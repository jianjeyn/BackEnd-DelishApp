<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // Tampilkan semua komunitas
    public function index()
    {
        $communities = Community::withCount('users')->get();
        return response()->json($communities);
    }

    // Simpan komunitas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        $community = Community::create([
            'nama' => $request->nama
        ]);

        return response()->json(['message' => 'Komunitas berhasil dibuat', 'data' => $community], 201);
    }

    // Tampilkan detail komunitas dan daftar user-nya
    public function show($id)
    {
        $community = Community::with('users')->findOrFail($id);
        return response()->json($community);
    }

    // Tambahkan user ke komunitas
    public function addUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $community = Community::findOrFail($id);
        $community->users()->syncWithoutDetaching($request->user_id); // tidak menimpa yang lain

        return response()->json(['message' => 'User berhasil ditambahkan ke komunitas']);
    }

    // Hapus user dari komunitas
    public function removeUser(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $community = Community::findOrFail($id);
        $community->users()->detach($request->user_id);

        return response()->json(['message' => 'User berhasil dihapus dari komunitas']);
    }

    // Hapus komunitas
    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->delete();

        return response()->json(['message' => 'Komunitas berhasil dihapus']);
    }
}
