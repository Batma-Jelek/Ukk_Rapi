<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan beserta PIC
     */
    public function index()
    {
        // Panggil relasi 'pic' tanpa membatasi kolom agar tidak terjadi SQL Error
        $rooms = Room::with('pic')->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $rooms
        ], 200);
    }

    /**
     * Menyimpan Ruangan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:100',
            'capacity'  => 'required|integer|min:1',
            'location'  => 'required|string',
            'pic_id'    => 'nullable|exists:users,id',
        ]);

        $room = Room::create([
            'room_name' => $request->room_name,
            'capacity'  => $request->capacity,
            'location'  => $request->location,
            'pic_id'    => $request->pic_id,
        ]);

        // Load relasi pic agar data respon membawa info PIC
        $room->load('pic');

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil ditambahkan.',
            'data'    => $room
        ], 201);
    }

    /**
     * Perbarui Data Ruangan
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_name' => 'sometimes|string|max:100',
            'capacity'  => 'sometimes|integer|min:1',
            'location'  => 'sometimes|string',
            'pic_id'    => 'nullable|exists:users,id',
        ]);

        $room->update($request->all());
        $room->load('pic');

        return response()->json([
            'success' => true,
            'message' => 'Data ruangan & PIC berhasil diperbarui.',
            'data'    => $room
        ], 200);
    }

    /**
     * Hapus Ruangan
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil dihapus.'
        ], 200);
    }
}