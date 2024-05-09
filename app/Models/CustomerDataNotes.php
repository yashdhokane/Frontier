<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDataNotes extends Model
{
    use HasFactory;

    protected $table = 'customer_notes'; // Define the table name

    protected $primaryKey = 'notes_id'; // Set the primary key

    protected $fillable = [
        'job_id',
        'notes',
        'notes_by',
    ];

    public $timestamps = false; // Disable timestamps
}