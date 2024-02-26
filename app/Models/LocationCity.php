<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationCity extends Model
{
    protected $table = 'location_cities';
    protected $primaryKey = 'city_id';
    use HasFactory;
    protected $fillable = [
        'city',
        'state_code',
        'zip',
        'latitude',
        'longitude',
        'county',
        'state_id',
    ];
}
