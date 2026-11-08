<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'reporter_id',
        'description',
        'photo_path',
        'repair_photo_path',
        'status_repair',
        'handler_id',
        'reported_at',
    ];

    // Relasi ke Ruangan
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Relasi ke Pelapor (Guru)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Alias relasi 'user' agar aman dipanggil jika controller/frontend memanggil ->user
    public function user()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relasi ke Penangan (PIC)
    public function handler()
    {
        return $this->belongsTo(User::class, 'handler_id');
    }
}