<?php

use App\Models\PasswordResetRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('users from every role can submit a password reset request', function () {
    foreach (['Admin', 'PIC Ruang', 'Guru', 'Organisasi'] as $roleName) {
        $role = Role::create(['role_name' => $roleName]);
        $user = User::create([
            'username' => strtolower(str_replace(' ', '_', $roleName)),
            'password' => 'password123',
            'fullname' => $roleName.' Satu',
            'phone_number' => '08123456789',
            'role_id' => $role->id,
        ]);

        $this->postJson('/api/password-reset-requests', [
            'username' => $user->username,
            'fullname' => $user->fullname,
            'phone_number' => $user->phone_number,
        ])
            ->assertAccepted()
            ->assertJsonPath('success', true);
    }

    expect(PasswordResetRequest::count())->toBe(4);
});

test('password reset requests require matching account details and do not reveal whether an account exists', function () {
    $role = Role::create(['role_name' => 'Guru']);
    $user = User::create([
        'username' => 'guru1',
        'password' => 'password123',
        'fullname' => 'Guru Satu',
        'phone_number' => '08123456789',
        'role_id' => $role->id,
    ]);

    $this->postJson('/api/password-reset-requests', [
        'username' => $user->username,
        'fullname' => $user->fullname,
        'phone_number' => '08000000000',
    ])
        ->assertAccepted()
        ->assertJsonPath('success', true);

    expect(PasswordResetRequest::count())->toBe(0);
});

test('only admins can view or complete password reset requests', function () {
    $adminRole = Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $admin = User::create([
        'username' => 'admin',
        'password' => 'password123',
        'fullname' => 'Admin Sekolah',
        'phone_number' => '08111111111',
        'role_id' => $adminRole->id,
    ]);
    $teacher = User::create([
        'username' => 'guru1',
        'password' => 'password123',
        'fullname' => 'Guru Satu',
        'phone_number' => '08122222222',
        'role_id' => $teacherRole->id,
    ]);
    $resetRequest = PasswordResetRequest::create([
        'user_id' => $teacher->id,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($teacher);
    $this->getJson('/api/password-reset-requests')->assertForbidden();
    $this->patchJson('/api/password-reset-requests/'.$resetRequest->id.'/complete')->assertForbidden();

    Sanctum::actingAs($admin);
    $this->getJson('/api/password-reset-requests')
        ->assertOk()
        ->assertJsonPath('data.0.user.username', 'guru1');

    $this->patchJson('/api/password-reset-requests/'.$resetRequest->id.'/complete')
        ->assertOk()
        ->assertJsonPath('success', true);

    expect($resetRequest->refresh()->status)->toBe('completed');
});

test('updating an account password completes its pending reset requests', function () {
    $adminRole = Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $admin = User::create([
        'username' => 'admin',
        'password' => 'password123',
        'fullname' => 'Admin Sekolah',
        'phone_number' => '08111111111',
        'role_id' => $adminRole->id,
    ]);
    $teacher = User::create([
        'username' => 'guru1',
        'password' => 'password123',
        'fullname' => 'Guru Satu',
        'phone_number' => '08122222222',
        'role_id' => $teacherRole->id,
    ]);
    $resetRequest = PasswordResetRequest::create([
        'user_id' => $teacher->id,
        'status' => 'pending',
    ]);

    Sanctum::actingAs($admin);

    $this->putJson('/api/users/'.$teacher->id, [
        'fullname' => $teacher->fullname,
        'username' => $teacher->username,
        'phone_number' => $teacher->phone_number,
        'role_id' => $teacherRole->id,
        'password' => 'newpassword123',
    ])->assertOk();

    expect($resetRequest->refresh()->status)->toBe('completed')
        ->and(password_verify('newpassword123', $teacher->refresh()->password))->toBeTrue();
});
