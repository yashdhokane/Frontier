<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasFactory;
    protected $table = 'time_zones';
    protected $primaryKey = 'timezone_id';

    protected $fillable = [
        'country_code', 'country_name','country_url','timezone_name','gmt_offset'
    ];

   
}
