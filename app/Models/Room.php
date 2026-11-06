<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'capacity',
        'location',
        'pic_id',
    ];

    // Relasi: Ruangan ditangani oleh satu User sebagai PIC
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    // Relasi: Satu Ruangan dapat memiliki banyak Agenda/Event
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Relasi: Satu Ruangan dapat memiliki banyak Pengaduan/Complaint
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}