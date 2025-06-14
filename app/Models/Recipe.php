<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    // Field yang bisa diisi secara mass-assignment
    protected $fillable = [
        'nama',
        'foto',
        'detail',
        'durasi',
        'kategori',
        'jenis_hidangan',
        'estimasi_waktu',
        'tingkat_kesulitan',
        'user_id', // Foreign key untuk relasi dengan User
    ];


    // Relasi: Satu resep punya banyak langkah (steps)
    public function steps()
    {
        return $this->hasMany(Step::class, 'resep_id');
    }

    // Relasi: Satu resep punya banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'resep_id');
    }

    // Relasi: Banyak user bisa memfavoritkan satu resep
    public function favoritByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite', 'resep_id', 'user_id');
    }

    // Relasi: Satu resep dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
