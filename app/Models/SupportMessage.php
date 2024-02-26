<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;
    protected $table = 'support_message';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_one', 'user_two'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_one','id');
    }
    
}