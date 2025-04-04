<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAssign extends Model
{
    use HasFactory;
    protected $table = 'job_assigned';
    protected $fillable = [
        'technician_id',
        'customer_id',
        'job_id',
        'duration',
        'driving_hours',
        'assign_title',
        'assign_description',
        'start_date_time',
        'end_date_time',
        'remind_type',
        'added_by',
        'updated_by',
        'start_slot',
        'end_slot',
        'technician_note_id',
        'pending_number',
        'timezone_id',
        'start_date_time',
        'end_date_time',
        'created_at',
        'updated_at'
    ];
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function techniciannote()
    {
        return $this->belongsTo(JobNoteModel::class, 'technician_note_id', 'id');
    }
    
    public function TimeZone()
    {
        return $this->belongsTo(TimeZone::class, 'timezone_id'); // Assuming 'assigned_timezone_id' is the foreign key
    }
    public function JobModel()
    {
        return $this->belongsTo(JobModel::class, 'job_id', 'id');
    }

    public function userAddress()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'customer_id');
    }

}
