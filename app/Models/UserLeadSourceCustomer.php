<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeadSourceCustomer extends Model
{
    use HasFactory;
    protected $table = 'site_lead_source';
    protected $primaryKey = 'source_id';

    protected $fillable = [
        'source_name',
        'added_by',
        'updated_by',
        'count',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function lastUpdatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
