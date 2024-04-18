<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersDetails extends Model
{
    
    use HasFactory;

    protected $table = 'user_details';
    protected $primaryKey = 'data_id';

    protected $fillable = [
        'user_id',
        'unique_number',
        'first_name',
        'last_name',
        'home_phone',
        'work_phone',
        'additional_email',
        'customer_company',
        'customer_type',
        'customer_position',
        'lifetime_value',
        'license_number',
        'dob',
        'ssn',
        'update_done',
    ];
    public $timestamps = false;
}
