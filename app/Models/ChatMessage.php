<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;
    protected $table = 'chat_message';

    protected $fillable = [
        'sender',
        'conversation_id',
        'message',
        'time',
        'file',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
}
