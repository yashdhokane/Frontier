<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessageReply extends Model
{
    use HasFactory;
    protected $table = 'support_message_reply';
    protected $primaryKey = 'id';

    protected $fillable = [
        'support_message_id', 'reply','user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    
}