<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobParameters extends Model
{
    use HasFactory;
    protected $table = 'job_parameters';
    protected $primaryKey = 'p_id';
    protected $fillable = [
        'p_type',
        'p_name',
        'p_desc',
        'p_active',
        'created_by',
    ];
    public function ParameterMeta()
    {
        return $this->belongsTo(JobParametersMeta::class, 'p_id','p_id');
    }
}
