<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Pemakaian Ruangan - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="public-calendar">

<header class="calendar-header">
    <a class="calendar-brand" href="/" aria-label="RAPI-Sekolah - Beranda">
        <span class="calendar-brand-mark" aria-hidden="true">R</span>
        <span>
            <strong>RAPI-Sekolah</strong>
            <small>Kalender pemakaian ruangan</small>
        </span>
    </a>
    <a href="/login" class="btn btn-primary calendar-login">Login Petugas / Guru</a>
</header>

<main class="calendar-main">
    <section class="calendar-hero">
        <div class="calendar-hero-copy">
            <span class="calendar-eyebrow">JADWAL RUANGAN SEKOLAH</span>
            <h1>Semua agenda, lebih mudah ditemukan.</h1>
            <p>Lihat jadwal penggunaan ruangan yang telah disetujui. Gunakan filter untuk menemukan agenda berdasarkan ruangan atau tanggal.</p>
        </div>
        <div class="calendar-hero-art" aria-hidden="true">
            <span class="calendar-art-back"></span>
            <span class="calendar-art-front">
                <span class="calendar-art-top"></span>
                <span class="calendar-art-grid"><i></i><i></i><i></i><i></i><i></i><i></i></span>
            </span>
        </div>
    </section>

    <section class="calendar-overview" aria-label="Ringkasan kalender">
        <article class="calendar-stat">
            <span class="calendar-stat-icon calendar-stat-blue" aria-hidden="true">▦</span>
            <span><strong id="agendaCount">0</strong><small>Agenda ditampilkan</small></span>
        </article>
        <article class="calendar-stat">
            <span class="calendar-stat-icon calendar-stat-green" aria-hidden="true">⌂</span>
            <span><strong id="roomCount">0</strong><small>Ruangan digunakan</small></span>
        </article>
        <article class="calendar-stat calendar-stat-filter">
            <span class="calendar-stat-icon calendar-stat-purple" aria-hidden="true">◷</span>
            <span><strong id="calendarFilterLabel">Semua tanggal</strong><small>Rentang tampilan</small></span>
        </article>
    </section>

    <section class="calendar-filter card" aria-labelledby="calendarFilterTitle">
        <div class="calendar-section-heading">
            <div>
                <span class="calendar-eyebrow">PENCARIAN JADWAL</span>
                <h2 id="calendarFilterTitle">Cari Agenda Yang Tersedia</h2>
            </div>
            <button type="button" id="resetCalendarFilters" class="calendar-reset-button">Reset filter</button>
        </div>
        <div class="calendar-filter-fields">
            <div class="form-group">
                <label for="filterSearch">Cari nama agenda atau penyelenggara</label>
                <input type="search" id="filterSearch" class="form-control" placeholder="Contoh: rapat atau nama guru" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="filterRoom">Ruangan</label>
                <select id="filterRoom" class="form-control">
                    <option value="">Semua ruangan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="filterDate">Tanggal agenda</label>
                <input type="date" id="filterDate" class="form-control">
            </div>
        </div>
    </section>

    <section class="calendar-schedule" aria-labelledby="calendarScheduleTitle">
        <div class="calendar-section-heading calendar-schedule-heading">
            <div>
                <span class="calendar-eyebrow">KALENDER PUBLIK</span>
                <h2 id="calendarScheduleTitle">Agenda disetujui</h2>
            </div>
            <span id="calendarResultsLabel" class="calendar-results-label">Memuat agenda...</span>
        </div>
        <div id="calendarGrid" class="calendar-grid" aria-live="polite">
            <div class="calendar-state-card"><span class="calendar-loading-dot"></span> Memuat jadwal kalender...</div>
        </div>
    </section>
</main>

<script>
let allEvents = [];

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, character => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
})[character]);

function formatCalendarDate(dateString, options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }) {
    const [year, month, day] = String(dateString || '').slice(0, 10).split('-').map(Number);
    if (!year || !month || !day) return 'Tanggal belum tersedia';
    return new Intl.DateTimeFormat('id-ID', options).format(new Date(year, month - 1, day));
}

