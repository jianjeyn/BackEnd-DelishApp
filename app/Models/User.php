<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',        // Added for edit profile
        'no_hp',
        'tanggal_lahir',
        'password',
        'gender',
        'foto',
        'presentation',    // Added for bio
        'add_link',       // Added for social link
        'community_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor for foto URL
    public function getFotoUrlAttribute()
    {
        return $this->foto 
            ? asset('storage/photos/' . $this->foto)
            : asset('images/default-avatar.png');
    }

    // Existing relationships
    public function community()
    {
        return $this->belongsTo(Community::class, 'community_user');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_user');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
    }

    // Fixed relationships for ProfileController
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'from_user_id', 'to_user_id')
                    ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'to_user_id', 'from_user_id')
                    ->withPivot('notifications_enabled', 'is_blocked')
                    ->withTimestamps();
    }

    public function favoriteRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id')
                    ->withTimestamps();
    }
}