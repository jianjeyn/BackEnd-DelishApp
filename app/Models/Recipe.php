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
        'ingredients',
        'durasi',
        'kategori',
        'jenis_hidangan',
        'estimasi_waktu',
        'tingkat_kesulitan',
    ];

    // Relasi: Satu resep punya banyak langkah (steps)
    public function steps()
    {
        return $this->hasMany(Step::class, 'fk_resep');
    }

    // Relasi: Satu resep punya banyak review
    public function reviews()
    {
        return $this->hasMany(Review::class, 'fk_resep');
    }

    // Relasi: Banyak user bisa memfavoritkan satu resep
    public function favoritByUsers()
    {
        return $this->belongsToMany(User::class, 'favorits', 'fk_resep', 'fk_user');
    }
}
