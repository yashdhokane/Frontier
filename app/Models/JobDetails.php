<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetails extends Model
{
    use HasFactory;
    protected $table = 'job_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id',
        'appliance_id',
        'model_number',
        'serial_number',
        'manufacturer_id',
    ];

 public function manufacturername()
{
    return $this->belongsTo(Manufacturer::class, 'manufacturer_id','id' );
}
    public function apliencename()
{
    return $this->belongsTo(Appliances::class, 'appliance_id','appliance_id' );
}

}
