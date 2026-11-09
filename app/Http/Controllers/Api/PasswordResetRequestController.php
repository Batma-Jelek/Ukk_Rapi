<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;

class PasswordResetRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $user = User::query()
            ->where('username', $validated['username'])
            ->whereRaw('LOWER(fullname) = LOWER(?)', [$validated['fullname']])
            ->where('phone_number', $validated['phone_number'])
            ->first();

        if ($user) {
            PasswordResetRequest::query()->firstOrCreate(
                [
                    'user_id' => $user->id,
                    'status' => 'pending',
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Jika data sesuai dengan akun, pengajuan sudah diterima dan akan diverifikasi oleh admin sekolah.',
        ], 202);
    }

    public function index(Request $request)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat melihat pengajuan reset sandi.');

        $requests = PasswordResetRequest::query()
            ->with('user.role:id,role_name')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    public function complete(Request $request, PasswordResetRequest $passwordResetRequest)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat menyelesaikan pengajuan reset sandi.');

        if ($passwordResetRequest->status === 'pending') {
            $passwordResetRequest->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan reset sandi telah ditandai selesai.',
        ]);
    }
}
