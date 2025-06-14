<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'resep_id',
        'user_id',
        'foto',
        'deskripsi',
        'bintang',
    ];

    // Relasi ke model Resep
    public function resep()
    {
        return $this->belongsTo(Resep::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
