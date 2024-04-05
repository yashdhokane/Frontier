<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $primaryKey = 'id';

    protected $fillable = ['event_type', 'technician_id', 'job_id', 'event_name', 'event_description', 'event_location', 'start_date_time', 'end_date_time', 'label_color', 'repeat', 'repeat_every', 'repeat_cycles', 'repeat_type', 'send_reminder', 'remind_time', 'remind_type', 'event_link', 'added_by', 'updated_by'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }
}
