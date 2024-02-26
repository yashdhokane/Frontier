<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotesCustomer extends Model
{
    use HasFactory;

    protected $table = 'user_notes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'note',
        'added_by',
        'last_updated_by',
    ];
}