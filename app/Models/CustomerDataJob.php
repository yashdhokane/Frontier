<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDataJob extends Model
{
    use HasFactory;
    protected $table = 'customer_jobs';
    protected $primaryKey = 'job_id';
    public $timestamps = false;

    protected $fillable = [
        'data_id',
        'user_id',
        'ticket_number',
        'tcc',
        'schedule_date',
        'technician',
        'status',
        'time_travel',
        'time_on_job',
        'total_time',
    ];

    public function Customerservice()
    {
        return $this->hasOne(CustomerDataServices::class, 'job_id', 'job_id');
    }
    public function Customernote()
    {
        return $this->hasOne(CustomerDataNotes::class, 'job_id', 'job_id');
    }

    public function Customerservicemany()
    {
        return $this->hasMany(CustomerDataServices::class, 'job_id', 'job_id');
    }
    public function Customernotemany()
    {
        return $this->hasMany(CustomerDataNotes::class, 'job_id', 'job_id');
    }

    public function filesmany()
    {
        return $this->hasMany(CustomerFiles::class, 'user_id', 'user_id');
    }
}
