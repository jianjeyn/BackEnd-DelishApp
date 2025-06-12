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
}
