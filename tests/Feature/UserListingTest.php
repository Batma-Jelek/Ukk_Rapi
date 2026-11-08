<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('admin can list accounts filtered by role', function () {
    $adminRole = Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $organizationRole = Role::create(['role_name' => 'Organisasi Siswa']);

    $admin = User::create([
        'username' => 'admin',
        'password' => 'password123',
        'fullname' => 'Admin',
        'phone_number' => '0811111111',
        'role_id' => $adminRole->id,
    ]);

    User::create([
        'username' => 'teacher1',
        'password' => 'password123',
        'fullname' => 'Guru Satu',
        'phone_number' => '0812222222',
        'role_id' => $teacherRole->id,
    ]);

    User::create([
        'username' => 'organization1',
        'password' => 'password123',
        'fullname' => 'Organisasi Satu',
        'phone_number' => '0813333333',
        'role_id' => $organizationRole->id,
    ]);

    Sanctum::actingAs($admin);

    $this->getJson('/api/users?role_id='.$teacherRole->id)
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.username', 'teacher1')
        ->assertJsonPath('data.0.role.role_name', 'Guru')
        ->assertJsonMissingPath('data.0.password');
});

test('only admins can list accounts', function () {
    Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $teacher = User::create([
        'username' => 'teacher1',
        'password' => 'password123',
        'fullname' => 'Guru Satu',
        'phone_number' => '0812222222',
        'role_id' => $teacherRole->id,
    ]);

    Sanctum::actingAs($teacher);

    $this->getJson('/api/users')->assertForbidden();
});

test('account listing requires authentication', function () {
    $this->getJson('/api/users')->assertUnauthorized();
});
