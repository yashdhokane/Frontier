<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobfields extends Model
{
    use HasFactory;
    protected $table = 'job_fields';
    protected $primaryKey = 'field_id';

    protected $fillable = [
        'field_id','user_id', 'field_name', 'created_by',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}