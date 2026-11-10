<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PIC - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .pic-message {
            margin-bottom: 16px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e3a8a;
        }

        .pic-message:empty {
            display: none;
        }

        .pic-message.is-error {
            background: #fef2f2;
            color: #991b1b;
        }

        .pic-room-name {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            background: #e0f2fe;
            color: #075985;
            font-size: 14px;
            font-weight: 600;
        }

        .pic-photo {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }

        .pic-empty {
            grid-column: 1 / -1;
            color: #64748b;
        }

        .pic-no-photo {
            margin-bottom: 12px;
            color: #64748b;
            font-size: 13px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <header class="top-navbar"
        style="display: flex; justify-content: space-between; align-items: center; background: var(--secondary); color: white;">
        <div>
            <h2>RAPI-Sekolah (PIC Panel)</h2>
            <small id="picName"></small>
        </div>
        <button type="button" onclick="logout()" class="btn btn-danger"
            style="width: auto; padding: 6px 16px;">Logout</button>
    </header>

    <main class="content-area" style="max-width: 1200px; margin: 0 auto;">
        <h3 style="margin-bottom: 20px;">Laporan Kerusakan Ruangan Tanggung Jawab Anda</h3>
        <div id="pageMessage" class="pic-message" role="status" aria-live="polite"></div>
        <div id="complaintsContainer" class="grid-cards" aria-live="polite">
            <p class="pic-empty">Laporan sedang dimuat...</p>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        let user = null;
        try {
            user = JSON.parse(localStorage.getItem('user') || 'null');
        } catch (error) {
            localStorage.removeItem('user');
        }

        if (!token || !user || Number(user.role_id) !== 2) {
            window.location.href = '/';
        } else {
            document.getElementById('picName').textContent = 'Petugas PIC: ' + (user.fullname || user.name || '-');
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]);
        }

        function formatDate(value) {
            if (!value) return 'Tanggal tidak tersedia';
            const date = new Date(value);
            return Number.isNaN(date.getTime())
                ? 'Tanggal tidak tersedia'
                : date.toLocaleDateString('id-ID', { day: 'numeric', month: 'numeric', year: 'numeric' });
        }

        function showMessage(message, isError = false) {
            const element = document.getElementById('pageMessage');
            element.textContent = message;
            element.classList.toggle('is-error', isError);
        }

        async function loadComplaints() {
            const container = document.getElementById('complaintsContainer');
            container.innerHTML = '<p class="pic-empty">Laporan sedang dimuat...</p>';

            try {
                const response = await fetch('/api/pic/complaints', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Laporan gagal dimuat. Silakan coba lagi.');
                }

                const complaints = Array.isArray(result.data) ? result.data : [];
                if (complaints.length === 0) {
                    container.innerHTML = '<p class="pic-empty">Tidak ada pengaduan kerusakan di ruangan Anda saat ini.</p>';
                    return;
                }

                container.innerHTML = complaints.map(item => {
                    const roomName = item.room?.room_name || `Ruangan ${item.room_id || ''}`;
                    const photoHtml = item.photo_path
                        ? `<div style="margin-bottom: 12px; text-align: center;">
                            <img class="pic-photo" src="/storage/${String(item.photo_path).split('/').map(encodeURIComponent).join('/')}" alt="Foto kerusakan" loading="lazy">
                           </div>`
                        : '<p class="pic-no-photo">(Tidak ada foto bukti)</p>';

                    return `
                        <div class="card">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:12px;">
                                <span class="badge badge-warning pic-room-name">${escapeHtml(roomName)}</span>
                                <small style="color:#64748b;">${escapeHtml(formatDate(item.reported_at || item.created_at))}</small>
                            </div>

                            ${photoHtml}

                            <p style="font-size:14px; margin-bottom:16px;">
                                <b>Deskripsi Kerusakan:</b><br>${escapeHtml(item.description || 'Tidak ada deskripsi.')}
                            </p>

                            <div class="form-group">
                                <label for="repairStatus_${item.id}">Update Status Perbaikan:</label>
                                <select id="repairStatus_${item.id}" class="form-control">
                                    <option value="Belum Diperbaiki" ${item.status_repair === 'Belum Diperbaiki' ? 'selected' : ''}>Belum Diperbaiki</option>
                                    <option value="Menunggu Spare Part" ${item.status_repair === 'Menunggu Spare Part' ? 'selected' : ''}>Menunggu Spare Part</option>
                                    <option value="Sudah Diperbaiki" ${item.status_repair === 'Sudah Diperbaiki' ? 'selected' : ''}>Sudah Diperbaiki</option>
                                </select>
                                <label for="repairPhoto_${item.id}" style="display:block; margin-top:12px;">Foto Bukti Perbaikan (wajib):</label>
                                <input type="file" id="repairPhoto_${item.id}" class="form-control" accept="image/*" required>
                                <button type="button" id="saveButton_${item.id}" class="btn btn-primary"
                                    style="margin-top:12px;"
                                    onclick="updateRepairStatus(${Number(item.id)})">Simpan Status &amp; Foto Bukti</button>
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                container.innerHTML = `
                    <div class="pic-empty">
                        <p>${escapeHtml(error.message || 'Terjadi kendala saat memuat laporan.')}</p>
                        <button type="button" class="btn btn-primary" style="max-width:180px; margin-top:12px;" onclick="loadComplaints()">Coba Lagi</button>
                    </div>`;
            }
        }

        async function updateRepairStatus(id) {
            const photoInput = document.getElementById(`repairPhoto_${id}`);
            const saveButton = document.getElementById(`saveButton_${id}`);
            if (!photoInput.files.length) {
                showMessage('Pilih foto bukti terlebih dahulu sebelum menyimpan status.', true);
                photoInput.focus();
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('status_repair', document.getElementById(`repairStatus_${id}`).value);
            formData.append('photo', photoInput.files[0]);
            saveButton.disabled = true;
            saveButton.textContent = 'Menyimpan...';
            showMessage('');

            try {
                const response = await fetch(`/api/complaints/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    const validationMessage = Object.values(result.errors || {}).flat()[0];
                    throw new Error(validationMessage || result.message || 'Status gagal disimpan. Silakan coba lagi.');
                }

                showMessage('Status perbaikan berhasil disimpan.');
                await loadComplaints();
            } catch (error) {
                showMessage(error.message || 'Status gagal disimpan. Periksa koneksi lalu coba lagi.', true);
                saveButton.disabled = false;
                saveButton.textContent = 'Simpan Status & Foto Bukti';
            }
        }

        function logout() {
            localStorage.clear();
            window.location.href = '/';
        }

        if (token && user && Number(user.role_id) === 2) {
            loadComplaints();
        }
    </script>
</body>

</html>
