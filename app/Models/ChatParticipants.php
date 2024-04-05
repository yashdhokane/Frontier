<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class ChatParticipants extends Model
{
    use HasFactory;
    protected $table = 'chat_participants';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'join_time',
        'added_by',
        'is_unread',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', );
    }
    public function latestParticipant()
    {
        return $this->hasOne(ChatParticipants::class, 'conversation_id')
            ->latest('id');
    }
    public $timestamps = false;
}