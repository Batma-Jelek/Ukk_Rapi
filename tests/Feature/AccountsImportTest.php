<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('admin can import accounts from a csv file', function () {
    $adminRole = Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $admin = User::create([
        'username' => 'admin',
        'password' => 'password123',
        'fullname' => 'Admin',
        'phone_number' => '0811111111',
        'role_id' => $adminRole->getKey(),
    ]);

    Sanctum::actingAs($admin);

    $file = UploadedFile::fake()->createWithContent(
        'accounts.csv',
        "fullname,username,password,role_id,phone\nSiti,teacher1,password123,{$teacherRole->getKey()},081234567890\n",
    );

    $response = $this->post('/api/users/import', ['file' => $file], ['Accept' => 'application/json']);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('imported_count', 1);

    $importedUser = User::where('username', 'teacher1')->firstOrFail();

    expect(Hash::check('password123', $importedUser->password))->toBeTrue();
    $this->assertDatabaseHas('users', [
        'username' => 'teacher1',
        'fullname' => 'Siti',
        'phone_number' => '081234567890',
        'role_id' => $teacherRole->getKey(),
    ]);
});

test('account import requires authentication', function () {
    $this->postJson('/api/users/import')
        ->assertUnauthorized();
});

test('only admins can import accounts', function () {
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $teacher = User::create([
        'username' => 'teacher1',
        'password' => 'password123',
        'fullname' => 'Guru',
        'phone_number' => '0811111111',
        'role_id' => $teacherRole->getKey(),
    ]);

    Sanctum::actingAs($teacher);

    $this->postJson('/api/users/import')
        ->assertForbidden();
});

test('invalid rows prevent all accounts in the file from being imported', function () {
    $adminRole = Role::create(['role_name' => 'Waka']);
    $teacherRole = Role::create(['role_name' => 'Guru']);
    $admin = User::create([
        'username' => 'admin',
        'password' => 'password123',
        'fullname' => 'Admin',
        'phone_number' => '0811111111',
        'role_id' => $adminRole->getKey(),
    ]);

    Sanctum::actingAs($admin);

    $file = UploadedFile::fake()->createWithContent(
        'accounts.csv',
        "fullname,username,password,role_id,phone\nSiti,teacher1,password123,{$teacherRole->getKey()},081234567890\nBudi,,password123,{$teacherRole->getKey()},081234567891\n",
    );

    $response = $this->post('/api/users/import', ['file' => $file], ['Accept' => 'application/json']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors('file');

    $this->assertDatabaseMissing('users', ['username' => 'teacher1']);
});
