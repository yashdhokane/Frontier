<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteJobFields extends Model
{
    use HasFactory;
    protected $table = 'site_job_fields';
    protected $primaryKey = 'field_id';

    protected $fillable = [
        'field_id', 'field_name', 'created_by','updated_by',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}