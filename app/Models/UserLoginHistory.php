<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginHistory extends Model
{
    use HasFactory;
    protected $table = 'user_login_history';

    protected $fillable = [
        'user_id',
        'login',
        'logout',
        'ip_address',
    ];

    public $timestamps = false;

}