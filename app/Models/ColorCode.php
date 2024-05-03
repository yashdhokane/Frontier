<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorCode extends Model
{
    use HasFactory;
     protected $table = 'color_codes';

    protected $primaryKey = 'color_id';


    protected $fillable = [
        'color_code',
    ];

    public $timestamps = false;
}
