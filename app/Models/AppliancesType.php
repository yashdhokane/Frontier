<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppliancesType extends Model
{
    use HasFactory;
    protected $table = 'appliance_type';
    protected $primaryKey = 'appliance_type_id';

    protected $fillable = [
        'appliance_name', 'appliance_details','created_by',
    ];
}