<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Endpoint: GET /api/complaints
     */
    public function index(Request $request)
    {
        return $this->getPicComplaints($request);
    }

    /**
     * Endpoint: GET /api/pic/complaints
     */
    public function getPicComplaints(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            if ($user->role_id === 1) {
                // Admin/Waka also needs the PIC contact for WhatsApp alerts.
                $complaints = Complaint::with(['room.pic', 'reporter', 'user'])->latest()->get();
            } else if ($user->role_id === 2) {
                // PIC Ruangan: Ambil laporan pada ruangan yang dipegang PIC
                $complaints = Complaint::whereHas('room', function ($query) use ($user) {
                    $query->where('pic_id', $user->id);
                })->with(['room', 'reporter', 'user'])->latest()->get();
            } else {
                // Guru: Ambil laporan yang dilaporkan oleh guru itu sendiri
                $complaints = Complaint::where('reporter_id', $user->id)
                    ->with(['room', 'reporter', 'user'])
                    ->latest()
                    ->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $complaints
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint: POST /api/complaints
     */
    public function store(Request $request)
    {
        try {
            // 1. Cek autentikasi user
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.'
                ], 401);
            }

            // 2. Validasi input
            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,id',
                'description' => 'required|string|min:3',
                'photo' => 'nullable|file|max:10240', // Maksimal 10MB
                'photo_base64' => 'nullable|string',   // Alternatif Base64
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 3. Olah file foto
            $photoPath = null;

            // A. Opsi Upload File Biasa / Multipart
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('complaints', 'public');
            }
           // B. Opsi Base64 (Webcam)
        else if ($request->filled('photo_base64')) {
            $base64Image = $request->photo_base64;

            // Ambil ekstensi gambar jika ada header data URI
            $extension = 'jpg';
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                $extension = strtolower($type[1]);
            }

            // Clean & Decode Base64 Data
            $base64Image = str_replace(' ', '+', $base64Image);
            $imageData = base64_decode($base64Image);

            if ($imageData !== false) {
                // Pastikan folder complaints dibuat jika belum ada
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('complaints')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('complaints');
                }

                $fileName = 'complaints/photo_' . time() . '_' . uniqid() . '.' . $extension;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageData);
                $photoPath = $fileName;
            }
        }

            // 4. Simpan data laporan ke Database
            $complaint = Complaint::create([
                'reporter_id' => $user->id,
                'room_id' => $request->room_id,
                'description' => $request->description,
                'photo_path' => $photoPath,
                'status_repair' => 'Belum Diperbaiki',
                'reported_at' => now(),
            ]);

            // 5. Load relasi pendukung
            $complaint->load(['room', 'reporter', 'user']);

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan kerusakan berhasil dikirim',
                'data' => $complaint
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Endpoint: PATCH /api/complaints/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        $complaint = Complaint::with('room')->findOrFail($id);

        if (!in_array($user->role_id, [1, 2], true)) {
            abort(403);
        }

        if ((int) $user->role_id === 2 && $complaint->room->pic_id !== $user->id) {
            abort(403);
        }

        $rules = [
            'status_repair' => 'required|string|in:Belum Diperbaiki,Menunggu Spare Part,Sudah Diperbaiki',
        ];

        if ((int) $user->role_id === 2) {
            $rules['photo'] = 'required|image|max:10240';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $updates = [
            'status_repair' => $request->status_repair,
            'handler_id' => $user->id,
        ];

        if ((int) $user->role_id === 2) {
            $photoPath = $request->file('photo')->store('complaint-repairs', 'public');
            if (!$photoPath) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Foto bukti gagal disimpan. Silakan coba lagi.',
                ], 500);
            }

            $updates['repair_photo_path'] = $photoPath;
        }

        $complaint->update($updates);

        return response()->json([
            'status' => 'success',
            'message' => 'Status perbaikan berhasil diperbarui',
            'data' => $complaint
        ], 200);
    }

    /**
     * Endpoint: GET /api/complaints/{id}/wa-link
     */
    public function getWaLink($id)
    {
        try {
            $complaint = Complaint::with(['room.pic', 'room.user'])->findOrFail($id);

            $pic = $complaint->room->pic ?? $complaint->room->user ?? null;
            $phoneNum = $pic->phone_number ?? $pic->phone ?? $pic->no_hp ?? null;

            if (!$pic || !$phoneNum) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor WhatsApp PIC untuk ruangan ini tidak ditemukan/belum diisi.'
                ], 400);
            }

            $phone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $phoneNum));
            $picName = $pic->fullname ?? $pic->name ?? 'PIC';
            $roomName = $complaint->room->room_name ?? $complaint->room->name ?? 'Ruangan';

            $message = "Halo Kak {$picName} (PIC Ruang {$roomName}),\n\n"
                . "Mohon untuk segera menindaklanjuti laporan kerusakan berikut:\n"
                . "📌 *Fasilitas*: {$complaint->description}\n"
                . "🔄 *Status Saat Ini*: {$complaint->status_repair}\n\n"
                . "Terima kasih.\n_Waka Sarpras Admin_";

            $waUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

            return response()->json([
                'status' => 'success',
                'wa_url' => $waUrl
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat link WA: ' . $e->getMessage()
            ], 500);
        }
    }
}