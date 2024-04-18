<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFiles extends Model
{
    use HasFactory;
    protected $table = 'user_files';

    protected $fillable = [
        'user_id',
        'path',
        'filename',
        'type',
        'size',
        'storage_location',
    ];
}