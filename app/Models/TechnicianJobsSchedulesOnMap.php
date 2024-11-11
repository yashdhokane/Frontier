<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianJobsSchedulesOnMap extends Model
{
    use HasFactory;
    protected $table = 'technician_jobs_schedules_on_map';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Set fillable properties for mass assignment
    protected $fillable = [
        'technician_ids',
        'days_ids',
        'previous_start_date_time'
    ];

    // Enable timestamps if the table has created_at and updated_at fields
    public $timestamps = true;
}
