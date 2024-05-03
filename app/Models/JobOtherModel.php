<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOtherModel extends Model
{
    use HasFactory;
    protected $table = 'job_other_details';

    protected $fillable = [
        'sign',
        'additional_details',
        'is_complete',
        'job_id',
        'user_id',
    ];
        public $timestamps = false;

}