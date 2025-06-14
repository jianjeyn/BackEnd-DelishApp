<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrendingController extends Controller
{
    /**
     * Display all trending recipes
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $kategori = $request->query('kategori');

        // Get trending recipes based on average rating
        $trending = Recipe::with(['user'])
                        ->withAvg('reviews', 'bintang')
                        ->when($kategori, function ($query, $kategori) {
                            return $query->where('kategori', $kategori);
                        })
                        ->orderByDesc('reviews_avg_bintang')
                        ->orderByDesc('id')
                        ->take($limit)
                        ->get();

        // Most viewed today
        $mostViewedToday = Recipe::with(['user'])
                                ->withAvg('reviews', 'bintang')
                                ->whereDate('created_at', today())
                                ->orderByDesc('reviews_avg_bintang')
                                ->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Trending recipes retrieved successfully',
            'data' => [
                'most_viewed_today' => $mostViewedToday,
                'trending_recipes' => $trending,
                'total' => $trending->count()
            ]
        ]);
    }

    /**
     * Get specific trending recipe detail by ID
     */
    public function show($id)
    {
        $recipe = Recipe::with(['steps', 'user'])
                       ->withAvg('reviews', 'bintang')
                       ->withCount('reviews')
                       ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe details retrieved successfully',
            'data' => [
                'recipe' => $recipe,
                'ingredients' => json_decode($recipe->ingredients, true),
                'steps' => $recipe->steps,
                'rating' => $recipe->reviews_avg_bintang ?? 0,
                'total_reviews' => $recipe->reviews_count ?? 0,
                'chef' => $recipe->user
            ]
        ]);
    }

    /**
     * Get trending by category
     */
    // public function byCategory($kategori)
    // {
    //     $trending = Recipe::with(['user'])
    //                     ->where('kategori', $kategori)
    //                     ->withAvg('reviews', 'bintang')
    //                     ->orderByDesc('reviews_avg_bintang')
    //                     ->take(10)
    //                     ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => "Trending {$kategori} recipes retrieved successfully",
    //         'data' => $trending,
    //         'category' => $kategori
    //     ]);
    // }

    /**
     * Get most viewed today (untuk section khusus)
     */
    // public function mostViewedToday()
    // {
    //     $recipe = Recipe::with(['user'])
    //                    ->withAvg('reviews', 'bintang')
    //                    ->whereDate('created_at', today())
    //                    ->orderByDesc('reviews_avg_bintang')
    //                    ->first();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Most viewed recipe today retrieved successfully',
    //         'data' => $recipe
    //     ]);
    // }

    /**
     * Search trending recipes
     */
    // public function search(Request $request)
    // {
    //     $query = $request->query('q');
        
    //     $results = Recipe::with(['user'])
    //                     ->withAvg('reviews', 'bintang')
    //                     ->where('nama', 'LIKE', "%{$query}%")
    //                     ->orWhere('detail', 'LIKE', "%{$query}%")
    //                     ->orderByDesc('reviews_avg_bintang')
    //                     ->take(10)
    //                     ->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Search results retrieved successfully',
    //         'data' => $results,
    //         'query' => $query,
    //         'total' => $results->count()
    //     ]);
    // }
}