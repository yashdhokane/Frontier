<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatFile extends Model
{
    use HasFactory;
    protected $table = 'chat_files';

    protected $fillable = [
        'conversation_id',
        'filename',
        'type',
        'size',
    ];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
}