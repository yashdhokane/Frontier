<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAppliances extends Model
{
    use HasFactory;
    protected $table = 'job_appliance';
    protected $primaryKey = 'details_id';

    protected $fillable = [
        'job_id', 'appliance_id'
    ];

    public function Appliances()
    {
        return $this->belongsTo(UserAppliances::class, 'appliance_id', 'appliance_id');
    }

    // public function apliencename()
    // {
    //     return $this->belongsTo(UserAppliances::class, 'appliance_id', 'appliance_id');
    // }
}
