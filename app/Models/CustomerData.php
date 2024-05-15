<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory;

    protected $table = 'customer_data'; // Define the table name
    protected $primaryKey = 'data_id'; // Set the primary key

    protected $fillable = [
        'user_id',
        'no_of_visits',
        'job_completed',
        'issue_resolved',
        'admin_comment',
        'tcc',
        'schedule_date',
        'created_at',
        'ticket_number',
        'updated_at',
        'created_at',


    ];

    //  public $timestamps = false; // Disable timestamps

    public function Jobdata()
    {
        return $this->hasMany(CustomerDataJob::class, 'user_id', 'user_id');
    }
    public function userdata()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function userdata1()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
