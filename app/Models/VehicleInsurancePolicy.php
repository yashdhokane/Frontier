<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInsurancePolicy extends Model
{
    use HasFactory;

    protected $table = 'vehicle_insurance_policies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'valid_upto',
        'company',
        'document',
        'premium',
        'cover',
        'vehicle_registration_number',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_id',
    ];
      protected $dates = [
        'valid_upto',
        // Other date fields
    ];

    // Or, if using $casts
    protected $casts = [
        'valid_upto' => 'datetime',
        // Other casts
    ];


    /**
     * Get the vehicle associated with the insurance policy.
     */
    public function vehicle()
    {
        return $this->belongsTo(FleetVehicle::class, 'vehicle_id', 'vehicle_id');
    }
}