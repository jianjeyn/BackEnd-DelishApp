<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_user';

    protected $fillable = [
        'notification_id',
        'user_id',
    ];

    // Relasi: Belongs to Notification
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    // Relasi: Belongs to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}