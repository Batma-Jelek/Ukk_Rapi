<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List pengguna
    public function index()
    {
        $users = User::with('role')->get();
        return response()->json(['success' => true, 'data' => $users], 200);
    }

    // Tambah user baru (Oleh Admin)
    public function store(Request $request)
    {
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
}