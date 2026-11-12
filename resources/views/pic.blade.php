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

        .pic-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 22px;
            padding-bottom: 10px;
            border-bottom: 1px solid #cbd5e1;
        }

        .pic-tab {
            min-height: 44px;
            padding: 10px 16px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: #475569;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .pic-tab:hover {
            background: #e0f2fe;
            color: #075985;
        }

        .pic-tab.is-active {
            border-color: #bae6fd;
            background: #e0f2fe;
            color: #075985;
        }

        .pic-tab-panel[hidden] {
            display: none;
        }

        .pic-calendar-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 14px;
            padding: 14px 16px;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            background: #fff;
        }

        .pic-calendar-toolbar h3 {
            margin: 0;
            color: #0f172a;
            font-size: 20px;
        }

        .pic-calendar-nav {
            display: flex;
            gap: 8px;
        }

        .pic-calendar-nav button {
            min-width: 40px;
            min-height: 40px;
            padding: 7px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .pic-calendar-nav button:hover {
            border-color: #0284c7;
            background: #f0f9ff;
            color: #0369a1;
        }

        .pic-calendar-nav .pic-calendar-today {
            border-color: #0284c7;
            background: #0284c7;
            color: #fff;
        }

        .pic-calendar-scroll {
            overflow-x: auto;
            border-radius: 10px;
            -webkit-overflow-scrolling: touch;
        }

        .pic-calendar-weekdays,
        .pic-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            min-width: 760px;
        }

        .pic-calendar-weekdays {
            border: 1px solid #dbe3ee;
            border-bottom: 0;
            border-radius: 10px 10px 0 0;
            background: #eaf4fb;
        }

        .pic-calendar-weekday {
            padding: 11px 6px;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
        }

        .pic-calendar-grid {
            overflow: hidden;
            border: 1px solid #dbe3ee;
            border-radius: 0 0 10px 10px;
            background: #fff;
        }

        .pic-calendar-day {
            display: block;
            width: 100%;
            min-width: 0;
            min-height: 120px;
            padding: 8px;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
            color: inherit;
            font: inherit;
            text-align: left;
            cursor: pointer;
        }

        .pic-calendar-day:hover {
            background: #f0f9ff;
        }

        .pic-calendar-day:focus-visible {
            position: relative;
            z-index: 1;
            outline: 3px solid #0284c7;
            outline-offset: -3px;
        }

        .pic-calendar-day:nth-child(7n) {
            border-right: 0;
        }

        .pic-calendar-day.is-other-month {
            background: #f8fafc;
        }

        .pic-calendar-day.is-other-month .pic-calendar-day-number {
            color: #94a3b8;
        }

        .pic-calendar-day.is-today {
            background: #f0f9ff;
        }

        .pic-calendar-day-number {
            display: flex;
            width: 28px;
            height: 28px;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            border-radius: 50%;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        .pic-calendar-day.is-today .pic-calendar-day-number {
            background: #0284c7;
            color: #fff;
        }

        .pic-calendar-events {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .pic-calendar-complaint {
            overflow: hidden;
            padding: 5px 6px;
            border-left: 3px solid #0284c7;
            border-radius: 5px;
            background: #e0f2fe;
            color: #0c4a6e;
            font-size: 12px;
            line-height: 1.35;
        }

        .pic-calendar-complaint strong,
        .pic-calendar-complaint span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pic-calendar-complaint span {
            margin-top: 2px;
            color: #334155;
            font-size: 11px;
        }

        .pic-calendar-more {
            padding: 2px 5px;
            color: #0369a1;
            font-size: 12px;
            font-weight: 700;
        }

        .pic-calendar-empty {
            margin-bottom: 12px;
            padding: 12px 14px;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            background: #f0f9ff;
            color: #334155;
        }

        .pic-repair-modal[hidden] {
            display: none;
        }

        .pic-repair-modal {
            position: fixed;
            z-index: 1100;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(15, 23, 42, 0.58);
        }

        .pic-repair-dialog {
            width: min(100%, 620px);
            max-height: min(82vh, 720px);
            overflow: hidden;
            border: 1px solid #dbe3ee;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.24);
        }

        .pic-repair-dialog-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .pic-repair-dialog-header h3 {
            color: #0f172a;
            font-size: 19px;
        }

        .pic-repair-dialog-header p {
            margin-top: 4px;
            color: #475569;
            font-size: 14px;
        }

        .pic-repair-modal-close {
            display: grid;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            place-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #fff;
            color: #334155;
            font-size: 24px;
            line-height: 1;
            cursor: pointer;
        }

        .pic-repair-modal-close:hover {
            border-color: #0284c7;
            color: #0369a1;
        }

        .pic-repair-list {
            max-height: calc(min(82vh, 720px) - 90px);
            overflow-y: auto;
            padding: 16px 20px 20px;
        }

        .pic-repair-item {
            margin-bottom: 12px;
            padding: 14px;
            border: 1px solid #dbe3ee;
            border-left: 4px solid #0284c7;
            border-radius: 9px;
            background: #fff;
        }

        .pic-repair-item:last-child {
            margin-bottom: 0;
        }

        .pic-repair-item-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .pic-repair-item h4 {
            color: #0f172a;
            font-size: 16px;
        }

        .pic-repair-item time {
            flex: 0 0 auto;
            color: #475569;
            font-size: 13px;
        }

        .pic-repair-item p {
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .pic-repair-status {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 9px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-size: 12px;
            font-weight: 700;
        }

        .pic-repair-modal-message {
            padding: 20px 4px;
            color: #475569;
            text-align: center;
        }

        @media (max-width: 600px) {
            .pic-calendar-toolbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .pic-calendar-nav {
                width: 100%;
            }

            .pic-calendar-nav button {
                flex: 1;
            }

            .pic-repair-modal {
                padding: 12px;
            }

            .pic-repair-dialog-header,
            .pic-repair-list {
                padding-right: 16px;
                padding-left: 16px;
            }

            .pic-repair-item-top {
                flex-direction: column;
                gap: 4px;
            }
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
        <nav class="pic-tabs" role="tablist" aria-label="Navigasi dashboard PIC">
            <button type="button" class="pic-tab is-active" id="complaintsTabButton"
                role="tab" aria-selected="true" aria-controls="complaintsPanel"
                onclick="showPicTab('complaintsPanel', this)">Laporan Kerusakan</button>
            <button type="button" class="pic-tab" id="calendarTabButton" role="tab" aria-selected="false"
                aria-controls="calendarPanel" onclick="showPicTab('calendarPanel', this)">Kalender</button>
        </nav>

        <section id="complaintsPanel" class="pic-tab-panel" role="tabpanel"
            aria-labelledby="complaintsTabButton">
            <h3 style="margin-bottom: 20px;">Laporan Kerusakan Ruangan Tanggung Jawab Anda</h3>
            <div id="pageMessage" class="pic-message" role="status" aria-live="polite"></div>
            <div id="complaintsContainer" class="grid-cards" aria-live="polite">
                <p class="pic-empty">Laporan sedang dimuat...</p>
            </div>
        </section>

        <section id="calendarPanel" class="pic-tab-panel" role="tabpanel" aria-labelledby="calendarTabButton" hidden>
            <div class="pic-calendar-toolbar">
                <h3 id="picCalendarMonth">Kalender Kerusakan</h3>
                <div class="pic-calendar-nav" aria-label="Navigasi kalender">
                    <button type="button" onclick="changePicCalendarMonth(-1)" aria-label="Bulan sebelumnya">‹</button>
                    <button type="button" class="pic-calendar-today" onclick="goToPicCalendarToday()">Hari ini</button>
                    <button type="button" onclick="changePicCalendarMonth(1)" aria-label="Bulan berikutnya">›</button>
                </div>
            </div>
            <p id="picCalendarEmpty" class="pic-calendar-empty" hidden></p>
            <div class="pic-calendar-scroll">
                <div class="pic-calendar-weekdays" aria-hidden="true">
                    <div class="pic-calendar-weekday">Min</div>
                    <div class="pic-calendar-weekday">Sen</div>
                    <div class="pic-calendar-weekday">Sel</div>
                    <div class="pic-calendar-weekday">Rab</div>
                    <div class="pic-calendar-weekday">Kam</div>
                    <div class="pic-calendar-weekday">Jum</div>
                    <div class="pic-calendar-weekday">Sab</div>
                </div>
                <div id="picCalendarGrid" class="pic-calendar-grid" aria-label="Kalender jadwal"
                    aria-live="polite">
                    <p class="pic-empty">Laporan kerusakan sedang dimuat...</p>
                </div>
            </div>
        </section>
    </main>

    <div id="picRepairModal" class="pic-repair-modal" role="presentation" hidden
        onclick="if (event.target === this) closePicRepairModal()">
        <section class="pic-repair-dialog" role="dialog" aria-modal="true"
            aria-labelledby="picRepairModalTitle" aria-describedby="picRepairModalDate" tabindex="-1">
            <header class="pic-repair-dialog-header">
                <div>
                    <h3 id="picRepairModalTitle">Laporan Kerusakan</h3>
                    <p id="picRepairModalDate"></p>
                </div>
                <button type="button" class="pic-repair-modal-close" onclick="closePicRepairModal()"
                    aria-label="Tutup pop-up">&times;</button>
            </header>
            <div id="picRepairList" class="pic-repair-list" aria-live="polite"></div>
        </section>
    </div>

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
                : date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'numeric',
                    year: 'numeric',
                    timeZone: 'Asia/Jakarta'
                });
        }

        function getReportDateKey(value) {
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return '';

            const parts = new Intl.DateTimeFormat('en-CA', {
                timeZone: 'Asia/Jakarta',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).formatToParts(date);
            const dateParts = Object.fromEntries(parts.map(part => [part.type, part.value]));
            return `${dateParts.year}-${dateParts.month}-${dateParts.day}`;
        }

        function formatReportTime(value) {
            const date = new Date(value);
            return Number.isNaN(date.getTime())
                ? 'Waktu tidak tersedia'
                : date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hourCycle: 'h23',
                    timeZone: 'Asia/Jakarta'
                });
        }

        function showMessage(message, isError = false) {
            const element = document.getElementById('pageMessage');
            element.textContent = message;
            element.classList.toggle('is-error', isError);
        }

        let picCalendarViewDate = new Date();
        picCalendarViewDate.setDate(1);
        let picComplaints = [];
        let picComplaintsLoaded = false;
        let lastFocusedCalendarDay = null;

        function showPicTab(panelId, button) {
            document.querySelectorAll('.pic-tab-panel').forEach(panel => {
                panel.hidden = panel.id !== panelId;
            });
            document.querySelectorAll('.pic-tab').forEach(tab => {
                tab.classList.toggle('is-active', tab === button);
                tab.setAttribute('aria-selected', tab === button ? 'true' : 'false');
            });

            if (panelId === 'calendarPanel' && !picComplaintsLoaded) {
                loadPicCalendar();
            } else if (panelId === 'calendarPanel') {
                renderPicCalendar();
            }
        }

        function picCalendarDateKey(date) {
            return [
                date.getFullYear(),
                String(date.getMonth() + 1).padStart(2, '0'),
                String(date.getDate()).padStart(2, '0')
            ].join('-');
        }

        function formatPicCalendarDate(dateKey, options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) {
            const [year, month, day] = dateKey.split('-').map(Number);
            return new Date(year, month - 1, day).toLocaleDateString('id-ID', options);
        }

        async function openPicRepairModal(dateKey, trigger) {
            const modal = document.getElementById('picRepairModal');
            const list = document.getElementById('picRepairList');
            lastFocusedCalendarDay = trigger || document.activeElement;
            document.getElementById('picRepairModalDate').textContent = formatPicCalendarDate(dateKey);
            modal.hidden = false;
            list.innerHTML = '<p class="pic-repair-modal-message">Memuat laporan kerusakan...</p>';
            modal.querySelector('.pic-repair-modal-close').focus();

            try {
                if (!picComplaintsLoaded) {
                    const response = await fetch('/api/pic/complaints', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Daftar laporan kerusakan gagal dimuat.');
                    }
                    picComplaints = Array.isArray(result.data) ? result.data : [];
                    picComplaintsLoaded = true;
                }

                const reports = picComplaints.filter(item =>
                    getReportDateKey(item.reported_at || item.created_at) === dateKey
                );

                if (reports.length === 0) {
                    list.innerHTML = '<p class="pic-repair-modal-message">Tidak ada laporan kerusakan pada tanggal ini.</p>';
                    return;
                }

                list.innerHTML = reports.map(item => {
                    const roomName = item.room?.room_name || `Ruangan ${item.room_id || ''}`;
                    const timestamp = item.reported_at || item.created_at;
                    const timeLabel = formatReportTime(timestamp);
                    const timeText = timeLabel === 'Waktu tidak tersedia' ? timeLabel : `${timeLabel} WIB`;
                    const status = item.status_repair || 'Belum Diperbaiki';

                    return `
                        <article class="pic-repair-item">
                            <div class="pic-repair-item-top">
                                <h4>${escapeHtml(roomName)}</h4>
                                <time datetime="${escapeHtml(timestamp || '')}">${escapeHtml(timeText)}</time>
                            </div>
                            <p>${escapeHtml(item.description || 'Tidak ada deskripsi kerusakan.')}</p>
                            <span class="pic-repair-status">${escapeHtml(status)}</span>
                        </article>`;
                }).join('');
            } catch (error) {
                list.innerHTML = `<p class="pic-repair-modal-message">${escapeHtml(error.message || 'Daftar laporan kerusakan gagal dimuat.')}</p>`;
            }
        }

        function closePicRepairModal() {
            const modal = document.getElementById('picRepairModal');
            if (modal.hidden) return;
            modal.hidden = true;
            if (lastFocusedCalendarDay && document.contains(lastFocusedCalendarDay)) {
                lastFocusedCalendarDay.focus();
            }
        }

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closePicRepairModal();
            }
        });

        function changePicCalendarMonth(offset) {
            picCalendarViewDate = new Date(
                picCalendarViewDate.getFullYear(),
                picCalendarViewDate.getMonth() + offset,
                1
            );
            renderPicCalendar();
        }

        function goToPicCalendarToday() {
            picCalendarViewDate = new Date();
            picCalendarViewDate.setDate(1);
            renderPicCalendar();
        }

        async function loadPicCalendar() {
            const container = document.getElementById('picCalendarGrid');
            container.innerHTML = '<p class="pic-empty">Laporan kerusakan sedang dimuat...</p>';

            try {
                const response = await fetch('/api/pic/complaints', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.message || 'Laporan kerusakan gagal dimuat.');
                }

                picComplaints = Array.isArray(result.data) ? result.data : [];
                picComplaintsLoaded = true;
                renderPicCalendar();
            } catch (error) {
                container.innerHTML = `
                    <p class="pic-empty" style="grid-column:1/-1;">
                        ${escapeHtml(error.message || 'Laporan kerusakan gagal dimuat.')}
                        <button type="button" class="btn btn-primary" style="width:auto; margin:8px;" onclick="loadPicCalendar()">Coba Lagi</button>
                    </p>`;
            }
        }

        function renderPicCalendar() {
            const container = document.getElementById('picCalendarGrid');
            const emptyMessage = document.getElementById('picCalendarEmpty');
            const year = picCalendarViewDate.getFullYear();
            const month = picCalendarViewDate.getMonth();
            const firstDay = new Date(year, month, 1);
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const totalDays = Math.ceil((firstDay.getDay() + daysInMonth) / 7) * 7;
            const todayKey = picCalendarDateKey(new Date());
            const complaintPrefix = `${year}-${String(month + 1).padStart(2, '0')}-`;
            const monthComplaints = picComplaints.filter(item =>
                getReportDateKey(item.reported_at || item.created_at).startsWith(complaintPrefix)
            );
            const complaintsByDate = new Map();

            document.getElementById('picCalendarMonth').textContent =
                picCalendarViewDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            emptyMessage.hidden = monthComplaints.length > 0;
            emptyMessage.textContent = 'Belum ada laporan kerusakan pada bulan ini.';

            picComplaints.forEach(item => {
                const complaintDate = getReportDateKey(item.reported_at || item.created_at);
                if (!complaintDate) return;
                if (!complaintsByDate.has(complaintDate)) {
                    complaintsByDate.set(complaintDate, []);
                }
                complaintsByDate.get(complaintDate).push(item);
            });

            const firstCalendarDate = new Date(year, month, 1 - firstDay.getDay());
            const days = [];
            for (let index = 0; index < totalDays; index++) {
                const date = new Date(firstCalendarDate);
                date.setDate(firstCalendarDate.getDate() + index);
                const dateKey = picCalendarDateKey(date);
                const dayComplaints = complaintsByDate.get(dateKey) || [];
                const dateLabel = formatPicCalendarDate(dateKey);
                const complaintMarkup = dayComplaints.slice(0, 3).map(item => {
                    const roomName = item.room?.room_name || `Ruang ${item.room_id}`;
                    const description = item.description || 'Kerusakan tanpa deskripsi';
                    const status = item.status_repair || 'Belum Diperbaiki';

                    return `
                        <div class="pic-calendar-complaint" title="${escapeHtml(`${roomName} — ${description} — ${status}`)}">
                            <strong>${escapeHtml(description)}</strong>
                            <span>${escapeHtml(`${roomName} · ${status}`)}</span>
                        </div>`;
                }).join('');
                const moreComplaints = dayComplaints.length > 3
                    ? `<div class="pic-calendar-more">+${dayComplaints.length - 3} laporan lainnya</div>`
                    : '';

                days.push(`
                    <button type="button" class="pic-calendar-day${date.getMonth() !== month ? ' is-other-month' : ''}${dateKey === todayKey ? ' is-today' : ''}"
                        aria-label="${dayComplaints.length} laporan kerusakan, ${escapeHtml(dateLabel)}" aria-haspopup="dialog"
                        onclick="openPicRepairModal('${dateKey}', this)">
                        <div class="pic-calendar-day-number">${date.getDate()}</div>
                        <div class="pic-calendar-events">${complaintMarkup}${moreComplaints}</div>
                    </button>`);
            }

            container.innerHTML = days.join('');
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
                picComplaints = complaints;
                picComplaintsLoaded = true;
                if (!document.getElementById('calendarPanel').hidden) {
                    renderPicCalendar();
                }
                if (complaints.length === 0) {
                    container.innerHTML = '<p class="pic-empty">Tidak ada pengaduan kerusakan di ruangan Anda saat ini.</p>';
                    return;
                }

                container.innerHTML = complaints.map(item => {
                    const roomName = item.room?.room_name || `Ruangan ${item.room_id || ''}`;
                    const timestamp = item.reported_at || item.created_at;
                    const reportTime = formatReportTime(timestamp);
                    const reportTimeLabel = reportTime === 'Waktu tidak tersedia'
                        ? reportTime
                        : `${reportTime} WIB`;
                    const photoHtml = item.photo_path
                        ? `<div style="margin-bottom: 12px; text-align: center;">
                            <img class="pic-photo" src="/storage/${String(item.photo_path).split('/').map(encodeURIComponent).join('/')}" alt="Foto kerusakan" loading="lazy">
                           </div>`
                        : '<p class="pic-no-photo">(Tidak ada foto bukti)</p>';

                    return `
                        <div class="card">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; margin-bottom:12px;">
                                <span class="badge badge-warning pic-room-name">${escapeHtml(roomName)}</span>
                                <div style="color:#64748b; text-align:right;">
                                    <time datetime="${escapeHtml(timestamp || '')}"
                                        style="display:block; font-size:13px;">
                                        ${escapeHtml(formatDate(timestamp))}
                                    </time>
                                    <small style="font-size:12px;">${escapeHtml(reportTimeLabel)}</small>
                                </div>
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
                                <label for="repairPhoto_${item.id}" style="display:block; margin-top:12px;">Foto Bukti Perbaikan (wajib, PNG/JPG):</label>
                                <input type="file" id="repairPhoto_${item.id}" class="form-control" accept=".png,.jpg,.jpeg,image/png,image/jpeg" required>
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

        async function isAllowedRepairPhoto(file) {
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

        document.addEventListener('change', async event => {
            const photoInput = event.target;
            if (!(photoInput instanceof HTMLInputElement) || !photoInput.id.startsWith('repairPhoto_')) return;

            const photoFile = photoInput.files?.[0];
            if (photoFile && !(await isAllowedRepairPhoto(photoFile))) {
                photoInput.value = '';
                showMessage('Sistem foto harus berformat PNG atau JPG.', true);
            }
        });

        async function updateRepairStatus(id) {
            const photoInput = document.getElementById(`repairPhoto_${id}`);
            const saveButton = document.getElementById(`saveButton_${id}`);
            if (!photoInput.files.length) {
                showMessage('Pilih foto bukti terlebih dahulu sebelum menyimpan status.', true);
                photoInput.focus();
                return;
            }

            const photoFile = photoInput.files[0];
            if (!(await isAllowedRepairPhoto(photoFile))) {
                photoInput.value = '';
                showMessage('Sistem foto harus berformat PNG atau JPG.', true);
                photoInput.focus();
                return;
            }

            const formData = new FormData();
            formData.append('_method', 'PATCH');
            formData.append('status_repair', document.getElementById(`repairStatus_${id}`).value);
            formData.append('photo', photoFile);
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
