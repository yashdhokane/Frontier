<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersActivity extends Model
{
    use HasFactory;
    protected $table = 'user_activity'; // Assuming your table name is 'users_activity'

    protected $fillable = [
        'user_id',
        'activity', // Assuming these are the fields in your table
    ];

    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}