function renderCalendar() {
    const searchTerm = document.getElementById('filterSearch').value.trim().toLocaleLowerCase('id-ID');
    const selectedRoom = document.getElementById('filterRoom').value;
    const selectedDate = document.getElementById('filterDate').value;
    const container = document.getElementById('calendarGrid');

    const filtered = allEvents
        .filter(item => {
            const searchableText = `${item.event_name || ''} ${item.user?.fullname || ''} ${item.room?.room_name || ''}`.toLocaleLowerCase('id-ID');
            const matchSearch = !searchTerm || searchableText.includes(searchTerm);
            const matchRoom = selectedRoom === '' || String(item.room_id) === selectedRoom;
            const matchDate = selectedDate === '' || String(item.date).slice(0, 10) === selectedDate;
            return matchSearch && matchRoom && matchDate;
        })
        .sort((a, b) => `${a.date || ''} ${a.start_time || ''}`.localeCompare(`${b.date || ''} ${b.start_time || ''}`));

    const rooms = new Set(filtered.map(item => item.room?.room_name || item.room_id).filter(Boolean));
    document.getElementById('agendaCount').textContent = filtered.length;
    document.getElementById('roomCount').textContent = rooms.size;
    document.getElementById('calendarFilterLabel').textContent = selectedDate
        ? formatCalendarDate(selectedDate, { day: 'numeric', month: 'short', year: 'numeric' })
        : 'Semua tanggal';
    document.getElementById('calendarResultsLabel').textContent = `${filtered.length} agenda ditampilkan`;

    if (filtered.length === 0) {
        container.innerHTML = `
            <div class="calendar-state-card calendar-empty-state">
                <span class="calendar-empty-icon" aria-hidden="true">⌕</span>
                <h3>Belum ada agenda yang cocok</h3>
                <p>Coba pilih ruangan lain atau hapus filter tanggal untuk melihat jadwal lainnya.</p>
                <button type="button" class="calendar-empty-reset" onclick="resetCalendarFilters()">Tampilkan semua agenda</button>
            </div>`;
        return;
    }

    container.innerHTML = filtered.map(item => {
        const roomName = item.room?.room_name || `Ruang ${item.room_id}`;
        const startTime = String(item.start_time || '').slice(0, 5) || '--:--';
        const endTime = String(item.end_time || '').slice(0, 5) || '--:--';
        const organizer = item.user?.fullname || 'Guru / Organisasi';
        return `
            <article class="calendar-event-card">
                <div class="calendar-event-accent"></div>
                <div class="calendar-event-content">
                    <div class="calendar-event-topline">
                        <span class="calendar-room-badge"><span aria-hidden="true">⌂</span>${escapeHtml(roomName)}</span>
                        <span class="calendar-event-date">${escapeHtml(formatCalendarDate(item.date, { day: 'numeric', month: 'short', year: 'numeric' }))}</span>
                    </div>
                    <h3>${escapeHtml(item.event_name || 'Agenda tanpa judul')}</h3>
                    <div class="calendar-event-time"><span aria-hidden="true">◷</span>${escapeHtml(startTime)} – ${escapeHtml(endTime)} WIB</div>
                    <div class="calendar-event-footer">
                        <span class="calendar-organizer-avatar" aria-hidden="true">${escapeHtml(organizer.trim().charAt(0).toUpperCase())}</span>
                        <span><small>Penyelenggara</small><strong>${escapeHtml(organizer)}</strong></span>
                    </div>
                </div>
            </article>`;
    }).join('');
}

function resetCalendarFilters() {
    document.getElementById('filterSearch').value = '';
    document.getElementById('filterRoom').value = '';
    document.getElementById('filterDate').value = '';
    renderCalendar();
}

async function initCalendar() {
    const container = document.getElementById('calendarGrid');
    try {
        const [roomRes, eventRes] = await Promise.all([
            fetch('/api/rooms'),
            fetch('/api/calendar')
        ]);
        if (!roomRes.ok || !eventRes.ok) {
            throw new Error('Data kalender belum dapat dimuat. Silakan coba lagi.');
        }

        const [roomData, eventData] = await Promise.all([roomRes.json(), eventRes.json()]);
        const roomSelect = document.getElementById('filterRoom');
        roomSelect.replaceChildren(new Option('Semua ruangan', ''));
        (roomData.data || []).forEach(room => {
            const option = document.createElement('option');
            option.value = room.id;
            option.textContent = room.room_name;
            roomSelect.appendChild(option);
        });

        allEvents = eventData.data || [];
        renderCalendar();
    } catch (error) {
        console.error('Gagal memuat kalender:', error);
        document.getElementById('calendarResultsLabel').textContent = 'Kalender tidak tersedia';
        container.innerHTML = `
            <div class="calendar-state-card calendar-empty-state">
                <span class="calendar-empty-icon" aria-hidden="true">!</span>
                <h3>Jadwal belum dapat dimuat</h3>
                <p>${escapeHtml(error.message || 'Periksa koneksi Anda, lalu coba lagi.')}</p>
                <button type="button" class="calendar-empty-reset" onclick="initCalendar()">Coba lagi</button>
            </div>`;
    }
}

document.getElementById('filterRoom').addEventListener('change', renderCalendar);
document.getElementById('filterDate').addEventListener('change', renderCalendar);
document.getElementById('filterSearch').addEventListener('input', renderCalendar);
document.getElementById('resetCalendarFilters').addEventListener('click', resetCalendarFilters);

initCalendar();
</script>

</body>
</html>