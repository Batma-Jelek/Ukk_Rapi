<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'phone_number',
        'role_id',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi ke JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data bawaan yang dicast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // -----------------------------------------------------------------
    // RELASI ELOQUENT
    // -----------------------------------------------------------------

    // Relasi: User memiliki satu Role (Waka/Admin, PIC Ruang, Guru)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi: User (PIC) dapat mengelola banyak Ruangan
    public function managedRooms()
    {
        return $this->hasMany(Room::class, 'pic_id');
    }

    // Relasi: User dapat membuat banyak Pengajuan Agenda
    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    // Relasi: User dapat membuat banyak Pengaduan Kerusakan
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'reporter_id');
    }

    // Relasi: User dapat menerima banyak Notifikasi
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}