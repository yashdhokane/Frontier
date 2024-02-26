<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHours extends Model
{
    use HasFactory;
    protected $table = 'site_working_hours';
    public $timestamps = false;

    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'open_close',
        'booking_start_time',
        'booking_end_time',
        'booking_open_close',
    ];

    
}
