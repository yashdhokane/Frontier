<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFile extends Model
{
    use HasFactory;
    protected $table = 'job_files';
    protected $primaryKey = 'id';

    protected $fillable = ['job_id','user_id','path','filename','type','size','storage_location'];
}
