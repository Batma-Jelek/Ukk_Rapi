<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Waka / Admin - RAPI Sekolah</title>
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

        .section-tab {
            display: none;
        }

        .section-tab.active {
            display: block;
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

        .form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }

        .form-group input,
        .form-group select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }


        /* Memberi jarak antar baris form */
        .form-group {
            margin-bottom: 18px;
            /* Jarak antar elemen input */
        }

        /* Memberi jarak antara teks label dengan kotak input */
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333333;
        }

        /* Memberi jarak atas khusus untuk tombol submit */
        .btn-submit {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #e00000;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #0284c7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-warning {
            background-color: #f50b0b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0369a1;
        }

        .btn-warning:hover {
            background-color: #d30909;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            padding: 12px 16px;
            font-weight: 600;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #059669;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .btn-approve {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
            margin-right: 4px;
        }

        .btn-reject {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }

        .veto-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.6);
        }

        .veto-modal.is-open {
            display: flex;
        }

        .veto-modal-dialog {
            width: min(100%, 520px);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .veto-modal-header,
        .veto-modal-body,
        .veto-modal-footer {
            padding: 20px 24px;
        }

        .veto-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #fef3c7;
            border-bottom: 1px solid #fde68a;
        }

        .veto-modal-header h2 {
            color: #78350f;
            font-size: 18px;
        }

        .veto-modal-close {
            border: 0;
            background: transparent;
            color: #475569;
            font-size: 28px;
            line-height: 1;
            cursor: pointer;
        }

        .veto-modal-body .form-group {
            margin-bottom: 18px;
        }

        .veto-modal-body select,
        .veto-modal-body textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .veto-modal-body textarea {
            resize: vertical;
        }

        .veto-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-secondary {
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #64748b;
            color: #ffffff;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-veto-submit {
            padding: 10px 16px;
            border: 0;
            border-radius: 6px;
            background: #f59e0b;
            color: #1e293b;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 480px) {
            .veto-modal {
                padding: 12px;
            }

            .veto-modal-header,
            .veto-modal-body,
            .veto-modal-footer {
                padding: 16px;
            }
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 220px;
                flex: 0 0 220px;
            }

            .main-content {
                min-width: 0;
                padding: 24px 20px;
            }

            .table-responsive table {
                min-width: 680px;
            }
        }

        @media (max-width: 680px) {
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
                gap: 4px;
                margin-top: 28px;
                padding: 0;
                overflow: visible;
            }

            .nav-link {
                flex: initial;
                min-height: 46px;
                padding: 11px 12px;
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
            }

            .main-content {
                padding: 18px 14px;
                overflow: visible;
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

            .form-group {
                min-width: 100%;
            }

            .main-content .grid-2 {
                grid-template-columns: 1fr !important;
            }

            .grid-cards {
                grid-template-columns: minmax(0, 1fr);
            }

            .table-responsive {
                max-width: 100%;
                -webkit-overflow-scrolling: touch;
            }

            .veto-modal {
                padding: 12px;
            }

            .veto-modal-dialog {
                width: 100%;
                max-height: calc(100dvh - 24px);
            }

            .veto-modal-header,
            .veto-modal-body,
            .veto-modal-footer {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <header class="mobile-nav-bar">
            <button type="button" class="mobile-menu-toggle" onclick="openMobileNav()" aria-label="Buka menu"
                aria-controls="mobileSidebar" aria-expanded="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    aria-hidden="true">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <strong>RAPI-Web</strong>
        </header>
        <button type="button" class="mobile-nav-backdrop" onclick="closeMobileNav()" aria-label="Tutup menu"></button>
        <aside class="sidebar" id="mobileSidebar">
            <div>
                <div class="sidebar-header">
                    <h2>RAPI</h2>
                    <small>Panel Waka / Admin</small>
                    <button type="button" class="mobile-nav-close" onclick="closeMobileNav()"
                        aria-label="Tutup menu">&times;</button>
                </div>
                <div class="mobile-profile">
                    <span class="mobile-profile-avatar" id="mobileProfileAvatar">A</span>
                    <div>
                        <strong id="mobileProfileName">Admin</strong>
                        <small>Panel Waka / Admin</small>
                    </div>
                </div>
                <nav class="sidebar-nav">
                    <a class="nav-link active" onclick="showTab('pengajuanTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 5h16v14H4zM8 9h8M8 13h8M8 17h5"></path>
                        </svg>Persetujuan Agenda
                    </a>
                    <a class="nav-link" onclick="showTab('ruanganTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 20V5l8-2 8 2v15M9 20v-5h6v5M8 8h.01M16 8h.01M8 11h.01M16 11h.01"></path>
                        </svg>Manajemen Ruangan
                    </a>
                    <a class="nav-link" onclick="showTab('perbaikanTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 6a5 5 0 0 0-6.5 6.5L3 17l4 4 4.5-4.5A5 5 0 0 0 18 10l-3 3-4-4 3-3z"></path>
                        </svg>Monitoring Perbaikan
                    </a>
                    <a class="nav-link" onclick="showTab('kalenderTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                            <path d="M16 3v4M8 3v4M3 10h18M8 14h2M14 14h2M8 17h2"></path>
                        </svg>Kalender Jadwal
                    </a>
                    <a class="nav-link" onclick="showTab('peminjamanTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h4"></path>
                        </svg>Pengajuan Peminjaman
                    </a>
                    <a class="nav-link" onclick="showTab('laporkerusakanTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 3 2.8 19h18.4L12 3zM12 9v4M12 16h.01"></path>
                        </svg>Laporan Kerusakan
                    </a>

                    <!-- Menu Baru: Manajemen Akun -->
                    <a class="nav-link" onclick="showTab('akunTab', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>Manajemen Akun
                    </a>
                </nav>
            </div>
            <div class="sidebar-footer">
                <span id="userNameLabel" class="user-name">Loading...</span>
                <button onclick="logout()" class="btn-logout">Logout</button>
            </div>
        </aside>

        <main class="main-content">
            <!-- Tab 1: Persetujuan Agenda -->
            <div id="pengajuanTab" class="section-tab active">
                <header class="content-header">
                    <h1>Persetujuan Pengajuan Agenda Ruangan</h1>
                </header>
                <div class="card">
                    <h3>Daftar Pengajuan Agenda Ruangan</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kegiatan</th>
                                    <th>Ruangan</th>
                                    <th>Waktu</th>
                                    <th>Pengaju</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="eventsTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #64748b; padding: 16px;">Memuat
                                        data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Manajemen Ruangan -->
            <div id="ruanganTab" class="section-tab">
                <header class="content-header">
                    <h1>Manajemen Ruangan Sekolah</h1>
                </header>
                <div class="card">
                    <h3> Tambah Ruangan Baru</h3>
                    <form id="addRoomForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama Ruangan</label>
                                <input type="text" id="room_name" placeholder="Contoh: Lab Komputer 1" required>
                            </div>
                            <div class="form-group">
                                <label>Kapasitas (Orang)</label>
                                <input type="number" id="capacity" placeholder="30" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi / Gedung</label>
                                <input type="text" id="location" placeholder="Contoh: Gedung B Lantai 2" required>
                            </div>
                            <div class="form-group">
                                <label>PIC Ruangan</label>
                                <select id="pic_id">
                                    <option value="">-- Pilih PIC --</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary">Tambah Ruangan</button>
                    </form>
                </div>
                <div class="card">
                    <h3> Daftar Ruangan</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Ruangan</th>
                                    <th>Kapasitas</th>
                                    <th>Lokasi</th>
                                    <th>PIC Ruangan</th>
                                </tr>
                            </thead>
                            <tbody id="roomsTableBody">
                                <tr>
                                    <td colspan="4" style="text-align: center; color: #64748b; padding: 16px;">Memuat
                                        ruangan...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Monitoring Perbaikan -->
            <div id="perbaikanTab" class="section-tab">
                <header class="content-header">
                    <h1>Monitoring Perbaikan Kerusakan & Alert PIC</h1>
                </header>
                <div class="card">
                    <h3> Daftar Laporan Kerusakan Ruangan</h3>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Ruangan</th>
                                    <th>Deskripsi Kerusakan</th>
                                    <th>Pelapor</th>
                                    <th>Status Perbaikan</th>
                                    <th>Aksi</th> <!-- KOLOM BARU -->
                                </tr>
                            </thead>
                            <tbody id="complaintsTableBody">
                                <tr>
                                    <!-- UBAH COLSPAN MENJADI 5 -->
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 16px;">Memuat
                                        laporan perbaikan...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!--kalender -->
            <div id="kalenderTab" class="section-tab">
                <div class="card" style="margin-bottom: 24px;">
                    <div class="grid-2" style="grid-template-columns: 1fr 1fr;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="filterRoom">Filter Berdasarkan Ruangan</label>
                            <select id="filterRoom" class="form-control" onchange="renderCalendar()">
                                <option value="">-- Semua Ruangan --</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="filterDate">Filter Tanggal</label>
                            <input type="date" id="filterDate" class="form-control" onchange="renderCalendar()">
                        </div>
                    </div>
                </div>

                <!-- Container Grid Agenda -->
                <h3 style="margin-bottom: 16px;">Jadwal Agenda Disetujui</h3>
                <div id="calendarGrid" class="grid-cards">
                    <p>Memuat jadwal kalender...</p>
                </div>
            </div>
            <div id="peminjamanTab" class="section-tab">
                <div class="card">
                    <h3> Pengajuan Agenda Ruangan</h3>
                    <form id="eventForm">
                        <div class="form-group">
                            <label>Nama Kegiatan / Agenda</label>
                            <input type="text" id="event_name" placeholder="Contoh: Rapat Koordinasi Ulangan" required>
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
            </div>

            <!-- Form 2: Lapor Kerusakan Fasilitas -->
            <div id="laporkerusakanTab" class="section-tab">
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

            <!-- TAB BARU: MANAJEMEN AKUN -->
            <div id="akunTab" class="section-tab">
                <header class="content-header">
                    <h1>Manajemen Akun Pengguna</h1>
                </header>

                <!-- 1. Form Tambah Akun Manual -->
                <div class="card" style="margin-bottom: 24px;">
                    <h3>Tambah Akun Manual</h3>
                    <form id="addAccountForm">
                        <div
                            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Nama
                                    Lengkap</label>
                                <input type="text" id="acc_name" class="form-control"
                                    placeholder="Contoh: Ahmad Subagja" required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Username</label>
                                <input type="text" id="acc_username" class="form-control" placeholder="Contoh: ahmad123"
                                    required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Password</label>
                                <input type="password" id="acc_password" class="form-control" placeholder="******"
                                    required>
                            </div>
                            <div class="form-group">
                                <label style="display: block; font-weight: 600; margin-bottom: 6px;">Role / Hak
                                    Akses</label>
                                <select id="acc_role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="2">PIC Ruang</option>
                                    <option value="3">Guru</option>
                                    <option value="4">Organisasi Sekolah</option>
                                </select>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label style="display: block; font-weight: 600; margin-bottom: 6px;">No HP / WhatsApp
                                    (Opsional)</label>
                                <input type="text" id="acc_phone" class="form-control"
                                    placeholder="Contoh: 08123456789">
                            </div>
                        </div>
                        <button type="submit" class="btn-primary" style="padding: 10px 20px; font-weight: 600;">Tambah
                            Akun</button>
                    </form>
                </div>

                <!-- 2. Form Import Excel -->
                <div class="card">
                    <h3>Import Akun via File Excel</h3>
                    <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                        Unggah file .xlsx atau .csv. Baris pertama (header) harus berisi: <strong>fullname, username,
                            password, role_id, phone</strong>.
                    </p>
                    <form id="importAccountForm">
                        <div class="form-group" style="margin-bottom: 16px;">
                            <input type="file" id="acc_excel" accept=".xlsx, .xls, .csv" class="form-control"
                                style="max-width: 400px;" required>
                        </div>
                        <button type="submit"
                            style="background-color: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            Upload & Import
                        </button>
                    </form>
                </div>

                <div class="card" style="margin-top: 24px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
                        <h3 style="margin: 0;">Daftar Akun</h3>
                        <label for="accountsRoleFilter" style="display: flex; align-items: center; gap: 8px;">
                            Tampilkan role:
                            <select id="accountsRoleFilter" class="form-control" style="min-width: 190px;">
                                <option value="">Semua Role</option>
                                <option value="2">PIC Ruang</option>
                                <option value="3">Guru</option>
                                <option value="4">Organisasi</option>
                            </select>
                        </label>
                    </div>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                                    <th style="padding: 12px 8px;">Nama</th>
                                    <th style="padding: 12px 8px;">Username</th>
                                    <th style="padding: 12px 8px;">Role</th>
                                    <th style="padding: 12px 8px;">No. HP</th>
                                </tr>
                            </thead>
                            <tbody id="accountsTableBody">
                                <tr>
                                    <td colspan="4" style="padding: 16px; text-align: center; color: #64748b;">
                                        Memuat daftar akun...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    </div>
    </div>

    </main>
    </div>

    <!-- Modal Hak Veto Pindah Ruangan -->
    <div class="veto-modal" id="vetoModal" aria-hidden="true">
        <div class="veto-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="vetoModalTitle">
            <div class="veto-modal-header">
                <h2 id="vetoModalTitle">Pindah Ruang Agenda</h2>
                <button type="button" class="veto-modal-close" onclick="closeVetoModal()"
                    aria-label="Tutup">&times;</button>
            </div>
            <div class="veto-modal-body">
                <input type="hidden" id="veto_event_id">

                <div class="form-group">
                    <label for="veto_new_room_id">Pilih Ruangan Baru</label>
                    <select id="veto_new_room_id" required>
                        <option value="">-- Pilih Ruangan Pengganti --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="veto_reassignment_reason">Alasan Pemindahan (Wajib)</label>
                    <textarea id="veto_reassignment_reason" rows="3"
                        placeholder="Jelaskan alasan pemindahan (misal: Ruangan dipakai untuk Acara Utama Sekolah)..."
                        required></textarea>
                </div>
            </div>
            <div class="veto-modal-footer">
                <button type="button" class="btn-secondary" onclick="closeVetoModal()">Batal</button>
                <button type="button" onclick="submitVeto()" class="btn-veto-submit">Eksekusi Veto</button>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));

        if (!token || !user || user.role_id !== 1) {
            window.location.href = '/';
        }

        document.getElementById('userNameLabel').innerText = user.fullname || user.name || 'Admin';

        const mobileProfileName = user.fullname || user.name || 'Admin';
        document.getElementById('mobileProfileName').textContent = mobileProfileName;
        document.getElementById('mobileProfileAvatar').textContent = mobileProfileName
            .trim()
            .split(/\s+/)
            .slice(0, 2)
            .map(part => part.charAt(0))
            .join('')
            .toUpperCase() || 'A';

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

        function showTab(tabId, element) {
            document.querySelectorAll('.section-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            element.classList.add('active');

            if (tabId === 'ruanganTab') {
                loadPicUsers();
                loadRooms();
            }
            if (tabId === 'akunTab') {
                loadAccounts();
            }
        }

        document.getElementById('accountsRoleFilter')?.addEventListener('change', loadAccounts);

        async function loadAccounts() {
            const tableBody = document.getElementById('accountsTableBody');
            if (!tableBody) return;

            tableBody.innerHTML = '<tr><td colspan="4" style="padding: 16px; text-align: center; color: #64748b;">Memuat daftar akun...</td></tr>';

            try {
                const roleId = document.getElementById('accountsRoleFilter').value;
                const query = roleId ? `?role_id=${encodeURIComponent(roleId)}` : '';
                const response = await fetch(`/api/users${query}`, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Daftar akun gagal dimuat.');
                }

                tableBody.replaceChildren();
                if (!result.data.length) {
                    const row = tableBody.insertRow();
                    const cell = row.insertCell();
                    cell.colSpan = 4;
                    cell.style.cssText = 'padding: 16px; text-align: center; color: #64748b;';
                    cell.textContent = 'Belum ada akun untuk role ini.';
                    return;
                }

                result.data.forEach(account => {
                    const row = tableBody.insertRow();
                    [
                        account.fullname || '-',
                        account.username || '-',
                        account.role?.role_name || '-',
                        account.phone_number || '-'
                    ].forEach(value => {
                        const cell = row.insertCell();
                        cell.style.padding = '12px 8px';
                        cell.style.borderBottom = '1px solid #e2e8f0';
                        cell.textContent = value;
                    });
                });
            } catch (error) {
                console.error('Gagal memuat daftar akun:', error);
                const row = tableBody.insertRow();
                const cell = row.insertCell();
                cell.colSpan = 4;
                cell.style.cssText = 'padding: 16px; text-align: center; color: #ef4444;';
                cell.textContent = error.message || 'Daftar akun gagal dimuat.';
            }
        }

        // 1. Load Data Agenda (Toleran Case-Sensitivity)
        async function loadEvents() {
            try {
                const response = await fetch('/api/events', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const events = Array.isArray(result) ? result : (result.data || []);
                const tableBody = document.getElementById('eventsTableBody');

                if (!events || events.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: #64748b; padding: 16px;">Belum ada pengajuan.</td></tr>`;
                    return;
                }

                let rows = '';
                events.forEach(event => {
                    const statusLower = (event.status || '').toLowerCase();
                    let statusBadge = '<span class="badge badge-warning">Pending</span>';
                    if (statusLower === 'approved') statusBadge = '<span class="badge badge-success">Disetujui</span>';
                    if (statusLower === 'rejected') statusBadge = '<span class="badge badge-danger">Ditolak</span>';

                    let actionButtons = '-';
                    if (statusLower === 'pending') {
                        actionButtons = `
    <button onclick="approveEvent(${event.id})" class="btn-approve">Approve</button>
    <button onclick="rejectEvent(${event.id})" class="btn-reject">Reject</button>
    `;
                    } else if (statusLower === 'approved' || statusLower === 'moved') {
                        actionButtons = `
    <button onclick="openVetoModal(${event.id})" class="btn-warning" style="background-color: #ffc107; color: #000; border: none; padding: 5px 10px; border-radius: 5px; font-weight: 600; cursor: pointer;">
        <i class="bi bi-arrow-left-right"></i> Pindah Ruang
    </button>
    `;
                    }

                    rows += `
                <tr>
                    <td><strong>${event.event_name}</strong></td>
                    <td>${event.room ? event.room.room_name : '-'}</td>
                    <td>${event.date} (${event.start_time} - ${event.end_time})</td>
                    <td>${event.user ? (event.user.fullname || event.user.name) : 'Guru'}</td>
                    <td>${statusBadge}</td>
                    <td>${actionButtons}</td>
                </tr>
            `;
                });
                tableBody.innerHTML = rows;
            } catch (error) {
                document.getElementById('eventsTableBody').innerHTML = `<tr><td colspan="6" style="text-align: center; color: #ef4444; padding: 16px;">Gagal memuat data pengajuan agenda.</td></tr>`;
            }
        }

        // 2. Load Daftar Ruangan (Toleran berbagai struktur JSON & Nama Kolom)
        async function loadRooms() {
            const tableBody = document.getElementById('roomsTableBody');
            if (!tableBody) return;

            try {
                const response = await fetch('/api/rooms', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP Error Status: ${response.status}`);
                }

                const result = await response.json();

                // Ekstraksi data aman untuk Array biasa, { data: [...] }, maupun Paginated { data: { data: [...] } }
                let rooms = [];
                if (Array.isArray(result)) {
                    rooms = result;
                } else if (Array.isArray(result.data)) {
                    rooms = result.data;
                } else if (result.data && Array.isArray(result.data.data)) {
                    rooms = result.data.data;
                }

                const eventRoomSelect = document.getElementById('event_room_id');
                if (eventRoomSelect) {
                    eventRoomSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';

                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = room.room_name || room.name || 'Tanpa Nama';
                        eventRoomSelect.appendChild(option);
                    });
                }

                const complaintRoomSelect = document.getElementById('complaint_room_id');
                if (complaintRoomSelect) {
                    complaintRoomSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';

                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = room.room_name || room.name || 'Tanpa Nama';
                        complaintRoomSelect.appendChild(option);
                    });
                }

                if (rooms.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 16px; color: #64748b;">Belum ada ruangan.</td></tr>`;
                    return;
                }

                let rows = '';
                rooms.forEach(r => {
                    // Toleransi jika nama kolom di DB adalah 'name' atau 'room_name'
                    const roomName = r.room_name || r.name || 'Tanpa Nama';

                    // Toleransi jika relasi PIC bernama 'pic' atau 'user'
                    let picName = 'Belum Ditentukan';
                    if (r.pic) {
                        picName = r.pic.fullname || r.pic.name || picName;
                    } else if (r.user) {
                        picName = r.user.fullname || r.user.name || picName;
                    }

                    rows += `
                <tr>
                    <td><strong>${roomName}</strong></td>
                    <td>${r.capacity || 0} Orang</td>
                    <td>${r.location || '-'}</td>
                    <td>${picName}</td>
                </tr>
            `;
                });

                tableBody.innerHTML = rows;

            } catch (e) {
                console.error('Error pada loadRooms():', e);
                tableBody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 16px; color: #ef4444;">Gagal memuat data ruangan. Silakan cek F12 Console.</td></tr>`;
            }
        }
        // 3. Load User PIC untuk Dropdown
        async function loadPicUsers() {
            try {
                const response = await fetch('/api/users?role_id=2', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const pics = Array.isArray(result) ? result : (result.data || []);

                const picSelect = document.getElementById('pic_id');
                if (picSelect) {
                    let options = '<option value="">-- Pilih PIC --</option>';
                    pics.forEach(pic => {
                        options += `<option value="${pic.id}">${pic.fullname || pic.name}</option>`;
                    });
                    picSelect.innerHTML = options;
                }
            } catch (e) {
                console.error('Gagal memuat PIC:', e);
            }
        }

        // 4. Load Monitoring Perbaikan
        async function loadComplaints() {
            try {
                const response = await fetch('/api/complaints', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                const complaints = Array.isArray(result) ? result : (result.data || []);
                // console.log('Loaded complaints:', complaints);
                const tableBody = document.getElementById('complaintsTableBody');

                let rows = '';
                complaints.forEach(c => {
                    // 1. Badge Status Perbaikan
                    let badge = c.status_repair === 'Sudah Diperbaiki'
                        ? '<span class="badge badge-success" style="background:#28a745; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Selesai Diperbaiki</span>'
                        : '<span class="badge badge-danger" style="background:#dc3545; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Belum Diperbaiki</span>';

                    // 2. Logika Tombol WhatsApp Alert PIC
                    let actionBtn = '-';

                    if (c.status_repair !== 'Sudah Diperbaiki') {
                        let roomName = c.room ? (c.room.room_name || c.room.name) : 'Ruangan';
                        let picName = (c.room && c.room.pic) ? (c.room.pic.fullname || c.room.pic.name) : 'PIC Lab';

                        // Ambil nomor HP PIC
                        let phone = (c.room && c.room.pic) ? (c.room.pic.phone_number) : null;

                        if (phone) {
                            let waNumber = phone.replace(/[^0-9]/g, '');
                            if (waNumber.startsWith('0')) {
                                waNumber = '62' + waNumber.substring(1);
                            }

                            let textMsg = encodeURIComponent(`Halo ${picName} (PIC ${roomName}), ini peringatan dari Admin. Laporan kerusakan: *"${c.description}"* di ${roomName} terpantau belum ditangani. Mohon segera dicek dan diperbaiki. Terima kasih.`);

                            actionBtn = `
                    <a href="https://wa.me/${waNumber}?text=${textMsg}" target="_blank" style="background-color: #25D366; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; display: inline-flex; align-items: center;">
                        Alert PIC
                    </a>`;
                        } else {
                            actionBtn = '<span style="font-size: 11px; color: #94a3b8; font-style: italic;">No HP Kosong</span>';
                        }
                    }

                    rows += `
            <tr>
                <td><strong>${c.room ? (c.room.room_name || c.room.name) : '-'}</strong></td>
                <td>${c.description || '-'}</td>
                <td>${c.user ? (c.user.fullname || c.user.name) : 'Guru'}</td>
                <td>${badge}</td>
                <td>${actionBtn}</td>
            </tr>
            `;
                });

                tableBody.innerHTML = rows || `<tr><td colspan="5" style="text-align: center; color: #64748b; padding: 16px;">Belum ada laporan kerusakan.</td></tr>`;
                tableBody.innerHTML = rows || `<tr><td colspan="4" style="text-align: center; padding: 16px;">Belum ada laporan kerusakan.</td></tr>`;
            } catch (e) {
                console.error(e);
            }
        }

        // Submit Form Tambah Ruangan
        document.getElementById('addRoomForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const picSelect = document.getElementById('pic_id');
            const picValue = picSelect ? picSelect.value : null;

            // Ambil elemen input name/room_name secara fleksibel
            const nameInput = document.getElementById('room_name') || document.getElementById('name');
            const capacityInput = document.getElementById('capacity');
            const locationInput = document.getElementById('location');

            const payload = {
                name: nameInput ? nameInput.value : '',          // Kolom DB Laravel standar
                room_name: nameInput ? nameInput.value : '',     // Cadangan jika nama kolom 'room_name'
                capacity: capacityInput ? parseInt(capacityInput.value) : 0,
                location: locationInput ? locationInput.value : '',
                pic_id: picValue ? parseInt(picValue) : null
            };

            try {
                const res = await fetch('/api/rooms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(payload)
                });

                const result = await res.json();

                if (res.ok) {
                    alert('Ruangan berhasil ditambahkan!');
                    this.reset();
                    await loadRooms(); // Memanggil ulang daftar ruangan secara otomatis
                } else {
                    const errorMsg = result.message || (result.errors ? Object.values(result.errors).flat().join('\n') : 'Gagal menambahkan ruangan.');
                    alert(errorMsg);
                }
            } catch (err) {
                console.error('Submit Room Error:', err);
                alert('Terjadi kesalahan jaringan.');
            }
        });
        async function approveEvent(id) {
            if (!confirm('Setujui agenda ini?')) return;
            await fetch(`/api/events/${id}/approve`, { method: 'PUT', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } });
            loadEvents();
        }

        // Submit Form Tambah Akun Manual
        document.getElementById('addAccountForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const token = localStorage.getItem('token');

            const payload = {
                fullname: document.getElementById('acc_name').value, // atau name (sesuaikan kolom DB)
                username: document.getElementById('acc_username').value,
                password: document.getElementById('acc_password').value,
                role_id: document.getElementById('acc_role').value,
                phone_number: document.getElementById('acc_phone').value // atau no_wa
            };

            try {
                const res = await fetch('/api/users/manual', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await res.json();
                if (res.ok) {
                    alert('Akun berhasil dibuat!');
                    this.reset();
                    loadAccounts();
                } else {
                    alert('Gagal membuat akun: ' + (result.message || 'Cek kembali data Anda.'));
                }
            } catch (err) {
                console.error('Error Add Account:', err);
                alert('Terjadi kesalahan jaringan.');
            }
        });

        // Submit Form Import Excel
        document.getElementById('importAccountForm')?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const fileInput = document.getElementById('acc_excel');
            if (!fileInput.files[0]) return alert('Pilih file Excel terlebih dahulu!');

            const token = localStorage.getItem('token');
            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            try {
                // Tampilkan indikator loading (opsional)
                const btnSubmit = this.querySelector('button[type="submit"]');
                btnSubmit.innerText = 'Mengunggah...';
                btnSubmit.disabled = true;

                const res = await fetch('/api/users/import', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                        // CATATAN PENTING: Jangan set Content-Type. 
                        // Browser akan otomatis mengatur multipart/form-data dan boundary-nya untuk file.
                    },
                    body: formData
                });

                const result = await res.json();
                if (res.ok) {
                    alert(`Import berhasil! ${result.imported_count} akun ditambahkan.`);
                    this.reset();
                    loadAccounts();
                } else {
                    alert('Gagal import: ' + (result.message || 'Format file salah.'));
                }
            } catch (err) {
                console.error('Error Import Account:', err);
                alert('Terjadi kesalahan jaringan saat mengunggah file.');
            } finally {
                const btnSubmit = this.querySelector('button[type="submit"]');
                btnSubmit.innerText = 'Upload & Import';
                btnSubmit.disabled = false;
            }
        });

        // ====================================================
        // HAK VETO: FUNGSI DIPASANG KE WINDOW AGAR DIBACA ONCLICK
        // ====================================================

        window.openVetoModal = async function (eventId) {
            const modalEl = document.getElementById('vetoModal');
            if (!modalEl) {
                console.error('Elemen modal pemindahan ruangan tidak ditemukan.');
                return;
            }

            document.getElementById('veto_event_id').value = eventId;
            document.getElementById('veto_reassignment_reason').value = '';
            const roomSelect = document.getElementById('veto_new_room_id');
            roomSelect.innerHTML = '<option value="">Memuat daftar ruangan...</option>';
            roomSelect.disabled = true;

            modalEl.classList.add('is-open');
            modalEl.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';

            try {
                const token = localStorage.getItem('token');
                const res = await fetch('/api/rooms', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) {
                    throw new Error(`Gagal memuat daftar ruangan (HTTP ${res.status}).`);
                }

                const result = await res.json();
                const rooms = Array.isArray(result)
                    ? result
                    : (Array.isArray(result.data) ? result.data : result.data?.data || []);

                roomSelect.innerHTML = '<option value="">-- Pilih Ruangan Pengganti --</option>';
                rooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.room_name || room.name || 'Tanpa Nama';
                    roomSelect.appendChild(option);
                });

                if (rooms.length === 0) {
                    roomSelect.innerHTML = '<option value="">Belum ada ruangan yang tersedia</option>';
                    return;
                }

                roomSelect.disabled = false;
                roomSelect.focus();
            } catch (err) {
                console.error('Gagal memuat daftar ruangan pengganti:', err);
                roomSelect.innerHTML = '<option value="">Gagal memuat ruangan</option>';
                alert(err.message || 'Gagal memuat daftar ruangan pengganti.');
            }

        };

        window.closeVetoModal = function () {
            const modalEl = document.getElementById('vetoModal');
            if (!modalEl) return;

            modalEl.classList.remove('is-open');
            modalEl.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        document.getElementById('vetoModal')?.addEventListener('click', event => {
            if (event.target === event.currentTarget) {
                window.closeVetoModal();
            }
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && document.getElementById('vetoModal')?.classList.contains('is-open')) {
                window.closeVetoModal();
            }
        });

        window.submitVeto = async function () {
            const eventId = document.getElementById('veto_event_id').value;
            const newRoomId = document.getElementById('veto_new_room_id').value;
            const reason = document.getElementById('veto_reassignment_reason').value;

            if (!newRoomId) {
                alert('Silakan pilih ruangan pengganti terlebih dahulu!');
                return;
            }
            if (!reason || reason.trim().length < 5) {
                alert('Alasan pemindahan wajib diisi minimal 5 karakter!');
                return;
            }

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`/api/events/${eventId}/reassign`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        new_room_id: newRoomId,
                        reassignment_reason: reason
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert(data.message || 'Ruangan berhasil dipindahkan sepihak!');
                    window.closeVetoModal();

                    // Reload Tabel dan Kalender
                    if (typeof loadEvents === 'function') loadEvents();
                    if (typeof initCalendar === 'function') initCalendar();
                } else {
                    alert(data.message || 'Gagal memindahkan ruangan.');
                }
            } catch (error) {
                console.error("Error Hak Veto:", error);
                alert('Terjadi kesalahan koneksi ke server.');
            }
        };

        async function rejectEvent(id) {
            if (!confirm('Tolak agenda ini?')) return;
            await fetch(`/api/events/${id}/reject`, { method: 'PUT', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } });
            loadEvents();
        }

        function logout() {
            localStorage.clear();
            window.location.href = '/';
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadEvents();
            loadRooms();
            loadPicUsers();
            loadComplaints();
        });




        // KALENDER
        let allEvents = [];

        async function initCalendar() {
            try {
                // Fetch Ruangan untuk Dropdown Filter
                const roomRes = await fetch('/api/rooms');
                const roomData = await roomRes.json();
                const roomSelect = document.getElementById('filterRoom');

                (roomData.data || []).forEach(r => {
                    roomSelect.innerHTML += `<option value="${r.id}">${r.room_name}</option>`;
                });

                // Fetch Event Kalender
                const eventRes = await fetch('/api/calendar');
                const eventData = await eventRes.json();
                allEvents = eventData.data || [];

                renderCalendar();
            } catch (e) {
                console.error('Gagal memuat kalender:', e);
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
                container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align:center;"><p style="color:var(--text-muted);">Tidak ada agenda terdaftar pada kriteria ini.</p></div>';
                return;
            }

            filtered.forEach(item => {
                container.innerHTML += `
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <span class="badge badge-success">${item.room ? item.room.room_name : 'Ruang ID: ' + item.room_id}</span>
                    <small style="color:var(--text-muted); font-weight:600;">${item.date}</small>
                </div>
                <h4 style="font-size:16px; margin-bottom:8px; color:var(--secondary);">${item.event_name}</h4>
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:12px;">
                     <b>${item.start_time.substring(0, 5)} - ${item.end_time.substring(0, 5)} WIB</b>
                </p>
                <p style="font-size:12px; color:var(--text-main); border-top:1px solid var(--border-color); padding-top:8px;">
                    Penyelenggara: <b>${item.user ? item.user.fullname : 'Guru/Organisasi'}</b>
                </p>
            </div>
        `;
            });
        }

        initCalendar();

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
                    alert('Pengajuan agenda berhasil dikirim');
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

    </script>
</body>

</html>