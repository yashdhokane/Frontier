<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobNoteModel extends Model
{
    use HasFactory;
     protected $table = 'job_notes';
    protected $fillable = [
        'user_id',
        'job_id',
        'note',
        'added_by',
        'updated_by',
        'note_read',
        'source_type', 
    ];
}