<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobActivity extends Model
{
    use HasFactory;
    protected $table = 'job_activity';
    protected $primaryKey = 'id';
    public $timestamps = true; // Assuming 'created_at' field is not a timestamp

    protected $fillable = [
        'job_id',
        'user_id',
        'activity',
        'created_at'
    ];
}
