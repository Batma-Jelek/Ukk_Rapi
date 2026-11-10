<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PIC - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .pic-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 24px;
            background: var(--secondary);
            color: #fff;
        }

        .pic-header h1 {
            font-size: 21px;
        }

        .pic-header small {
            display: block;
            margin-top: 4px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .pic-logout {
            width: auto;
            min-width: 72px;
            min-height: 36px;
            padding: 6px 12px;
            font-size: 13px;
        }

        .pic-main {
            width: 100%;
            margin: 0;
            padding: 28px clamp(20px, 3vw, 40px) 40px;
        }

        .pic-title {
            margin-bottom: 20px;
            color: var(--secondary);
            font-size: 21px;
            line-height: 1.4;
        }

        .pic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 292px), 1fr));
            align-items: start;
            gap: 18px;
        }

        .pic-card {
            overflow: hidden;
            padding: 0;
            border: 1px solid #dbe3ee;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
        }

        .pic-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .pic-room-name {
            display: inline-flex;
            max-width: 70%;
            min-height: 34px;
            align-items: center;
            padding: 6px 11px;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            background: #e0f2fe;
            color: #075985;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .pic-date {
            flex: 0 0 auto;
            color: #475569;
            font-size: 13px;
            text-align: right;
        }

        .pic-card-body {
            padding: 16px;
        }

        .pic-photo {
            display: block;
            width: 140px;
            height: 160px;
            margin: 0 auto 14px;
            border-radius: 9px;
            background: #f1f5f9;
            object-fit: cover;
        }

        .pic-no-photo {
            display: grid;
            min-height: 90px;
            margin-bottom: 14px;
            padding: 12px;
            place-items: center;
            border: 1px dashed #94a3b8;
            border-radius: 9px;
            color: #475569;
            font-size: 14px;
            text-align: center;
        }

        .pic-description {
            min-height: 48px;
            margin-bottom: 16px;
            color: #0f172a;
            font-size: 15px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .pic-description-text {
            display: block;
            margin-top: 3px;
            text-align: left;
            white-space: pre-wrap;
        }

        .pic-description-label {
            display: block;
            margin-bottom: 2px;
            color: #334155;
            font-weight: 700;
        }

        .pic-form {
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .pic-form .form-group {
            margin-bottom: 13px;
        }

        .pic-form .form-group label {
            margin-bottom: 6px;
            font-size: 15px;
        }

        .pic-form .form-control {
            min-height: 46px;
            padding: 10px 12px;
            border-color: #cbd5e1;
            font-size: 15px;
        }

        .pic-form input[type="file"] {
            height: auto;
            background: #fff;
        }

        .pic-save {
            min-height: 48px;
            margin-top: 2px;
            font-size: 15px;
        }

        .pic-message {
            margin-bottom: 14px;
            padding: 11px 13px;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 15px;
        }

        .pic-message:empty {
            display: none;
        }

        .pic-message.is-error {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .pic-empty {
            grid-column: 1 / -1;
            padding: 22px;
            border: 1px solid #dbe3ee;
            border-radius: 10px;
            background: #fff;
            color: #334155;
            font-size: 16px;
            text-align: center;
        }

        .pic-form .form-control:focus-visible,
        .pic-form .btn:focus-visible,
        .pic-logout:focus-visible {
            outline: 3px solid #0284c7;
            outline-offset: 2px;
        }

        .pic-save:disabled {
            cursor: wait;
            opacity: 0.7;
        }

        @media (max-width: 600px) {
            .pic-header {
                padding: 14px 16px;
            }

            .pic-header h1 {
                font-size: 18px;
            }

            .pic-main {
                padding: 22px 16px 32px;
            }

            .pic-title {
                font-size: 19px;
            }
        }
    </style>
</head>

<body>
    <header class="pic-header">
        <div>
            <h1>RAPI-Sekolah (PIC Panel)</h1>
            <small id="picName"></small>
        </div>
        <button type="button" onclick="logout()" class="btn btn-danger pic-logout">Logout</button>
    </header>

    <main class="pic-main">
        <h2 class="pic-title">Laporan Kerusakan Ruangan Tanggung Jawab Anda</h2>
        <div id="pageMessage" class="pic-message" role="status" aria-live="polite"></div>
        <div id="complaintsContainer" class="pic-grid" aria-live="polite">
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
                        ? `<img class="pic-photo" src="/storage/${String(item.photo_path).split('/').map(encodeURIComponent).join('/')}" alt="Foto kerusakan" loading="lazy">`
                        : '<div class="pic-no-photo">Pelapor tidak menyertakan foto kerusakan.</div>';

                    return `
                        <article class="card pic-card">
                            <div class="pic-card-head">
                                <span class="pic-room-name">${escapeHtml(roomName)}</span>
                                <time class="pic-date" datetime="${escapeHtml(item.reported_at || item.created_at || '')}">
                                    ${escapeHtml(formatDate(item.reported_at || item.created_at))}
                                </time>
                            </div>
                            <div class="pic-card-body">
                                ${photoHtml}
                                <p class="pic-description">
                                    <span class="pic-description-label">Deskripsi Kerusakan:</span>
                                    <span class="pic-description-text">${escapeHtml(item.description || 'Tidak ada deskripsi.')}</span>
                                </p>
                                <div class="pic-form">
                                    <div class="form-group">
                                        <label for="repairStatus_${item.id}">Update Status Perbaikan:</label>
                                        <select id="repairStatus_${item.id}" class="form-control">
                                            <option value="Belum Diperbaiki" ${item.status_repair === 'Belum Diperbaiki' ? 'selected' : ''}>Belum Diperbaiki</option>
                                            <option value="Menunggu Spare Part" ${item.status_repair === 'Menunggu Spare Part' ? 'selected' : ''}>Menunggu Spare Part</option>
                                            <option value="Sudah Diperbaiki" ${item.status_repair === 'Sudah Diperbaiki' ? 'selected' : ''}>Sudah Diperbaiki</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="repairPhoto_${item.id}">Foto Bukti Perbaikan (wajib):</label>
                                        <input type="file" id="repairPhoto_${item.id}" class="form-control" accept="image/*" required>
                                    </div>
                                    <button type="button" id="saveButton_${item.id}" class="btn btn-primary pic-save"
                                        onclick="updateRepairStatus(${Number(item.id)})">Simpan Status &amp; Foto Bukti</button>
                                </div>
                            </div>
                        </article>
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
