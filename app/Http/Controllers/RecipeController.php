<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    // 1. Ambil semua resep (dengan filter kategori opsional)
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $recipes = Recipe::withAvg('reviews', 'bintang')
                    ->withCount('reviews')
                    ->when($kategori, function ($query, $kategori) {
                        return $query->where('kategori', $kategori);
                    })
                    ->get();

        return response()->json($recipes);
    }

    // 2. Tampilkan detail satu resep
    public function show($id)
    {
        $recipe = Recipe::with(['steps', 'reviews.user', 'user', 'ingredients'])
                    ->withAvg('reviews', 'bintang')
                    ->withCount('reviews')
                    ->withCount('steps')
                    ->findOrFail($id);

        return response()->json($recipe);
    }

    // 3. Simpan resep baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'detail' => 'required|string',
            'ingredients' => 'required|string',
            'durasi' => 'required|string',
            'kategori' => 'required|string',
            'jenis_hidangan' => 'nullable|string',
            'estimasi_waktu' => 'nullable|string',
            'tingkat_kesulitan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('recipes', 'public');
        }

        $recipe = Recipe::create($validated);

        // Kirim notifikasi ke followers
        $user = Auth::user();
        $judulNotif = "{$user->name} mengunggah resep baru!";
        $deskripsiNotif = "Lihat resep: {$recipe->nama}";

        app(NotificationController::class)->sendToFollowers($user, $judulNotif, $deskripsiNotif);

        return response()->json([
            'message' => 'Resep berhasil ditambahkan',
            'data' => $recipe
        ], 201);
    }

    // 4. Update resep
    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'detail' => 'required|string',
            'ingredients' => 'required|string',
            'durasi' => 'required|string',
            'kategori' => 'required|string',
            'jenis_hidangan' => 'nullable|string',
            'estimasi_waktu' => 'nullable|string',
            'tingkat_kesulitan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('recipes', 'public');
        }

        $recipe->update($validated);

        return response()->json([
            'message' => 'Resep berhasil diperbarui',
            'data' => $recipe
        ]);
    }

    // 5. Hapus resep
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        return response()->json([
            'message' => 'Resep berhasil dihapus'
        ]);
    }
}