<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'resep_id',
        'no',
        'deskripsi',
    ];

    // Relasi ke model Resep
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
