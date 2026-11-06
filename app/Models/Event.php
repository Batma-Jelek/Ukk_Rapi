<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'event_name',
        'date',
        'start_time',
        'end_time',
        'status',
        'approved_by',
        'move_reason',
        'reassignment_reason',
        'moved_by_user_id',
    ];

    // Relasi: Event dilaksanakan di satu Ruangan
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi: Event diajukan oleh satu User (Pemohon)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Event disetujui oleh satu User (Admin/Waka)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}