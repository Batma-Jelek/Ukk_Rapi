<?php

use App\Models\Complaint;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('waka can get the room PIC phone number for repair alerts', function () {
    $wakaRole = Role::create(['role_name' => 'Waka']);
    $picRole = Role::create(['role_name' => 'PIC Ruang']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $waka = User::create([
        'username' => 'waka',
        'password' => 'password',
        'fullname' => 'Waka',
        'phone_number' => '0811111111',
        'role_id' => $wakaRole->id,
    ]);

    $pic = User::create([
        'username' => 'pic_lab',
        'password' => 'password',
        'fullname' => 'PIC Lab',
        'phone_number' => '088239057040',
        'role_id' => $picRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $room = Room::create([
        'room_name' => 'Lab Komputer',
        'capacity' => 30,
        'location' => 'Lantai 1',
        'pic_id' => $pic->id,
    ]);

    Complaint::create([
        'room_id' => $room->id,
        'reporter_id' => $teacher->id,
        'description' => 'Komputer tidak menyala',
        'photo_path' => 'complaints/test.jpg',
        'status_repair' => 'Belum Diperbaiki',
    ]);

    Sanctum::actingAs($waka);

    $response = $this->getJson('/api/complaints');

    $response->assertOk()
        ->assertJsonPath('data.0.room.pic.fullname', 'PIC Lab')
        ->assertJsonPath('data.0.room.pic.phone_number', '088239057040');
});

test('guru complaint listing does not expose the room PIC phone number', function () {
    $picRole = Role::create(['role_name' => 'PIC Ruang']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $pic = User::create([
        'username' => 'pic_lab',
        'password' => 'password',
        'fullname' => 'PIC Lab',
        'phone_number' => '088239057040',
        'role_id' => $picRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $room = Room::create([
        'room_name' => 'Lab Komputer',
        'capacity' => 30,
        'location' => 'Lantai 1',
        'pic_id' => $pic->id,
    ]);

    Complaint::create([
        'room_id' => $room->id,
        'reporter_id' => $teacher->id,
        'description' => 'Komputer tidak menyala',
        'photo_path' => 'complaints/test.jpg',
        'status_repair' => 'Belum Diperbaiki',
    ]);

    Sanctum::actingAs($teacher);

    $response = $this->getJson('/api/complaints');

    $response->assertOk()
        ->assertJsonMissingPath('data.0.room.pic');
});

test('pic must upload repair evidence to update status and waka can see the evidence', function () {
    $wakaRole = Role::create(['role_name' => 'Waka']);
    $picRole = Role::create(['role_name' => 'PIC Ruang']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $waka = User::create([
        'username' => 'waka',
        'password' => 'password',
        'fullname' => 'Waka',
        'phone_number' => '0811111111',
        'role_id' => $wakaRole->id,
    ]);

    $pic = User::create([
        'username' => 'pic_lab',
        'password' => 'password',
        'fullname' => 'PIC Lab',
        'phone_number' => '088239057040',
        'role_id' => $picRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $room = Room::create([
        'room_name' => 'Lab Komputer',
        'capacity' => 30,
        'location' => 'Lantai 1',
        'pic_id' => $pic->id,
    ]);

    $complaint = Complaint::create([
        'room_id' => $room->id,
        'reporter_id' => $teacher->id,
        'description' => 'Komputer tidak menyala',
        'photo_path' => 'complaints/test.jpg',
        'status_repair' => 'Belum Diperbaiki',
    ]);

    Sanctum::actingAs($pic);

    $this->patchJson("/api/complaints/{$complaint->id}/status", [
        'status_repair' => 'Menunggu Spare Part',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('photo');

    Storage::fake('public');
    $updateResponse = $this->post("/api/complaints/{$complaint->id}/status", [
        '_method' => 'PATCH',
        'status_repair' => 'Menunggu Spare Part',
        'photo' => UploadedFile::fake()->image('bukti-perbaikan.jpg'),
    ], ['Accept' => 'application/json']);

    $updateResponse->assertOk()
        ->assertJsonPath('data.status_repair', 'Menunggu Spare Part')
        ->assertJsonStructure(['data' => ['repair_photo_path']]);

    $repairPhotoPath = $updateResponse->json('data.repair_photo_path');
    expect($repairPhotoPath)->toStartWith('complaint-repairs/');
    Storage::disk('public')->assertExists($repairPhotoPath);

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'status_repair' => 'Menunggu Spare Part',
        'handler_id' => $pic->id,
        'repair_photo_path' => $repairPhotoPath,
    ]);

    Sanctum::actingAs($waka);

    $this->getJson('/api/complaints')
        ->assertOk()
        ->assertJsonPath('data.0.status_repair', 'Menunggu Spare Part')
        ->assertJsonPath('data.0.repair_photo_path', $repairPhotoPath);
});

test('pic cannot update a complaint for a room they do not manage', function () {
    $picRole = Role::create(['role_name' => 'PIC Ruang']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $pic = User::create([
        'username' => 'pic_lab',
        'password' => 'password',
        'fullname' => 'PIC Lab',
        'phone_number' => '088239057040',
        'role_id' => $picRole->id,
    ]);

    $otherPic = User::create([
        'username' => 'other_pic',
        'password' => 'password',
        'fullname' => 'PIC Ruang Lain',
        'phone_number' => '088239057041',
        'role_id' => $picRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $room = Room::create([
        'room_name' => 'Lab Komputer',
        'capacity' => 30,
        'location' => 'Lantai 1',
        'pic_id' => $otherPic->id,
    ]);

    $complaint = Complaint::create([
        'room_id' => $room->id,
        'reporter_id' => $teacher->id,
        'description' => 'Komputer tidak menyala',
        'photo_path' => 'complaints/test.jpg',
        'status_repair' => 'Belum Diperbaiki',
    ]);

    Sanctum::actingAs($pic);

    $this->patchJson("/api/complaints/{$complaint->id}/status", [
        'status_repair' => 'Menunggu Spare Part',
    ])->assertForbidden();

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'status_repair' => 'Belum Diperbaiki',
    ]);
});

test('pic cannot set an unsupported repair status', function () {
    $picRole = Role::create(['role_name' => 'PIC Ruang']);
    $teacherRole = Role::create(['role_name' => 'Guru']);

    $pic = User::create([
        'username' => 'pic_lab',
        'password' => 'password',
        'fullname' => 'PIC Lab',
        'phone_number' => '088239057040',
        'role_id' => $picRole->id,
    ]);

    $teacher = User::create([
        'username' => 'teacher',
        'password' => 'password',
        'fullname' => 'Guru',
        'phone_number' => '0822222222',
        'role_id' => $teacherRole->id,
    ]);

    $room = Room::create([
        'room_name' => 'Lab Komputer',
        'capacity' => 30,
        'location' => 'Lantai 1',
        'pic_id' => $pic->id,
    ]);

    $complaint = Complaint::create([
        'room_id' => $room->id,
        'reporter_id' => $teacher->id,
        'description' => 'Komputer tidak menyala',
        'photo_path' => 'complaints/test.jpg',
        'status_repair' => 'Belum Diperbaiki',
    ]);

    Sanctum::actingAs($pic);

    $this->patchJson("/api/complaints/{$complaint->id}/status", [
        'status_repair' => 'Status Tidak Valid',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('status_repair');

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'status_repair' => 'Belum Diperbaiki',
    ]);
});
