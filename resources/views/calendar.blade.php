<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Pemakaian Ruangan - RAPI Sekolah</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header class="top-navbar" style="display: flex; justify-content: space-between; align-items: center; background: var(--secondary); color: white;">
    <div>
        <h2>RAPI-Sekolah</h2>
        <small style="color: #94a3b8;">Jadwal Penggunaan Ruangan (Siswa & Publik)</small>
    </div>
    <a href="/login" class="btn btn-primary" style="text-decoration: none; width: auto; padding: 6px 16px;">Login Petugas / Guru</a>
</header>

<main class="content-area" style="max-width: 1100px; margin: 0 auto;">
    <!-- Filter Ruangan & Tanggal -->
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
</main>

<script>
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
                    🕒 <b>${item.start_time.substring(0,5)} - ${item.end_time.substring(0,5)} WIB</b>
                </p>
                <p style="font-size:12px; color:var(--text-main); border-top:1px solid var(--border-color); padding-top:8px;">
                    Penyelenggara: <b>${item.user ? item.user.fullname : 'Guru/Organisasi'}</b>
                </p>
            </div>
        `;
    });
}

initCalendar();
</script>

</body>
</html>