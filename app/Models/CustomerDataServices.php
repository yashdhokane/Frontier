<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDataServices extends Model
{
    use HasFactory;

    protected $table = 'customer_services'; // Define the table name

    protected $primaryKey = 'service_id'; // Set the primary key

    protected $fillable = [
        'job_id',
        'service_name',
        'amount',
    ];

    public $timestamps = false; // Disable timestamps

}