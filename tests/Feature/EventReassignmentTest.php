<?php

use App\Models\Event;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('admin can move an approved event and notify its requester', function () {
    $adminRole = Role::create(['role_name' => 'Admin']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $admin = User::create([
        'username' => 'admin',
        'password' => 'password',
        'fullname' => 'Admin',
        'phone_number' => '0811111111',
        'role_id' => $adminRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $oldRoom = Room::create([
        'room_name' => 'Ruang Lama',
        'capacity' => 30,
        'location' => 'Lantai 1',
    ]);

    $newRoom = Room::create([
        'room_name' => 'Ruang Baru',
        'capacity' => 30,
        'location' => 'Lantai 2',
    ]);

    $event = Event::create([
        'room_id' => $oldRoom->id,
        'user_id' => $teacher->id,
        'event_name' => 'Rapat Guru',
        'date' => '2026-11-10',
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'status' => 'approved',
    ]);

    Sanctum::actingAs($admin);

    $response = $this->putJson("/api/events/{$event->id}/reassign", [
        'new_room_id' => $newRoom->id,
        'reassignment_reason' => 'Ruangan lama sedang diperbaiki',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'room_id' => $newRoom->id,
        'reassignment_reason' => 'Ruangan lama sedang diperbaiki',
        'moved_by_user_id' => $admin->id,
        'status' => 'approved',
    ]);

    $this->assertDatabaseHas('notifications', [
        'user_id' => $teacher->id,
        'title' => 'Ruangan Dipindahkan oleh Waka',
        'message' => "Acara 'Rapat Guru' dipindahkan ke Ruang Baru dari Ruang Lama. Alasan: Ruangan lama sedang diperbaiki",
    ]);

    Sanctum::actingAs($teacher);
    $notificationResponse = $this->getJson('/api/notifications');
    $notificationResponse->assertOk()
        ->assertJsonPath('data.0.message', "Acara 'Rapat Guru' dipindahkan ke Ruang Baru dari Ruang Lama. Alasan: Ruangan lama sedang diperbaiki");

    $notificationId = $notificationResponse->json('data.0.id');
    Sanctum::actingAs($admin);
    $this->patchJson("/api/notifications/{$notificationId}/read")->assertNotFound();

    Sanctum::actingAs($teacher);
    $this->patchJson("/api/notifications/{$notificationId}/read")->assertOk();
    $this->getJson('/api/notifications')->assertJsonCount(0, 'data');
});
