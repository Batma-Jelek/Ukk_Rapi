<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List pengguna
    public function index(Request $request)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat melihat daftar akun.');

        $validated = $request->validate([
            'role_id' => 'sometimes|integer|exists:roles,id',
        ]);

        $users = User::with('role:id,role_name')
            ->when(
                isset($validated['role_id']),
                fn ($query) => $query->where('role_id', $validated['role_id'])
            )
            ->orderBy('fullname')
            ->get();

        return response()->json(['success' => true, 'data' => $users], 200);
    }

    // Tambah user baru (Oleh Admin)
    public function store(Request $request)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat menambahkan akun.');

        $request->validate([
            'username'     => 'required|string|unique:users,username',
            'password'     => 'required|string|min:6',
            'fullname'     => 'required|string',
            'phone_number' => 'required|string',
            'role_id'      => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'fullname'     => $request->fullname,
            'phone_number' => $request->phone_number,
            'role_id'      => $request->role_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan.',
            'data'    => $user
        ], 201);
    }

    // Perbarui akun (Oleh Admin)
    public function update(Request $request, User $user)
    {
        abort_unless((int) $request->user()->role_id === 1, 403, 'Hanya admin yang dapat mengubah akun.');

        $validated = $request->validate([
            'username'     => 'required|string|unique:users,username,'.$user->id,
            'fullname'     => 'required|string',
            'phone_number' => 'required|string|max:20',
            'role_id'      => 'required|integer|exists:roles,id',
            'password'     => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui.',
            'data'    => $user->load('role:id,role_name'),
        ], 200);
    }
}