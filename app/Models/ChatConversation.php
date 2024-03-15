<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;
    protected $table = 'chat_conversation';
    protected $primaryKey = 'id';

    protected $fillable = [
        'created_date',
        'created_by',
        'send_to',
        'last_activity',
    ];

    public function Participant()
    {
        return $this->hasMany(ChatParticipants::class, 'conversation_id', 'id');
    }

}