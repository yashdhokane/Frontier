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

    public function techniciannote()
    {
        return $this->belongsTo(JobNoteModel::class, 'technician_note_id', 'id');
    }


}
