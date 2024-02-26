<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeadSourceCustomer extends Model
{
    use HasFactory;
    protected $table = 'user_lead_source';

    protected $fillable = [
        'source_name',
        'added_by',
        'last_updated_by',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function lastUpdatedByUser()
    {
        return $this->belongsTo(User::class, 'last_updated_by', 'id');
    }
}
