<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Login Utama & Kalender Publik
Route::get('/', function () {
    return view('calendar');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/reset-password', function () {
    return view('reset-password');
})->name('password.reset.form');
Route::get('/calendar-view', function () {
    return view('calendar');
});

// 2. Dashboard Web Admin / Waka Sarpras (Role ID: 1)
Route::get('/admin/dashboard', function () {
    return view('admin');
});

// 3. Dashboard Web PIC Ruang (Role ID: 2)
Route::get('/pic/dashboard', function () {
    return view('pic');
});

// 4. Dashboard Web Guru (Role ID: 3) & Organisasi (Role ID: 4)
Route::get('/guru/dashboard', function () {
    return view('guru');
});
Route::get('/organisasi/dashboard', function () {
    return view('organisasi');
});