<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityUser extends Model
{
    protected $table = 'community_user';

    protected $fillable = [
        'community_id', 
        'user_id'
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
