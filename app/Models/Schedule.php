<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule'; // Assuming your table name is 'service_category'

    protected $fillable = ['schedule_type','job_id','event_id','estimate_id','start_date_time','end_date_time','remind_type','added_by','updated_by','start_slot','end_slot','timezone_id'];
}
