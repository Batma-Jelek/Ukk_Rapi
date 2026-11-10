<?php

use App\Models\Event;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('booking accepts minute-specific time between adjacent events', function () {
    $role = Role::create(['role_name' => 'Guru']);
    $user = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '08123456789',
        'role_id' => $role->id,
    ]);
    $room = Room::create([
        'room_name' => 'Ruang 1',
        'capacity' => 30,
        'location' => 'Lantai 1',
    ]);

    Event::create([
        'room_id' => $room->id,
        'user_id' => $user->id,
        'event_name' => 'Agenda Pagi',
        'date' => '2026-11-10',
        'start_time' => '09:30:00',
        'end_time' => '10:30:00',
        'status' => 'approved',
    ]);
    Event::create([
        'room_id' => $room->id,
        'user_id' => $user->id,
        'event_name' => 'Agenda Berikutnya',
        'date' => '2026-11-10',
        'start_time' => '11:10:00',
        'end_time' => '12:10:00',
        'status' => 'approved',
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/events', [
        'room_id' => $room->id,
        'event_name' => 'Peminjaman 10:36 sampai 11:09',
        'date' => '2026-11-10',
        'start_time' => '10:36',
        'end_time' => '11:09',
    ])->assertCreated()
        ->assertJsonPath('data.start_time', '10:36')
        ->assertJsonPath('data.end_time', '11:09');

    $this->postJson('/api/events', [
        'room_id' => $room->id,
        'event_name' => 'Agenda Bentrok',
        'date' => '2026-11-10',
        'start_time' => '10:00',
        'end_time' => '10:40',
    ])->assertUnprocessable()
        ->assertJsonPath('message', 'Jadwal pada ruangan dan waktu tersebut sudah terpakai!');
});
