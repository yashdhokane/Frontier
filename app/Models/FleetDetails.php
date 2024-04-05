<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDetails extends Model
{
      

    use HasFactory;
        protected $table = 'fleet_details';

        protected $primaryKey = 'fleet_id';

    protected $fillable = ['fleet_id', 'user_id', 'fleet_key', 'fleet_value'];

  public $timestamps = false;

}