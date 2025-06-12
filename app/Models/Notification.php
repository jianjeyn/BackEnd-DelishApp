<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    // Field yang bisa diisi secara mass-assignment
    protected $fillable = [
        'judul',
        'deskripsi',
    ];

    // Relasi: One notification has many notification_users (one to many)
    public function notificationUsers()
    {
        return $this->hasMany(NotificationUser::class, 'notification_id');
    }

    // Jika ingin akses users melalui notification_users
    public function users()
    {
        return $this->hasManyThrough(User::class, NotificationUser::class, 'notification_id', 'id', 'id', 'user_id');
    }
}