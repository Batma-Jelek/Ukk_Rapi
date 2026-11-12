# RAPI Sekolah

RAPI Sekolah adalah aplikasi web untuk mengelola peminjaman ruangan dan pelaporan kerusakan fasilitas sekolah. Aplikasi menyediakan kalender agenda, alur persetujuan, pengelolaan akun dan ruangan, serta pemantauan perbaikan berdasarkan peran pengguna.

## Fitur

- Kalender publik untuk melihat agenda ruangan yang telah disetujui, dengan filter ruangan dan tanggal.
- Pengajuan agenda dan peminjaman ruangan oleh Guru dan Organisasi Siswa.
- Persetujuan, penolakan, dan pengaturan ulang ruangan agenda oleh Waka/Admin.
- Pelaporan kerusakan ruangan dengan foto bukti.
- Pemantauan laporan dan pembaruan status perbaikan oleh PIC ruangan; tersedia foto setelah perbaikan.
- Pengelolaan ruangan, akun pengguna, dan impor akun melalui Excel atau CSV oleh Waka/Admin.
- Pengajuan lupa sandi oleh pengguna dan verifikasi oleh Waka/Admin.
- Laporan peminjaman dan kerusakan untuk Waka/Admin.

## Peran pengguna

| Peran | Akses utama |
| --- | --- |
| Waka/Admin (`role_id` 1) | Mengelola ruangan dan akun, mengimpor akun, mengatur agenda, memantau laporan kerusakan, serta memverifikasi pengajuan lupa sandi. |
| PIC Ruang (`role_id` 2) | Melihat laporan kerusakan pada ruangan yang menjadi tanggung jawabnya dan memperbarui status perbaikan. |
| Guru (`role_id` 3) | Mengajukan agenda ruangan dan melaporkan kerusakan. |
| Organisasi Siswa (`role_id` 4) | Mengajukan agenda ruangan dan melaporkan kerusakan. |

## Teknologi

- PHP `^8.3` dan Laravel `^13.17`
- Laravel Sanctum untuk autentikasi API
- Laravel Excel `^4.0` dan PhpSpreadsheet untuk impor akun
- SQLite sebagai konfigurasi database bawaan; MySQL juga dapat digunakan
- Vite, Tailwind CSS, dan JavaScript untuk aset frontend

## Persyaratan

- PHP 8.3 atau lebih baru beserta ekstensi yang dibutuhkan Laravel dan PhpSpreadsheet
- Composer
- Node.js dan npm
- Database SQLite atau MySQL

Untuk unggah foto dan impor Excel, PHP harus dapat menulis ke direktori temporary yang digunakan proses web. Jika muncul peringatan seperti `Unable to create temporary file`, atur `upload_tmp_dir` dan/atau `sys_temp_dir` pada konfigurasi PHP ke direktori yang ada dan dapat ditulis, kemudian restart PHP/web server.

## Instalasi lokal

Jalankan perintah berikut dari direktori proyek. Contoh perintah file berikut menggunakan PowerShell.

1. Pasang dependensi PHP dan siapkan file environment:

   ```powershell
   composer install
   Copy-Item .env.example .env
   php artisan key:generate
   ```

   Jika `.env` sudah tersedia, jangan menimpanya; cukup pastikan `APP_KEY` sudah dibuat.

2. Siapkan database.

   Konfigurasi bawaan memakai SQLite. Buat file database jika belum ada:

   ```powershell
   if (-not (Test-Path database\database.sqlite)) {
       New-Item -ItemType File -Path database\database.sqlite
   }
   ```

   Untuk MySQL, buat database terlebih dahulu lalu atur `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`.

3. Jalankan migrasi dan data contoh:

   ```powershell
   php artisan migrate --seed
   ```

4. Pasang dependensi frontend dan buat aset:

   ```powershell
   npm install
   npm run build
   ```

5. Siapkan akses file publik dan jalankan aplikasi:

   ```powershell
   php artisan storage:link
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000`.

## Akun contoh lokal

Seeder membuat akun berikut untuk pengembangan lokal:

| Peran | Username | Password |
| --- | --- | --- |
| Waka/Admin | `admin` | `admin123` |
| PIC Ruang | `pic_lab` | `password123` |
| Guru | `guru_osis` | `guru123` |
| Organisasi Siswa | `organisasi_siswa` | `organisasi123` |

Akun ini hanya untuk penggunaan lokal setelah menjalankan seeder. Ganti atau hapus kredensial contoh sebelum aplikasi digunakan di lingkungan lain.

## Format impor akun

Waka/Admin dapat mengimpor berkas `.xlsx`, `.xls`, atau `.csv` melalui dashboard. Baris pertama harus berisi header berikut:

```text
fullname,username,password,role_id,phone
```

Nilai `role_id`: `1` untuk Waka, `2` untuk PIC Ruang, `3` untuk Guru, dan `4` untuk Organisasi Siswa. `fullname`, `username`, `password`, dan `role_id` wajib diisi; password minimal 6 karakter. `username` harus unik. Kolom `phone` boleh kosong. Ukuran berkas maksimal 10 MB.

## Halaman aplikasi

| URL | Halaman |
| --- | --- |
| `/` | Kalender agenda publik |
| `/login` | Login |
| `/calendar-view` | Kalender agenda publik |
| `/admin/dashboard` | Dashboard Waka/Admin |
| `/pic/dashboard` | Dashboard PIC Ruang |
| `/guru/dashboard` | Dashboard Guru |
| `/organisasi/dashboard` | Dashboard Organisasi Siswa |

Data aplikasi dilayani melalui endpoint API di bawah `/api`. Endpoint selain login, daftar ruangan, kalender publik, dan pengajuan lupa sandi memerlukan autentikasi Sanctum.

## Pengembangan dan pengujian

Jalankan server pengembangan Laravel dan Vite secara bersamaan:

```powershell
composer run dev
```

Jalankan pengujian:

```powershell
php artisan test
```

## Deployment

- Atur `.env` untuk lingkungan tujuan, termasuk `APP_ENV=production`, `APP_DEBUG=false`, URL aplikasi, dan koneksi database.
- Pasang dependensi produksi menggunakan `composer install --no-dev --optimize-autoloader`.
- Jalankan `php artisan migrate --force` setelah database siap.
- Buat aset frontend dengan `npm ci` dan `npm run build`.
- Pastikan `storage` dan `bootstrap/cache` dapat ditulis oleh proses aplikasi, buat storage link, serta pastikan PHP memiliki direktori temporary yang dapat ditulis.
- Jangan gunakan akun contoh seeder di production.
