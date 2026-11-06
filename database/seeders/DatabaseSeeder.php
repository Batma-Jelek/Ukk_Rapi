<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = Role::create(['role_name' => 'Waka']);
        $picRole = Role::create(['role_name' => 'PIC Ruang']);
        $guruRole = Role::create(['role_name' => 'Guru']);
        $organisasiRole = Role::create(['role_name' => 'Organisasi Siswa']);


        // 2. Seed Admin Default
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'fullname' => 'Windu',
            'phone_number' => '081234567890',
            'role_id' => $adminRole->id,
        ]);

        // 3. Seed Sample PIC Ruang
        User::create([
            'username' => 'pic_lab',
            'password' => Hash::make('password123'),
            'fullname' => 'Bu Lauhin (PIC Lab)',
            'phone_number' => '088239057040',
            'role_id' => $picRole->id,
        ]);

        // 4. Seed Sample Guru
        User::create([
            'username' => 'guru_osis',
            'password' => Hash::make('guru123'),
            'fullname' => 'Siti Nurhaliza (Pembina OSIS)',
            'phone_number' => '085123456789',
            'role_id' => $guruRole->id,
        ]);

         // 5. Seed Sample organisasi siswa
        User::create([
            'username' => 'organisasi_siswa',
            'password' => Hash::make('organisasi123'),
            'fullname' => 'Abid (Ketua PMR)',
            'phone_number' => '085987654321',
            'role_id' => $organisasiRole->id,
        ]);
    }
}
