<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Recipe::select('kategori')
                        ->distinct()
                        ->pluck('kategori');

        $filteredRecipes = Recipe::all();

        $trending = Recipe::withAvg('reviews', 'bintang')
                        ->orderByDesc('reviews_avg_bintang')
                        ->take(5)
                        ->get();

        $yourRecipes = Recipe::where('user_id', Auth::id())->take(5)->get();

        $recentlyAdded = Recipe::orderBy('created_at', 'desc')->take(5)->get();

        return response()->json([
            'categories' => $categories,
            'filtered' => $filteredRecipes,
            'trending' => $trending,
            'your_recipes' => $yourRecipes,
            'recently_added' => $recentlyAdded,
        ]);
    }
}
