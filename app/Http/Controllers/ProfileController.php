<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display user profile with recipes and favorites
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's recipes
        $userRecipes = Recipe::where('user_id', $user->id)
                           ->withAvg('reviews', 'bintang')
                           ->withCount('reviews')
                           ->orderBy('created_at', 'desc')
                           ->get();

        // Get user's favorite recipes
        $favoriteRecipes = $user->favoriteRecipes()
                               ->withAvg('reviews', 'bintang') 
                               ->withCount('reviews')
                               ->orderBy('created_at', 'desc')
                               ->get();

        // Statistics
        $stats = [
            'total_recipes' => $userRecipes->count(),
            'total_followers' => $user->followers()->count(),
            'total_following' => $user->following()->count(),
            'total_favorites' => $favoriteRecipes->count()
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Profile data retrieved successfully',
            'data' => [
                'user' => $user,
                'stats' => $stats,
                'recipes' => $userRecipes,
                'favorites' => $favoriteRecipes
            ]
        ]);
    }

    /**
     * Display followers list
     */
    public function followers(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');
        
        $followers = $user->followers()
                         ->when($search, function ($query, $search) {
                             return $query->where('name', 'LIKE', "%{$search}%")
                                         ->orWhere('email', 'LIKE', "%{$search}%");
                         })
                         ->withPivot('created_at')
                         ->orderBy('pivot_created_at', 'desc')
                         ->get();

        $stats = [
            'total_following' => $user->following()->count(),
            'total_followers' => $user->followers()->count()
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Followers retrieved successfully',
            'data' => [
                'user' => $user,
                'stats' => $stats,
                'followers' => $followers
            ]
        ]);
    }

    /**
     * Display following list
     */

    public function following(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');
        
        $following = $user->following()
                        ->when($search, function ($query, $search) {
                            return $query->where('name', 'LIKE', "%{$search}%")
                                        ->orWhere('username', 'LIKE', "%{$search}%");
                        })
                        ->withPivot('created_at', 'notifications_enabled', 'is_blocked')
                        ->orderBy('pivot_created_at', 'desc')
                        ->get();

        $stats = [
            'total_following' => $user->following()->count(),
            'total_followers' => $user->followers()->count()
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Following list retrieved successfully',
            'data' => [
                'user' => $user,
                'stats' => $stats,
                'following' => $following->map(function ($followedUser) {
                    return [
                        'id' => $followedUser->id,
                        'name' => $followedUser->name,
                        'username' => $followedUser->username,
                        'foto_url' => $followedUser->foto ? asset('storage/photos/' . $followedUser->foto) : null,
                        'followed_at' => $followedUser->pivot->created_at,
                        'notifications_enabled' => $followedUser->pivot->notifications_enabled ?? true,
                        'is_blocked' => $followedUser->pivot->is_blocked ?? false,
                    ];
                })
            ]
        ]);
    }

    /**
     * Manage notifications for followed user
     */
    // public function manageNotifications(Request $request, $userId)
    // {
    //     $user = Auth::user();
    //     $request->validate([
    //         'notifications_enabled' => 'required|boolean'
    //     ]);

    //     // Update pivot table
    //     $user->following()->updateExistingPivot($userId, [
    //         'notifications_enabled' => $request->notifications_enabled
    //     ]);

    //     $action = $request->notifications_enabled ? 'enabled' : 'disabled';

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => "Notifications {$action} successfully",
    //         'data' => [
    //             'user_id' => $userId,
    //             'notifications_enabled' => $request->notifications_enabled
    //         ]
    //     ]);
    // }

    /**
     * Block a followed user
     */
    // public function blockUser($userId)
    // {
    //     $user = Auth::user();
    //     $targetUser = User::findOrFail($userId);

    //     // Update pivot table to mark as blocked
    //     $user->following()->updateExistingPivot($userId, [
    //         'is_blocked' => true
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'User blocked successfully',
    //         'data' => [
    //             'blocked_user' => [
    //                 'id' => $targetUser->id,
    //                 'name' => $targetUser->name,
    //                 'username' => $targetUser->username
    //             ]
    //         ]
    //     ]);
    // }

    /**
     * Unblock a user
     */
    // public function unblockUser($userId)
    // {
    //     $user = Auth::user();

    //     $user->following()->updateExistingPivot($userId, [
    //         'is_blocked' => false
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'User unblocked successfully',
    //         'data' => [
    //             'user_id' => $userId
    //         ]
    //     ]);
    // }

    /**
     * Report a user
     */
    // public function reportUser(Request $request, $userId)
    // {
    //     $user = Auth::user();
    //     $targetUser = User::findOrFail($userId);
        
    //     $request->validate([
    //         'reason' => 'required|string|max:500',
    //         'category' => 'required|in:spam,harassment,inappropriate_content,fake_account,other'
    //     ]);

    //     // Create report record (you might need a reports table)
    //     $report = [
    //         'reporter_id' => $user->id,
    //         'reported_user_id' => $userId,
    //         'reason' => $request->reason,
    //         'category' => $request->category,
    //         'created_at' => now()
    //     ];

    //     // Save to reports table or handle as needed
    //     // Report::create($report);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'User reported successfully',
    //         'data' => [
    //             'reported_user' => [
    //                 'id' => $targetUser->id,
    //                 'name' => $targetUser->name,
    //                 'username' => $targetUser->username
    //             ],
    //             'report_details' => [
    //                 'reason' => $request->reason,
    //                 'category' => $request->category
    //             ]
    //         ]
    //     ]);
    // }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50|unique:users,username,' . $user->id,
            'presentation' => 'sometimes|string|max:500',
            'add_link' => 'sometimes|url|max:255',
            'foto' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($user->foto && Storage::exists('public/photos/' . $user->foto)) {
                Storage::delete('public/photos/' . $user->foto);
            }
            
            $fotoPath = $request->file('foto')->store('photos', 'public');
            $user->foto = basename($fotoPath);
        }

        // Update only editable fields from UI
        $user->fill($request->only([
            'name', 
            'username', 
            'presentation', 
            'add_link'
        ]));
        
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => $user,
                'updated_fields' => [
                    'name' => $user->name,
                    'username' => $user->username,
                    'presentation' => $user->presentation,
                    'add_link' => $user->add_link,
                    'foto' => $user->foto ? asset('storage/photos/' . $user->foto) : null
                ]
            ]
        ]);
    }

    /**
     * Get profile share data
     */
    public function shareProfile()
    {
        $user = Auth::user();
        
        $shareData = [
            'user_id' => $user->id,
            'username' => $user->username ?? $user->name,
            'profile_url' => config('app.url') . '/profile/' . $user->id,
            'qr_code_url' => config('app.url') . '/api/profile/qr/' . $user->id,
            'share_text' => "Check out {$user->name}'s delicious recipes on DelishApp!",
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Share profile data retrieved successfully',
            'data' => $shareData
        ]);
    }

    /**
     * Follow a user
     */
    public function followUser($userId)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($userId);
        
        if ($user->id == $userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot follow yourself'
            ], 400);
        }

        if ($user->following()->where('followed_user_id', $userId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already following this user'
            ], 409);
        }

        $user->following()->attach($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'User followed successfully',
            'data' => [
                'followed_user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'username' => $targetUser->username,
                ]
            ]
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollowUser($userId)
    {
        $user = Auth::user();
        $user->following()->detach($userId);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully unfollowed user',
            'data' => [
                'following_count' => $user->following()->count()
            ]
        ]);
    }

    /**
     * Toggle follow status
     */
    public function toggleFollow($userId)
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($userId);
        
        if ($user->id == $userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot follow yourself'
            ], 400);
        }

        if ($user->following()->where('followed_user_id', $userId)->exists()) {
            $user->following()->detach($userId);
            $message = 'Successfully unfollowed user';
            $following = false;
        } else {
            $user->following()->attach($userId);
            $message = 'Successfully followed user';
            $following = true;
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => [
                'following' => $following,
                'following_count' => $user->following()->count(),
                'target_user' => $targetUser
            ]
        ]);
    }

    /**
     * Generate QR Code for profile sharing
     */
    public function generateProfileQR($userId)
    {
        $user = User::findOrFail($userId);
        $profileUrl = config('app.url') . '/profile/' . $user->id;
        
        // You can use a QR code library like SimpleSoftwareIO/simple-qrcode
        // For now, return the URL that should be converted to QR code
        return response()->json([
            'status' => 'success',
            'message' => 'QR code data generated successfully',
            'data' => [
                'profile_url' => $profileUrl,
                'qr_text' => $profileUrl,
                'user' => $user
            ]
        ]);
    }

    /**
     * Add recipe to favorites
     */
    public function addToFavorites($recipeId)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($recipeId);
        
        // Check if already favorited
        if ($user->favoriteRecipes()->where('recipe_id', $recipeId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe already in favorites'
            ], 409);
        }

        $user->favoriteRecipes()->attach($recipeId);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe added to favorites successfully'
        ]);
    }

    /**
     * Remove recipe from favorites
     */
    public function removeFromFavorites($recipeId)
    {
        $user = Auth::user();
        $user->favoriteRecipes()->detach($recipeId);

        return response()->json([
            'status' => 'success',
            'message' => 'Recipe removed from favorites successfully'
        ]);
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite($recipeId)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($recipeId);
        
        if ($user->favoriteRecipes()->where('recipe_id', $recipeId)->exists()) {
            $user->favoriteRecipes()->detach($recipeId);
            $message = 'Recipe removed from favorites';
            $favorited = false;
        } else {
            $user->favoriteRecipes()->attach($recipeId);
            $message = 'Recipe added to favorites';
            $favorited = true;
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'favorited' => $favorited
        ]);
    }

    // API: Search Followers
    public function apiSearchFollowers(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $search = $request->input('search');

        $followers = $user->followers()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return response()->json([
            'user' => $user->only(['id', 'name', 'username']),
            'followers' => $followers,
            'search' => $search,
        ]);
    }

    // API: Search Following
    public function apiSearchFollowing(Request $request, $username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $search = $request->input('search');

        $following = $user->following()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->paginate(10);

        return response()->json([
            'user' => $user->only(['id', 'name', 'username']),
            'following' => $following,
            'search' => $search,
        ]);
    }
}