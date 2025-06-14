<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    // GET: /api/search-page
    public function searchPage(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            'trending' => $this->getTrendingRecipes(),
            'recommended' => $this->recommendedRecipes($user),
            'your_recipes' => Recipe::where('user_id', $user?->id)->get(),
            'results' => $this->getSearchResults($request),
        ]);
    }

    // Trending: berdasarkan review dan bintang
    public function getTrendingRecipes()
    {
        return Recipe::withCount('reviews')
            ->withAvg('reviews', 'bintang')
            ->orderByDesc('reviews_avg_bintang')
            ->orderByDesc('reviews_count')
            ->limit(10)
            ->get();
    }

    // Rekomendasi berdasarkan kategori favorit user
    public function recommendedRecipes(?User $user)
    {
        if (!$user) {
            return Recipe::inRandomOrder()->limit(5)->get();
        }

        $favKategori = Recipe::whereHas('favoritByUsers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->select('kategori')
            ->groupBy('kategori')
            ->orderByRaw('COUNT(*) DESC')
            ->pluck('kategori')
            ->first();

        if (!$favKategori) {
            return Recipe::inRandomOrder()->limit(5)->get();
        }

        return Recipe::where('kategori', $favKategori)
            ->inRandomOrder()
            ->limit(5)
            ->get();
    }

    // Fitur search + filter
    public function getSearchResults(Request $request)
    {
        $query = Recipe::query();

        if ($request->filled('keyword')) {
            $query->where('nama', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('jenis_hidangan')) {
            $query->where('jenis_hidangan', $request->jenis_hidangan);
        }

        if ($request->filled('estimasi_waktu')) {
            $query->where('estimasi_waktu', $request->estimasi_waktu);
        }

        if ($request->filled('tingkat_kesulitan')) {
            $query->where('tingkat_kesulitan', $request->tingkat_kesulitan);
        }

        if ($request->filled('bahan')) {
            $query->whereHas('ingredients', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->bahan . '%');
            });
        }

        return $query->latest()->paginate(10);
    }
}
