<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Room; // <-- TAMBAHKAN INI
use App\Services\ScheduleValidationService;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Menampilkan semua daftar pengajuan agenda (Waka / Admin)
     */
    public function index()
    {
        $events = Event::with(['room', 'user'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ], 200);
    }

    /**
     * Agenda yang disetujui untuk Kalender Publik
     */
    public function getCalendarEvents()
    {
        $events = Event::with(['room', 'user'])
            ->whereIn('status', ['approved', 'Approved', 'moved', 'Moved'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ], 200);
    }

    /**
     * Pengajuan Agenda Baru oleh Guru / Waka
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'event_name' => 'required|string|max:200',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Cek Bentrok menggunakan Service
        if (ScheduleValidationService::hasConflict($request->room_id, $request->date, $request->start_time, $request->end_time)) {
            return response()->json([
                'message' => 'Jadwal pada ruangan dan waktu tersebut sudah terpakai!'
            ], 422);
        }

        // Auto-Approve jika pembuatnya Waka/Admin (role_id 1)
        $user = auth()->user();
        $status = ($user && $user->role_id == 1) ? 'approved' : 'pending';

        $event = Event::create([
            'user_id' => $user->id,
            'room_id' => $request->room_id,
            'event_name' => $request->event_name,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $status,
        ]);

        return response()->json([
            'message' => ($status === 'approved')
                ? 'Peminjaman Waka berhasil dibuat dan langsung masuk agenda!'
                : 'Pengajuan agenda berhasil dikirim!',
            'data' => $event
        ], 201);
    }

    /**
     * Approve Agenda
     */
    public function approve($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Pengajuan agenda berhasil disetujui.'
        ], 200);
    }

    /**
     * Reject Agenda
     */
    public function reject($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Pengajuan agenda telah ditolak.'
        ], 200);
    }

    /**
     * Hak Veto Waka / Admin: Memindahkan Ruangan Peminjaman Sepihak
     */
    public function reassignRoom(Request $request, $id)
    {
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
            'reassignment_reason' => 'required|string|min:5',
        ]);

        // 1. Ambil data event beserta user pengaju dan room saat ini
        $event = Event::with(['user', 'room'])->findOrFail($id);
        $oldRoomName = $event->room ? $event->room->room_name : 'Ruangan Lama';

        $newRoom = Room::findOrFail($request->new_room_id);
        $reason = $request->input('reassignment_reason');

        DB::transaction(function () use ($event, $newRoom, $reason, $oldRoomName) {
            $event->update([
                'room_id' => $newRoom->id,
                'reassignment_reason' => $reason,
                'moved_by_user_id' => auth()->id(),
                'status' => 'approved',
            ]);

            if ($event->user) {
                $event->user->notifications()->create([
                    'title' => 'Ruangan Dipindahkan oleh Waka',
                    'message' => "Kegiatan '{$event->event_name}' dipindahkan dari {$oldRoomName} ke {$newRoom->room_name}. Alasan: {$reason}",
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil dipindahkan sepihak dan notifikasi telah dikirim.',
            'data' => $event->load('room')
        ], 200);
    }
}
