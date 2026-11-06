<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - RAPI Sekolah</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #334155;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: #0f172a;
            color: #ffffff;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-header h2 {
            font-size: 22px;
            color: #38bdf8;
            margin-bottom: 4px;
        }

        .sidebar-header small {
            color: #94a3b8;
            font-size: 13px;
        }

        .sidebar-nav {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex-grow: 1;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: #0284c7;
            color: #ffffff;
        }

        .sidebar-footer {
            border-top: 1px solid #334155;
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .user-name {
            font-size: 13px;
            color: #e2e8f0;
            font-weight: 600;
        }

        .btn-logout {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }

        /* Main Content Styling */
        .main-content {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
        }

        .content-header h1 {
            font-size: 24px;
            color: #0f172a;
            margin-bottom: 24px;
        }

        /* Tab System */
        .section-tab {
            display: none;
        }

        .section-tab.active {
            display: block;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .card h3 {
            font-size: 18px;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            width: 100%;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: flex;
            gap: 16px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn-primary {
            background-color: #0284c7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0369a1;
        }

        .btn-warning {
            background-color: #d97706;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .btn-warning:hover {
            background-color: #b45309;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #059669;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div>
                <div class="sidebar-header">
                    <h2>RAPI-Web</h2>
                    <small>Panel Guru</small>
                </div>

                <nav class="sidebar-nav">
                    <!-- Modifikasi link menjadi fungsi showTab -->
                    <a class="nav-link active" onclick="showTab('layananTab', this)"> Form Layanan Guru</a>
                    <a class="nav-link" onclick="showTab('kalenderTab', this)"> Kalender Jadwal</a>
                </nav>
            </div>

            <div class="sidebar-footer">
                <span id="userNameLabel" class="user-name">Loading...</span>
                <button onclick="logout()" class="btn-logout">Logout</button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Tab 1: Layanan Peminjaman & Pelaporan -->
            <div id="layananTab" class="section-tab active">
                <header class="content-header">
                    <h1>Layanan Peminjaman & Pelaporan Ruangan</h1>
                </header>

                <div class="grid-2">
                    <!-- Form 1: Pengajuan Agenda Ruangan -->
                    <div class="card">
                        <h3> Pengajuan Agenda Ruangan</h3>
                        <form id="eventForm">
                            <div class="form-group">
                                <label>Nama Kegiatan / Agenda</label>
                                <input type="text" id="event_name" placeholder="Contoh: Rapat Koordinasi Ulangan"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Pilih Ruangan</label>
                                <select id="event_room_id" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Pelaksanaan</label>
                                <input type="date" id="date" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <input type="time" id="start_time" required>
                                </div>
                                <div class="form-group">
                                    <label>Waktu Selesai</label>
                                    <input type="time" id="end_time" required>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary">Kirim Pengajuan Agenda</button>
                        </form>
                    </div>

                    <!-- Form 2: Lapor Kerusakan Fasilitas -->
                    <div class="card">
                        <h3> Lapor Kerusakan Ruangan</h3>
                        <form id="complaintForm">
                            <div class="form-group">
                                <label>Pilih Ruangan Bermasalah</label>
                                <select id="complaint_room_id" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Deskripsi Kerusakan</label>
                                <textarea id="description"
                                    placeholder="Jelaskan detail kerusakan, contoh: AC tidak dingin / Proyektor mati..."
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Bukti Kerusakan (Opsional)</label>
                                <input type="file" name="photo" id="photo" accept="image/*" capture="environment">
                            </div>

                            <input type="hidden" name="photo_base64" id="input_photo_base64">

                            <button type="submit" class="btn-warning">Kirim Laporan Kerusakan</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Kalender Jadwal (Ditambahkan dari admin.blade) -->
            <div id="kalenderTab" class="section-tab">
                <header class="content-header">
                    <h1>Kalender Jadwal Ruangan</h1>
                </header>
                <div class="card" style="margin-bottom: 24px;">
                    <div class="grid-2">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="filterRoom">Filter Berdasarkan Ruangan</label>
                            <select id="filterRoom" onchange="renderCalendar()">
                                <option value="">-- Semua Ruangan --</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="filterDate">Filter Tanggal</label>
                            <input type="date" id="filterDate" onchange="renderCalendar()">
                        </div>
                    </div>
                </div>

                <h3 style="margin-bottom: 16px;">Jadwal Agenda Disetujui</h3>
                <div id="calendarGrid" class="grid-cards">
                    <p>Memuat jadwal kalender...</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        // Proteksi Akses
        if (!token || !user) {
            window.location.href = '/';
        }

        document.getElementById('userNameLabel').innerText = user.fullname || user.name || 'Guru';

        // TAB SYSTEM 
        function showTab(tabId, element) {
            document.querySelectorAll('.section-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            element.classList.add('active');

            // Jika tab kalender dibuka, muat data kalender jika belum termuat
            if (tabId === 'kalenderTab') {
                initCalendar();
            }
        }

        // 1. Function Load Daftar Ruangan ke Dropdown Layanan
        async function loadRoomOptions() {
            try {
                const res = await fetch('/api/rooms', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });

                if (!res.ok) return;

                const data = await res.json();
                const roomsList = Array.isArray(data) ? data : (data.data || []);

                const eventSelect = document.getElementById('event_room_id');
                const complaintSelect = document.getElementById('complaint_room_id');

                let optionsHtml = '<option value="">-- Pilih Ruangan --</option>';

                roomsList.forEach(r => {
                    optionsHtml += `<option value="${r.id}">${r.room_name || r.name} (Kapasitas: ${r.capacity || 0} Org)</option>`;
                });

                if (eventSelect) eventSelect.innerHTML = optionsHtml;
                if (complaintSelect) complaintSelect.innerHTML = optionsHtml;

            } catch (e) {
                console.error('Gagal memuat ruangan:', e);
            }
        }

        // 2. Submit Handler: Form Pengajuan
        document.getElementById('eventForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const payload = {
                event_name: document.getElementById('event_name').value,
                room_id: document.getElementById('event_room_id').value,
                date: document.getElementById('date').value,
                start_time: document.getElementById('start_time').value,
                end_time: document.getElementById('end_time').value
            };

            try {
                const res = await fetch('/api/events', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(payload)
                });

                const result = await res.json();

                if (res.ok) {
                    alert('Pengajuan agenda berhasil dikirim! Menunggu persetujuan Waka/Admin.');
                    document.getElementById('eventForm').reset();
                } else {
                    alert(result.message || 'Gagal mengajukan agenda ruangan.');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            }
        });

        // 3. Submit Handler: Form Pelaporan
        const photoInput = document.getElementById('photo');

        async function readFileAsDataUrl(file) {
            const image = await createImageBitmap(file);
            const maxWidth = 1024;
            const maxHeight = 768;
            const scale = Math.min(1, maxWidth / image.width, maxHeight / image.height);
            const canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(image.width * scale));
            canvas.height = Math.max(1, Math.round(image.height * scale));

            const context = canvas.getContext('2d');
            if (!context) {
                image.close();
                throw new Error('Browser tidak dapat memproses foto.');
            }

            context.drawImage(image, 0, 0, canvas.width, canvas.height);
            image.close();

            const compressedPhoto = await new Promise((resolve, reject) => {
                canvas.toBlob(blob => {
                    if (blob) {
                        resolve(blob);
                    } else {
                        reject(new Error('Gagal mengompres foto.'));
                    }
                }, 'image/jpeg', 0.65);
            });

            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => {
                    if (typeof reader.result === 'string') {
                        resolve(reader.result);
                    } else {
                        reject(new Error('Gagal membaca file foto.'));
                    }
                };
                reader.onerror = () => reject(reader.error || new Error('Gagal membaca file foto.'));
                reader.readAsDataURL(compressedPhoto);
            });
        }

        photoInput?.addEventListener('change', () => {
            window.capturedPhotoBase64 = null;
            document.getElementById('input_photo_base64').value = '';
        });

        document.getElementById('complaintForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const token = localStorage.getItem('token') || sessionStorage.getItem('token');
            if (!token) {
                alert('Sesi login Anda telah berakhir. Silakan login kembali.');
                window.location.href = '/';
                return;
            }

            const roomId = document.getElementById('complaint_room_id').value;
            const description = document.getElementById('description').value.trim();

            if (!roomId) {
                alert('Silakan pilih ruangan terlebih dahulu!');
                return;
            }
            if (description.length < 3) {
                alert('Deskripsi kerusakan minimal 3 karakter.');
                return;
            }

            try {
                const selectedPhoto = photoInput?.files?.[0];
                const photoData = selectedPhoto
                    ? await readFileAsDataUrl(selectedPhoto)
                    : window.capturedPhotoBase64 || document.getElementById('input_photo_base64')?.value || null;

                const payload = {
                    room_id: roomId,
                    description: description,
                    photo_base64: photoData
                };

                const res = await fetch('/api/complaints', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(payload)
                });

                const rawText = await res.text();
                let result;

                try {
                    result = JSON.parse(rawText);
                } catch (jsonErr) {
                    console.error('Server mengembalikan respon HTML/Error PHP:', rawText);
                    if (rawText.includes('Unable to create temporary file') ||
                        rawText.includes("POST data can't be buffered")) {
                        alert('Server PHP tidak dapat menulis file sementara untuk request. Minta administrator mengatur direktori temporary PHP yang dapat ditulis lalu restart Herd.');
                    } else {
                        alert('Terjadi kesalahan fatal pada server (HTML/PHP Error). Cek Console F12.');
                    }
                    return;
                }

                if (res.ok) {
                    alert('Laporan kerusakan berhasil terkirim ke PIC Ruangan!');
                    document.getElementById('complaintForm').reset();
                    window.capturedPhotoBase64 = null;
                    if (photoPreview) photoPreview.style.display = 'none';
                } else {
                    console.error('Validation Errors (422):', result.errors);
                    let errorMsg = result.message || 'Gagal mengirim laporan.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).flat().join('\n');
                    }
                    alert('Gagal Mengirim Laporan:\n' + errorMsg);
                }
            } catch (err) {
                console.error('Network/Server Error:', err);
                alert('Terjadi kesalahan jaringan atau server.');
            }
        });

        // =========================================================
        // TAMBAHKAN KODE KAMERA DI BAWAH INI (Baris 339 dan seterusnya)
        // =========================================================

        let cameraStream = null;
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const photoPreview = document.getElementById('photoPreview');
        const btnStartCamera = document.getElementById('btnStartCamera');
        const btnCapture = document.getElementById('btnCapture');
        const btnRetake = document.getElementById('btnRetake');

        // 1. Jalankan/Buka Kamera Real-time
        btnStartCamera?.addEventListener('click', async () => {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false
                });
                video.srcObject = cameraStream;
                video.style.display = 'block';
                photoPreview.style.display = 'none';
                btnStartCamera.style.display = 'none';
                btnCapture.style.display = 'inline-block';
                btnRetake.style.display = 'none';
            } catch (err) {
                alert('Gagal mengakses kamera: ' + err.message);
            }
        });

        // 2. Jepret & Kompres Foto (Menggunakan Synchronous toDataURL)
        btnCapture?.addEventListener('click', () => {
            const MAX_WIDTH = 1024;
            const MAX_HEIGHT = 768;
            let width = video.videoWidth || 640;
            let height = video.videoHeight || 480;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }
            canvas.width = width;
            canvas.height = height;
        })

        btnCapture?.addEventListener('click', () => {
            // 1. Deklarasikan ukuran video dan canvas (Wajib ada agar tidak error width/height)
            const MAX_WIDTH = 1024;
            const MAX_HEIGHT = 768;
            let width = video.videoWidth || 640;
            let height = video.videoHeight || 480;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;

            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, width, height);

            // 2. Simpan string Base64 ke variabel global dan ke input hidden
            window.capturedPhotoBase64 = canvas.toDataURL('image/jpeg', 0.5);

            const inputPhoto = document.getElementById('input_photo_base64');
            if (inputPhoto) {
                inputPhoto.value = window.capturedPhotoBase64;
            }

            // 3. Hentikan alur kamera dan perbarui UI
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }
            video.style.display = 'none';
            photoPreview.src = window.capturedPhotoBase64;
            photoPreview.style.display = 'block';
            btnCapture.style.display = 'none';
            btnRetake.style.display = 'inline-block';
        });

        // KALENDER SYSTEM (Diadaptasi dari admin.blade)
        let allEvents = [];

        async function initCalendar() {
            try {
                // Fetch Ruangan untuk Dropdown Filter Kalender
                const roomRes = await fetch('/api/rooms', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const roomData = await roomRes.json();
                const roomSelect = document.getElementById('filterRoom');

                // Reset dropdown filter
                roomSelect.innerHTML = '<option value="">-- Semua Ruangan --</option>';

                let roomsList = Array.isArray(roomData) ? roomData : (roomData.data || []);
                roomsList.forEach(r => {
                    roomSelect.innerHTML += `<option value="${r.id}">${r.room_name || r.name}</option>`;
                });

                // Fetch Event Kalender
                const eventRes = await fetch('/api/calendar', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const eventData = await eventRes.json();
                allEvents = eventData.data || eventData || [];

                renderCalendar();
            } catch (e) {
                console.error('Gagal memuat kalender:', e);
                document.getElementById('calendarGrid').innerHTML = '<p style="color:red;">Gagal memuat jadwal kalender.</p>';
            }
        }

        function renderCalendar() {
            const selectedRoom = document.getElementById('filterRoom').value;
            const selectedDate = document.getElementById('filterDate').value;
            const container = document.getElementById('calendarGrid');

            let filtered = allEvents.filter(item => {
                let matchRoom = selectedRoom === '' || item.room_id == selectedRoom;
                let matchDate = selectedDate === '' || item.date === selectedDate;
                return matchRoom && matchDate;
            });

            container.innerHTML = '';

            if (filtered.length === 0) {
                container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align:center;"><p style="color:#64748b;">Tidak ada agenda terdaftar pada kriteria ini.</p></div>';
                return;
            }

            filtered.forEach(item => {
                container.innerHTML += `
            <div class="card" style="margin-bottom:0;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <span class="badge badge-success">${item.room ? item.room.room_name : 'Ruang ID: ' + item.room_id}</span>
                    <small style="color:#64748b; font-weight:600;">${item.date}</small>
                </div>
                <h4 style="font-size:16px; margin-bottom:8px; color:#0f172a;">${item.event_name}</h4>
                <p style="font-size:13px; color:#64748b; margin-bottom:12px;">
                     <b>${item.start_time.substring(0, 5)} - ${item.end_time.substring(0, 5)} WIB</b>
                </p>
                <p style="font-size:12px; color:#334155; border-top:1px solid #e2e8f0; padding-top:8px;">
                    Penyelenggara: <b>${item.user ? item.user.fullname : 'Guru/Organisasi'}</b>
                </p>
            </div>
        `;
            });

        }
        function logout() {
            localStorage.clear();
            window.location.href = '/';
        }

        // Inisialisasi awal
        document.addEventListener('DOMContentLoaded', () => {
            loadRoomOptions();
        });
    </script>

</body>

</html>