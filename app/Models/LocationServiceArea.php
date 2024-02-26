<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationServiceArea extends Model
{
    use HasFactory;
    protected $table = 'location_service_area';
    protected $primaryKey = 'area_id';
    protected $fillable = [
        'area_id',
        'area_name',
        'area_description',
        'area_type',
        'area_radius',
        'area_latitude',
        'area_longitude',
        'created_at',
        'updated_at',
    ];
}
