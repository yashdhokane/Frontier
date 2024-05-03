<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notifications';

    protected $fillable = [
        'notice_id',
        'user_id',
        'is_read',
        'read_at',
    ];

     public function notice()
    {
        return $this->hasOne(NotificationModel::class, 'notice_id', 'notice_id');
    }

         public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
        public $timestamps = false;

}