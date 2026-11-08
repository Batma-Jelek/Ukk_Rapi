<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PIC - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <header class="top-navbar"
        style="display: flex; justify-content: space-between; align-items: center; background: var(--secondary); color: white;">
        <div>
            <h2>RAPI-Sekolah (PIC Panel)</h2>
            <small id="picName"></small>
        </div>
        <button onclick="logout()" class="btn btn-danger" style="width: auto; padding: 6px 16px;">Logout</button>
    </header>

    <main class="content-area" style="max-width: 1200px; margin: 0 auto;">
        <h3 style="margin-bottom: 20px;">Laporan Kerusakan Ruangan Tanggung Jawab Anda</h3>

        <div id="complaintsContainer" class="grid-cards">
            <p>Memuat laporan...</p>
        </div>
    </main>

    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        if (!token || !user || user.role_id !== 2) {
            window.location.href = '/';
        }
        document.getElementById('picName').innerText = 'Petugas PIC: ' + user.fullname;

        async function loadComplaints() {
            try {
                const res = await fetch('/api/pic/complaints', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await res.json();
                const container = document.getElementById('complaintsContainer');
                container.innerHTML = '';

                if (!data.data || data.data.length === 0) {
                    container.innerHTML = '<p>Tidak ada pengaduan kerusakan di ruangan Anda saat ini.</p>';
                    return;
                }

                data.data.forEach(item => {
                    // 1. Buat elemen gambar jika item.photo_path tersedia
                    const photoHtml = item.photo_path
                        ? `<div style="margin-bottom: 12px; text-align: center;">
             <img src="/storage/${item.photo_path}" 
                  alt="Foto Kerusakan" 
                  style="max-width: 100%; max-height: 200px; border-radius: 8px; object-fit: cover;">
           </div>`
                        : `<p style="color: #888; font-size: 12px; font-style: italic; margin-bottom: 12px;">(Tidak ada foto bukti)</p>`;

                    // 2. Render isi card beserta elemen fotonya
                    container.innerHTML += `
        <div class="card">
            <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                <span class="badge badge-warning">Room ID: ${item.room_id}</span>
                <small style="color:#9a3b8; ">${new Date(item.created_at).toLocaleDateString('id-ID')}</small>
            </div>
            
            ${photoHtml}

            <p style="font-size:14px; margin-bottom:16px;"><b>Deskripsi Kerusakan:</b><br>${item.description}</p>
            
            <div class="form-group">
                <label for="repairStatus_${item.id}">Update Status Perbaikan:</label>
                <select id="repairStatus_${item.id}" class="form-control">
                    <option value="Belum Diperbaiki" ${item.status_repair === 'Belum Diperbaiki' ? 'selected' : ''}>Belum Diperbaiki</option>
                    <option value="Menunggu Spare Part" ${item.status_repair === 'Menunggu Spare Part' ? 'selected' : ''}>Menunggu Spare Part</option>
                    <option value="Sudah Diperbaiki" ${item.status_repair === 'Sudah Diperbaiki' ? 'selected' : ''}>Sudah Diperbaiki</option>
                </select>
                <label for="repairPhoto_${item.id}" style="display:block; margin-top:12px;">Foto Bukti Perbaikan (wajib):</label>
                <input type="file" id="repairPhoto_${item.id}" class="form-control" accept="image/*" required>
                <button type="button" class="btn btn-primary" style="margin-top:12px;"
                    onclick="updateRepairStatus(${item.id})">Simpan Status &amp; Foto Bukti</button>
            </div>
        </div>
    `;
                });
            } catch (e) { console.error(e); }
        }

        async function updateRepairStatus(id) {
            const photoInput = document.getElementById(`repairPhoto_${id}`);
            if (!photoInput.files.length) {
                photoInput.reportValidity();
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('status_repair', document.getElementById(`repairStatus_${id}`).value);
            formData.append('photo', photoInput.files[0]);

            try {
                const res = await fetch(`/api/complaints/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: formData
                });
                const result = await res.json();

                if (!res.ok) {
                    const validationMessage = Object.values(result.errors || {}).flat()[0];
                    throw new Error(result.message || validationMessage || 'Gagal memperbarui status perbaikan.');
                }

                await loadComplaints();
                alert('Status perbaikan berhasil diperbarui!');
            } catch (error) {
                alert(error.message || 'Gagal memperbarui status perbaikan. Periksa koneksi lalu coba lagi.');
                await loadComplaints();
            }
        }

        function logout() {
            localStorage.clear();
            window.location.href = '/';
        }

        loadComplaints();
    </script>

</body>

</html>