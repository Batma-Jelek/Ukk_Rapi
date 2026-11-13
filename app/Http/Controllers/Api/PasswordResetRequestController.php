<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\UserPasswordResetLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

        $whatsappUrl = null;
        if ($user) {
            $phoneNumber = $this->normalizeWhatsappNumber($user->phone_number ?? '');
            if ($phoneNumber !== null) {
                $whatsappUrl = $this->createResetLink($user, $phoneNumber)['whatsapp_url'];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Jika data sesuai dengan akun, WhatsApp akan terbuka dengan tautan untuk mengubah sandi.',
            'data' => [
                'whatsapp_url' => $whatsappUrl,
            ],
        ], 202);
    }

    public function sendLink(Request $request, PasswordResetRequest $passwordResetRequest)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat mengirim tautan reset sandi.');

        if ($passwordResetRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan ini sudah tidak aktif.',
            ], 422);
        }

        $user = $passwordResetRequest->user;
        $phoneNumber = $this->normalizeWhatsappNumber($user->phone_number ?? '');
        if ($phoneNumber === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp akun tidak valid. Perbarui nomor HP pengguna terlebih dahulu.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp siap dibuka dengan tautan reset sandi.',
            'data' => $this->createResetLink($user, $phoneNumber),
        ]);
    }

    private function normalizeWhatsappNumber(string $phoneNumber): ?string
    {
        $phoneNumber = preg_replace('/\D+/', '', $phoneNumber) ?? '';
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '62'.substr($phoneNumber, 1);
        } elseif (!str_starts_with($phoneNumber, '62')) {
            $phoneNumber = '62'.$phoneNumber;
        }

        return strlen($phoneNumber) >= 10 && strlen($phoneNumber) <= 15
            ? $phoneNumber
            : null;
    }

    private function createResetLink(User $user, string $phoneNumber): array
    {
        $plainToken = Str::random(64);
        $expiresAt = now()->addMinutes(60);

        DB::transaction(function () use ($user, $plainToken, $expiresAt) {
            UserPasswordResetLink::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'token_hash' => Hash::make($plainToken),
                    'expires_at' => $expiresAt,
                ]
            );
        });

        $resetUrl = route('password.reset.form', [
            'token' => $plainToken,
            'username' => $user->username,
        ]);
        $message = "Halo {$user->fullname}, berikut tautan untuk mengubah sandi akun RAPI Sekolah: {$resetUrl}. Tautan berlaku 60 menit dan hanya dapat digunakan sekali.";

        return [
            'reset_url' => $resetUrl,
            'whatsapp_url' => 'https://wa.me/'.$phoneNumber.'?text='.rawurlencode($message),
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('username', $validated['username'])->first();
        if (!$user) {
            return $this->invalidResetLinkResponse();
        }

        $wasReset = DB::transaction(function () use ($user, $validated) {
            $resetLink = UserPasswordResetLink::where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (
                !$resetLink ||
                $resetLink->expires_at->isPast() ||
                !Hash::check($validated['token'], $resetLink->token_hash)
            ) {
                return false;
            }

            $user->password = Hash::make($validated['password']);
            $user->save();
            $user->tokens()->delete();

            PasswordResetRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

            $resetLink->delete();

            return true;
        });

        if (!$wasReset) {
            return $this->invalidResetLinkResponse();
        }

        return response()->json([
            'success' => true,
            'message' => 'Sandi berhasil diubah. Silakan masuk menggunakan sandi baru.',
        ]);
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

    private function invalidResetLinkResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Tautan reset sandi tidak valid atau sudah kedaluwarsa. Minta admin mengirim tautan baru.',
            'errors' => [
                'token' => ['Tautan reset sandi tidak valid atau sudah kedaluwarsa.'],
            ],
        ], 422);
    }
}
