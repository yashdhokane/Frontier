<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobParametersMeta extends Model
{
    use HasFactory;
    protected $table = 'job_parameters_meta';
    protected $primaryKey = 'meta_id';
    protected $fillable = [
        'p_id',
        'p_name',
        'p_value',
    ];
}
