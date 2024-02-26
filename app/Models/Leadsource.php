<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leadsource extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'user_lead_source';

    protected $fillable = [
        'source_name',
        'user_id',
        'added_by',
        'last_updated_by',
    ];
      
    
    
}
