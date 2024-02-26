<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appliances extends Model
{
    use HasFactory;

      protected $table = 'appliances';

    protected $primaryKey = 'appliance_id';

    protected $fillable = [
        'appliance_name',
        'appliance_details',
        'appliance_type',
    ];


}