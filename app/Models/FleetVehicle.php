<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetVehicle extends Model
{
    use HasFactory;
    protected $table = 'fleet_vehicles';
    protected $primaryKey = 'vehicle_id';

 protected $fillable = [
        'vehicle_description',
        'vehicle_summary',
        'vehicle_no',
        'vehicle_name',
        'vehicle_image',
        'technician_id',
        'created_by',
        'updated_by',
        'vin_number',        // Added field
        'make',              // Added field
        'model',             // Added field
        'year',              // Added field
        'color',             // Added field
        'vehicle_weight',    // Added field
        'vehicle_cost',      // Added field
    ];
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }
    public function vehicle()
    {
        return $this->hasOne(VehicleInsurancePolicy::class, 'vehicle_id', 'vehicle_id');
    }
}