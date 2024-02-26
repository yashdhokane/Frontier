<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['technician_id', 'hour', 'task_title', 'task_description'];

    public function technician()
    {
        return $this->belongsTo(Technician::class,'Technician_id');
    }
}
