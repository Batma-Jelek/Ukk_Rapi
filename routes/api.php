<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\AccountsImportController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak Memerlukan Token)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/calendar', [EventController::class, 'getCalendarEvents']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Memerlukan Token Bearer / Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users/import', AccountsImportController::class);

    // Route Mengambil Daftar User (Dipakai untuk Dropdown PIC Ruangan - Role ID 2)
    Route::get('/users', function (\Illuminate\Http\Request $request) {
        $query = \App\Models\User::query();
        if ($request->has('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        return response()->json(['success' => true, 'data' => $query->get()]);
    });

    // Admin (Role 1): Kelola Ruangan
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

    // Guru (Role 3), Organisasi (Role 4) & Admin (Role 1): Pengajuan & Daftar Agenda
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);

    // Admin (Role 1): Persetujuan, Penolakan & Hak Veto Pindah Ruang Agenda
    Route::put('/events/{id}/approve', [EventController::class, 'approve']);
    Route::put('/events/{id}/reject', [EventController::class, 'reject']);
    Route::patch('/events/{id}/status', [EventController::class, 'updateStatus']);
    Route::put('/events/{id}/move', [EventController::class, 'moveRoom']);
    Route::put('/events/{id}/reassign', [EventController::class, 'reassignRoom']); // <--- RUTE HAK VETO BARU

    // Guru (Role 3), Organisasi (Role 4) & Admin (Role 1): Lapor Kerusakan
    Route::post('/complaints', [ComplaintController::class, 'store']);

    // Admin (Role 1) & PIC (Role 2): Kelola & Monitoring Kerusakan
    Route::get('/complaints', [ComplaintController::class, 'index']);
    Route::get('/pic/complaints', [ComplaintController::class, 'getPicComplaints']);
    Route::patch('/complaints/{id}/status', [ComplaintController::class, 'updateStatus']);

    // Admin (Role 1): Generator Teguran WA ke PIC
    Route::get('/complaints/{id}/wa-link', [ComplaintController::class, 'getWaLink']);
});