<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Ambil semua review untuk resep tertentu
    public function index(Request $request)
    {
        $resep_id = $request->query('resep_id');

        $reviews = Review::with('user')
                    ->where('resep_id', $resep_id)
                    ->latest()
                    ->get();

        return response()->json($reviews);
    }

    // Simpan review baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resep_id' => 'required|exists:recipes,id',
            'deskripsi' => 'nullable|string',
            'bintang' => 'required|integer|min:1|max:5',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('reviews', 'public');
        }

        $review = Review::create($validated);

        return response()->json([
            'message' => 'Review berhasil ditambahkan',
            'data' => $review,
        ], 201);
    }
}