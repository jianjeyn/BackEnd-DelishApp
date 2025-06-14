<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // 1. Tampilkan semua review dari satu resep
    public function index($resep_id)
    {
        $resep = Resep::findOrFail($resep_id);
        $reviews = Review::with('user')
                        ->where('resep_id', $resep_id)
                        ->latest()
                        ->get();

        return view('reviews.index', compact('resep', 'reviews'));
    }

    // 2. Tampilkan form tambah review
    public function create($resep_id)
    {
        $resep = Resep::findOrFail($resep_id);
        return view('reviews.create', compact('resep'));
    }

    // 3. Simpan review baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resep_id' => 'required|exists:reseps,id',
            'deskripsi' => 'nullable|string',
            'bintang' => 'required|integer|min:1|max:5',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('reviews', 'public');
        }

        Review::create($validated);

        return redirect()->route('reviews.index', $validated['resep_id'])
                         ->with('success', 'Review berhasil ditambahkan!');
    }
}