<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $table = 'community';

    protected $fillable = [
        'nama'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_user');
    }

    public function recipes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Recipe::class,   // Model yang dituju
            CommunityUser::class, // Model penghubung (pivot)
            'community_id',  // Foreign key di community_user (mengacu ke community)
            'user_id',       // Foreign key di recipe (mengacu ke user)
            'id',            // Local key di community
            'user_id'        // Local key di community_user (mengacu ke user)
        );
    }
}
