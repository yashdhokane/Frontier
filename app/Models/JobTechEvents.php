<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTechEvents extends Model
{
    use HasFactory;


    protected $table = 'job_tech_events';


    protected $primaryKey = 'auto_id';



    protected $fillable = [
        'job_id',
        'job_schedule',
        'job_enroute',
        'job_start',
        'job_end',
        'job_invoice',
        'job_payment',
        'enroute_time',
        'job_time',
        'total_time_on_job',
        'is_repair_complete',
        'additional_details',
        'customer_signature',
        'tech_completed',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'job_schedule' => 'datetime',
        'job_enroute' => 'datetime',
        'job_start' => 'datetime',
        'job_end' => 'datetime',
        'job_invoice' => 'datetime',
        'job_payment' => 'datetime',
    ];

    public $timestamps = false;
}