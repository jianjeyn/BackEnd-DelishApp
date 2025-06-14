<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = $request->validate ([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'no_hp'          => 'required|string|unique:users,no_hp',
            'tanggal_lahir'  => 'required|date',
            'password'       => 'required|string|min:6|confirmed',
            'gender'         => 'required|in:male,female',
            'community_id'   => 'nullable|exists:communities,id',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'fail',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan foto kalau ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('user_foto', 'public');
        }

        // Simpan user baru
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'password'       => Hash::make($request->password),
            'gender'         => $request->gender,
            'foto'           => $fotoPath,
            'community_id'   => $request->community_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil',
            'data' => $user
        ], 201);
    }


}
