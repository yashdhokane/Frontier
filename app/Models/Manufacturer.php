<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = 'manufacturers';
    protected $fillable = [
        'parent_id',
        'manufacturer_name',
        'manufacturer_description',
        'manufacturer_image',
        'added_by',
        'last_updated_by',
        'is_active',
    ];
}