<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRoutingCron extends Model
{
    use HasFactory;

    protected $table = 'routing_cron';

    // Primary key
    protected $primaryKey = 'cron_id';

    // Fillable fields
    protected $fillable = [
        'route_id',
        'cron_route_time',
        'cron_route_next_date',
        'cron_route_active',
        'cron_re_route_time',
        'cron_re_route_next_date',
        'cron_re_route_active',
        'cron_publish_time',
        'cron_publish_next_date',
        'cron_publish_active',
        'cron_job_publish',
        'cron_job_previous',
        'number_of_calls',
        'tech_ids',
    ];

    // Disable timestamps if the table doesn't have created_at and updated_at fields managed by Eloquent
    public $timestamps = false;
}
