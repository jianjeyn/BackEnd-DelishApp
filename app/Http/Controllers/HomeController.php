<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $filteredRecipes = Recipe::when($kategori, function ($query, $kategori) {
            return $query->where('kategori', $kategori);
        })->get();

        $trending = Recipe::withAvg('reviews', 'bintang')
                        ->orderByDesc('reviews_avg_bintang')
                        ->take(5)
                        ->get();

        $yourRecipes = Recipe::where('user_id', Auth::id())->take(5)->get();

        $recentlyAdded = Recipe::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'filtered' => $filteredRecipes,
            'trending' => $trending,
            'your_recipes' => $yourRecipes,
            'recently_added' => $recentlyAdded,
        ]);
    }
}
