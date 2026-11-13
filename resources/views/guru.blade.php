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
            flex: 0 0 260px;
            background-color: #0f172a;
            color: #ffffff;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mobile-nav-bar,
        .mobile-nav-backdrop,
        .mobile-nav-close,
        .mobile-profile {
            display: none;
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

        .nav-link svg {
            width: 24px;
            height: 24px;
            flex: 0 0 24px;
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
            min-width: 0;
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

            .dashboard-container {
                display: block;
            }

            .mobile-nav-bar {
                position: sticky;
                top: 0;
                z-index: 900;
                display: flex;
                align-items: center;
                gap: 14px;
                min-height: 60px;
                padding: 10px 16px;
                background: #ffffff;
                border-bottom: 1px solid #e2e8f0;
                color: #0f172a;
            }

            .mobile-menu-toggle,
            .mobile-nav-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border: 0;
                border-radius: 8px;
                background: transparent;
                color: #334155;
                cursor: pointer;
            }

            .mobile-menu-toggle svg {
                width: 24px;
                height: 24px;
            }

            .mobile-nav-backdrop.is-open {
                position: fixed;
                inset: 0;
                z-index: 1000;
                display: block;
                border: 0;
                background: rgba(15, 23, 42, 0.48);
            }

            .sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 1001;
                display: flex;
                width: min(320px, 86vw);
                height: 100vh;
                height: 100dvh;
                padding: 16px 14px;
                overflow-y: auto;
                background: #ffffff;
                color: #1e293b;
                box-shadow: 12px 0 32px rgba(15, 23, 42, 0.12);
                transform: translateX(-105%);
                transition: transform 0.25s ease;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-header {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                min-height: 44px;
            }

            .sidebar-header h2,
            .sidebar-header small {
                display: none;
            }

            .mobile-nav-close {
                font-size: 30px;
                line-height: 1;
            }

            .sidebar-nav {
                flex-direction: column;
                gap: 8px;
                margin-top: 16px;
                overflow: visible;
            }

            .nav-link {
                flex: initial;
                min-height: 46px;
                color: #334155;
                white-space: normal;
            }

            .nav-link:hover,
            .nav-link.active {
                background: #f1f5f9;
                color: #0f172a;
            }

            .nav-link svg {
                width: 20px;
                height: 20px;
                flex: 0 0 20px;
                color: #64748b;
            }

            .mobile-profile {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 20px 8px 8px;
            }

            .mobile-profile-avatar {
                display: grid;
                width: 44px;
                height: 44px;
                flex: 0 0 44px;
                place-items: center;
                border-radius: 50%;
                background: #e0f2fe;
                color: #0369a1;
                font-size: 17px;
                font-weight: 700;
            }

            .mobile-profile strong,
            .mobile-profile small {
                display: block;
            }

            .mobile-profile strong {
                overflow: hidden;
                max-width: 210px;
                color: #1e293b;
                font-size: 14px;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .mobile-profile small {
                margin-top: 3px;
                color: #64748b;
                font-size: 12px;
            }

            .sidebar-footer {
                flex-direction: column;
                align-items: stretch;
                margin-top: auto;
                padding-top: 16px;
                border-color: #e2e8f0;
            }

            .sidebar-footer .user-name {
                display: none;
            }

            .btn-logout {
                width: 100%;
                min-width: 100px;
            }

            .main-content {
                min-width: 0;
                padding: 22px 18px;
            }

            .grid-cards {
                grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
            }

            .form-row {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .nav-link {
                padding: 10px 12px;
                font-size: 13px;
            }

            .main-content {
                padding: 16px 12px;
            }

            .content-header h1 {
                font-size: 21px;
                line-height: 1.3;
            }

            .card {
                padding: 18px 14px;
            }

            .form-row {
                gap: 0;
            }

            .grid-cards {
                grid-template-columns: minmax(0, 1fr);
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

        .booking-history-card {
            margin-top: 24px;
            padding: 24px;
        }

        .booking-history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .booking-history-header h3 {
            margin: 0 0 6px;
            color: #0f172a;
            font-size: 20px;
            line-height: 1.4;
        }

        .booking-history-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .booking-history-header > .booking-history-refresh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            min-height: 44px;
            max-width: max-content;
            padding: 10px 18px;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .booking-history-scroll {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            -webkit-overflow-scrolling: touch;
        }

        .booking-history-table {
            width: 100%;
            min-width: 760px;
            border-collapse: separate;
            border-spacing: 0;
            text-align: left;
        }

        .booking-history-table th {
            padding: 14px 18px;
            background: #f1f5f9;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        .booking-history-table td {
            padding: 16px 18px;
            border-top: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
            vertical-align: middle;
        }

        .booking-history-table tbody tr {
            background: #fff;
            transition: background-color 0.15s ease;
        }

        .booking-history-table tbody tr:hover {
            background: #f8fafc;
        }

        .booking-history-table td:first-child {
            min-width: 190px;
            color: #0f172a;
            font-weight: 600;
        }

        .booking-history-table td:nth-child(2) {
            min-width: 170px;
        }

        .booking-history-table td:nth-child(3),
        .booking-history-table td:nth-child(4),
        .booking-history-table td:nth-child(5) {
            white-space: nowrap;
        }

        .booking-status {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
        }

        .booking-history-empty {
            padding: 24px 18px !important;
            color: #64748b !important;
            text-align: center;
        }

        @media (max-width: 768px) {
            .booking-history-card {
                padding: 18px 14px;
            }

            .booking-history-header {
                align-items: stretch;
            }

            .booking-history-header > .booking-history-refresh {
                width: auto;
                align-self: flex-start;
            }

            .booking-history-scroll::after {
                content: 'Geser tabel ke samping untuk melihat semua kolom';
                display: block;
                padding: 9px 12px;
                border-top: 1px solid #e2e8f0;
                color: #64748b;
                font-size: 12px;
                text-align: center;
            }
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

        .notification-toast-container {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 1200;
            display: grid;
            width: min(380px, calc(100vw - 32px));
            gap: 10px;
        }

        .notification-toast {
            position: relative;
            padding: 14px 42px 14px 16px;
            border: 1px solid #bae6fd;
            border-left: 4px solid #0284c7;
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
        }

        .notification-toast strong {
            display: block;
            margin-bottom: 5px;
            color: #075985;
        }

        .notification-toast p {
            font-size: 14px;
            line-height: 1.45;
        }

        .notification-toast button {
            position: absolute;
            top: 8px;
            right: 8px;
            border: 0;
            background: transparent;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
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
        <header class="mobile-nav-bar">
            <button type="button" class="mobile-menu-toggle" onclick="openMobileNav()"
                aria-label="Buka menu" aria-controls="mobileSidebar" aria-expanded="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <strong>RAPI-Web</strong>
        </header>
        <button type="button" class="mobile-nav-backdrop" onclick="closeMobileNav()"
            aria-label="Tutup menu"></button>
        <!-- Sidebar -->
        <aside class="sidebar" id="mobileSidebar">
            <div>
                <div class="sidebar-header">
                    <h2>RAPI-Web</h2>
                    <small>Panel Guru</small>
                    <button type="button" class="mobile-nav-close" onclick="closeMobileNav()"
                        aria-label="Tutup menu">&times;</button>
                </div>
                <div class="mobile-profile">
                    <span class="mobile-profile-avatar" id="mobileProfileAvatar">G</span>
                    <div>
                        <strong id="mobileProfileName">Guru</strong>
                        <small>Panel Guru</small>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <!-- Modifikasi link menjadi fungsi showTab -->
                    <a class="nav-link active" onclick="showTab('layananTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                            <path d="M8 8h8M8 12h8M8 16h5"></path>
                        </svg>Form Layanan Guru
                    </a>
                    <a class="nav-link" onclick="showTab('kalenderTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M16 3v4M8 3v4M3 10h18M8 14h2M14 14h2M8 17h2"></path>
                        </svg>Kalender Jadwal
                    </a>
                    <a class="nav-link" onclick="showTab('monitoringKerusakanTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 6a5 5 0 0 0-6.5 6.5L3 17l4 4 4.5-4.5A5 5 0 0 0 18 10l-3 3-4-4 3-3z"></path>
                        </svg>Monitoring Kerusakan
                    </a>
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
                                    <input type="time" id="start_time" step="60" required>
                                </div>
                                <div class="form-group">
                                    <label>Waktu Selesai</label>
                                    <input type="time" id="end_time" step="60" required>
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
                                <label>Foto Bukti Kerusakan (Opsional, PNG/JPG)</label>
                                <input type="file" name="photo" id="photo" accept=".png,.jpg,.jpeg,image/png,image/jpeg" capture="environment">
                            </div>

                            <input type="hidden" name="photo_base64" id="input_photo_base64">

                            <button type="submit" class="btn-warning">Kirim Laporan Kerusakan</button>
                        </form>
                    </div>
                </div>
                <div class="card booking-history-card">
                    <div class="booking-history-header">
                        <div>
                            <h3>Riwayat Peminjaman Ruangan</h3>
                            <p>Pantau pengajuan dan status persetujuannya. Daftar diperbarui otomatis.</p>
                        </div>
                        <button type="button" class="btn-primary booking-history-refresh" onclick="loadMyBookings()">Muat Ulang</button>
                    </div>
                    <div class="booking-history-scroll">
                        <table class="booking-history-table">
                            <thead>
                                <tr>
                                    <th>Kegiatan</th>
                                    <th>Ruangan</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="myBookingsBody">
                                <tr><td colspan="5" class="booking-history-empty">Memuat riwayat peminjaman...</td></tr>
                            </tbody>
                        </table>
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

            <div id="monitoringKerusakanTab" class="section-tab">
                <header class="content-header">
                    <h1>Monitoring Kerusakan</h1>
                </header>
                <div class="card">
                    <p style="color:#64748b; margin-bottom:16px;">Daftar ini hanya menampilkan laporan yang Anda buat. Informasi bersifat baca-saja.</p>
                    <div id="ownComplaintsList" class="grid-cards">
                        <p>Memuat laporan kerusakan...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="notificationToastContainer" class="notification-toast-container"
        role="region" aria-label="Notifikasi" aria-live="polite"></div>

    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        // Proteksi Akses
        if (!token || !user) {
            window.location.href = '/';
        }

        document.getElementById('userNameLabel').innerText = user.fullname || user.name || 'Guru';

        const mobileProfileName = user.fullname || user.name || 'Guru';
        document.getElementById('mobileProfileName').textContent = mobileProfileName;
        document.getElementById('mobileProfileAvatar').textContent = mobileProfileName
            .trim()
            .split(/\s+/)
            .slice(0, 2)
            .map(part => part.charAt(0))
            .join('')
            .toUpperCase() || 'G';

        function showNotificationToast(notification) {
            const container = document.getElementById('notificationToastContainer');
            const toast = document.createElement('article');
            toast.className = 'notification-toast';

            const title = document.createElement('strong');
            title.textContent = notification.title || 'Notifikasi';
            const message = document.createElement('p');
            message.textContent = notification.message || '';
            const closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.setAttribute('aria-label', 'Tutup notifikasi');
            closeButton.textContent = '×';
            closeButton.addEventListener('click', () => toast.remove());

            toast.append(title, message, closeButton);
            container.appendChild(toast);
            window.setTimeout(() => toast.remove(), 10000);
        }

        let isLoadingNotifications = false;
        async function loadNotifications() {
            if (!token || document.visibilityState !== 'visible' || isLoadingNotifications) return;
            isLoadingNotifications = true;

            try {
                const response = await fetch('/api/notifications', {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Notifikasi gagal dimuat.');
                }

                for (const notification of (Array.isArray(result.data) ? result.data : [])) {
                    showNotificationToast(notification);
                    const readResponse = await fetch(`/api/notifications/${notification.id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    if (!readResponse.ok) {
                        throw new Error('Notifikasi gagal ditandai sudah dibaca.');
                    }
                }
            } catch (error) {
                console.error('Gagal memuat notifikasi:', error);
            } finally {
                isLoadingNotifications = false;
            }
        }

        setInterval(loadNotifications, 10000);
        document.addEventListener('visibilitychange', loadNotifications);

        function openMobileNav() {
            document.getElementById('mobileSidebar').classList.add('is-open');
            document.querySelector('.mobile-nav-backdrop').classList.add('is-open');
            document.querySelector('.mobile-menu-toggle').setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileNav() {
            document.getElementById('mobileSidebar').classList.remove('is-open');
            document.querySelector('.mobile-nav-backdrop').classList.remove('is-open');
            document.querySelector('.mobile-menu-toggle').setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
            link.addEventListener('click', closeMobileNav);
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeMobileNav();
            }
        });

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
            if (tabId === 'monitoringKerusakanTab') {
                loadOwnComplaints();
            }
        }

        async function loadOwnComplaints() {
            const container = document.getElementById('ownComplaintsList');
            container.replaceChildren(Object.assign(document.createElement('p'), { textContent: 'Memuat laporan kerusakan...' }));

            try {
                const response = await fetch('/api/complaints', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const result = await response.json();
                if (!response.ok || result.status !== 'success') {
                    throw new Error(result.message || 'Laporan kerusakan gagal dimuat.');
                }

                container.replaceChildren();
                if (!result.data.length) {
                    container.append(Object.assign(document.createElement('p'), {
                        textContent: 'Anda belum mengirim laporan kerusakan.'
                    }));
                    return;
                }

                result.data.forEach(complaint => {
                    const card = document.createElement('article');
                    card.className = 'card';

                    const room = document.createElement('h3');
                    room.textContent = complaint.room?.room_name || 'Ruangan';
                    card.appendChild(room);

                    const description = document.createElement('p');
                    description.textContent = complaint.description || '-';
                    card.appendChild(description);

                    const status = document.createElement('p');
                    status.style.marginTop = '12px';
                    status.textContent = `Status perbaikan: ${complaint.status_repair || 'Belum Diperbaiki'}`;
                    card.appendChild(status);

                    [[complaint.photo_path, 'Foto laporan'], [complaint.repair_photo_path, 'Foto bukti perbaikan']].forEach(([path, label]) => {
                        const section = document.createElement('div');
                        section.style.marginTop = '12px';
                        const caption = document.createElement('p');
                        caption.textContent = label;
                        section.appendChild(caption);

                        if (path) {
                            const image = document.createElement('img');
                            image.src = `/storage/${path.split('/').map(encodeURIComponent).join('/')}`;
                            image.alt = label;
                            image.style.cssText = 'max-width:100%; max-height:200px; border-radius:8px; object-fit:cover;';
                            section.appendChild(image);
                        } else {
                            const empty = document.createElement('small');
                            empty.textContent = 'Belum tersedia';
                            section.appendChild(empty);
                        }
                        card.appendChild(section);
                    });

                    container.appendChild(card);
                });
            } catch (error) {
                console.error('Gagal memuat laporan kerusakan:', error);
                container.replaceChildren(Object.assign(document.createElement('p'), {
                    textContent: error.message || 'Laporan kerusakan gagal dimuat.'
                }));
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

        const eventDateInput = document.getElementById('date');
        function updateEventDateMinimum() {
            const today = new Date();
            const localDate = [
                today.getFullYear(),
                String(today.getMonth() + 1).padStart(2, '0'),
                String(today.getDate()).padStart(2, '0')
            ].join('-');
            eventDateInput.min = localDate;
            if (eventDateInput.value && eventDateInput.value < localDate) {
                eventDateInput.value = '';
            }
        }

        updateEventDateMinimum();
        eventDateInput?.addEventListener('focus', updateEventDateMinimum);

        function escapeBookingText(value) {
            return String(value ?? '').replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]);
        }

        function bookingStatus(status) {
            const statuses = {
                pending: ['Menunggu Persetujuan', '#fef3c7', '#92400e'],
                approved: ['Disetujui', '#dcfce7', '#166534'],
                rejected: ['Ditolak', '#fee2e2', '#991b1b'],
                moved: ['Ruangan Dipindahkan', '#e0f2fe', '#075985']
            };
            return statuses[String(status || '').toLowerCase()] || ['Status tidak diketahui', '#e2e8f0', '#334155'];
        }

        function formatBookingDate(value) {
            const [year, month, day] = String(value || '').slice(0, 10).split('-').map(Number);
            return year && month && day
                ? new Date(year, month - 1, day).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                })
                : 'Tanggal tidak tersedia';
        }

        let myBookingsData = [];
        let myBookingsRequestVersion = 0;
        const recentlySubmittedBookings = new Map();

        function renderMyBookings(bookings) {
            const tableBody = document.getElementById('myBookingsBody');
            if (bookings.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="booking-history-empty">Belum ada pengajuan peminjaman ruangan.</td></tr>';
                return;
            }

            tableBody.innerHTML = bookings.map(booking => {
                const [label, background, color] = bookingStatus(booking.status);
                return `
                    <tr>
                        <td>${escapeBookingText(booking.event_name || '-')}</td>
                        <td>${escapeBookingText(booking.room?.room_name || `Ruangan ${booking.room_id}`)}</td>
                        <td>${escapeBookingText(formatBookingDate(booking.date))}</td>
                        <td>${escapeBookingText(`${String(booking.start_time || '').slice(0, 5)}–${String(booking.end_time || '').slice(0, 5)} WIB`)}</td>
                        <td><span class="booking-status" style="background:${background}; color:${color};">${label}</span></td>
                    </tr>`;
            }).join('');
        }

        async function loadMyBookings() {
            const requestVersion = ++myBookingsRequestVersion;
            const tableBody = document.getElementById('myBookingsBody');

            try {
                const response = await fetch('/api/my-events', {
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Riwayat peminjaman gagal dimuat.');
                }

                if (requestVersion !== myBookingsRequestVersion) return;
                if (!Array.isArray(result.data)) {
                    throw new Error('Format data riwayat peminjaman tidak valid.');
                }
                myBookingsData = result.data;
                const serverBookingIds = new Set(myBookingsData.map(booking => String(booking.id)));
                recentlySubmittedBookings.forEach((booking, id) => {
                    if (!serverBookingIds.has(id)) myBookingsData.push(booking);
                });
                renderMyBookings(myBookingsData);
            } catch (error) {
                if (requestVersion !== myBookingsRequestVersion) return;
                if (myBookingsData.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="booking-history-empty" style="color:#991b1b !important;">${escapeBookingText(error.message || 'Riwayat peminjaman gagal dimuat.')}</td></tr>`;
                }
                console.error('Gagal memperbarui riwayat peminjaman:', error);
            }
        }

        setInterval(() => {
            if (document.visibilityState === 'visible') loadMyBookings();
        }, 30000);

        // 2. Submit Handler: Form Pengajuan
        document.getElementById('eventForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            updateEventDateMinimum();
            if (!eventDateInput.checkValidity()) {
                eventDateInput.reportValidity();
                return;
            }

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
                    if (result.data?.id) {
                        recentlySubmittedBookings.set(String(result.data.id), result.data);
                        myBookingsData = [
                            result.data,
                            ...myBookingsData.filter(booking => String(booking.id) !== String(result.data.id))
                        ];
                        renderMyBookings(myBookingsData);
                    }
                    document.getElementById('eventForm').reset();
                    await loadMyBookings();
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

        async function isAllowedPhotoFile(file) {
            if (!file || !/\.(png|jpe?g)$/i.test(file.name)) return false;
            if (file.type && !['image/png', 'image/jpeg', 'image/jpg'].includes(file.type.toLowerCase())) return false;

            try {
                const signature = new Uint8Array(await file.slice(0, 8).arrayBuffer());
                const isPng = signature.length >= 8 &&
                    signature[0] === 0x89 && signature[1] === 0x50 &&
                    signature[2] === 0x4e && signature[3] === 0x47 &&
                    signature[4] === 0x0d && signature[5] === 0x0a &&
                    signature[6] === 0x1a && signature[7] === 0x0a;
                const isJpeg = signature.length >= 3 &&
                    signature[0] === 0xff && signature[1] === 0xd8 && signature[2] === 0xff;

                return isPng || isJpeg;
            } catch (error) {
                return false;
            }
        }

        photoInput?.addEventListener('change', async () => {
            window.capturedPhotoBase64 = null;
            document.getElementById('input_photo_base64').value = '';
            const selectedPhoto = photoInput.files?.[0];
            if (selectedPhoto && !(await isAllowedPhotoFile(selectedPhoto))) {
                photoInput.value = '';
                alert('Sistem foto harus berformat PNG atau JPG.');
            }
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
                if (selectedPhoto && !(await isAllowedPhotoFile(selectedPhoto))) {
                    photoInput.value = '';
                    alert('Sistem foto harus berformat PNG atau JPG.');
                    photoInput.focus();
                    return;
                }
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
            loadMyBookings();
            loadNotifications();
        });
    </script>

</body>

</html>