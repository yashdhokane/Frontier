<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTechEvent extends Model
{
    use HasFactory;
    public $timestamps = false; // Disable timestamps
    protected $table = 'job_tech_events';
    protected $primaryKey = 'auto_id';

    protected $fillable = ['job_id','job_schedule','job_enroute','job_start','job_end','job_invoice','job_payment'];
}
