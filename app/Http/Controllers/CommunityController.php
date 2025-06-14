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

    // Tampilkan detail komunitas dan daftar user-nya
    public function show($id)
    {
        $community = Community::with(['users.recipes'])->findOrFail($id);

        // Ambil semua resep dari user yang mengikuti komunitas
        $recipes = $community->users->flatMap(function ($user) {
            return $user->recipes;
        });

        return response()->json([
            'community' => $community->only(['id', 'nama']),
            'users' => $community->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            }),
            'recipes' => $recipes
        ]);
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
}
