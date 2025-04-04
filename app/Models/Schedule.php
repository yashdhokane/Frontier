<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    protected $primaryKey = 'id';

    protected $fillable = ['schedule_type', 'job_id', 'event_id', 'estimate_id', 'technician_id', 'start_date_time', 'end_date_time', 'remind_type', 'added_by', 'updated_by', 'start_slot', 'end_slot', 'timezone_id', 'show_on_schedule', 'position', 'is_routes_map', 'job_onmap_reaching_timing'];

    public function JobModel()
    {
        return $this->belongsTo(JobModel::class, 'job_id', 'id');
    }
    public function JobModel1()
    {
        return $this->hasOne(JobModel::class, 'id', 'job_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function JobTechEvent()
    {
        return $this->hasOne(JobTechEvents::class, 'job_id', 'job_id');
    }

    public function Jobassign()
    {
        return $this->belongsTo(JobAssign::class, 'job_id', 'job_id')
            ->select(['job_id', 'start_date_time', 'end_date_time', 'duration', 'driving_hours', 'customer_id']);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

}
