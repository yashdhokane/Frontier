<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestRoute extends Model
{
    use HasFactory;
    protected $primarykey = 'id';
    protected $table = 'best_routes';

    protected $fillable = [
        'job_id',
        'date',
        'order',
        'latitude',
        'longitude',
    ];
}
