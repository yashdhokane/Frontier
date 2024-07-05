<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetVehicle extends Model
{
    use HasFactory;
    protected $table = 'fleet_vehicles';
    protected $primaryKey = 'vehicle_id';

    protected $fillable = ['vehicle_description', 'vehicle_summary', 'vehicle_no', 'vehicle_name', 'vehicle_image', 'technician_id', 'created_by', 'updated_by'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }
}