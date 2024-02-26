<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUserAddress extends Model {
    use HasFactory;
    protected $table = 'user_address';
    protected $primaryKey = 'address_id';
    protected $fillable = [
        'user_id', 'address_type', 'address_primary', 'address_line1', 'address_line2',
        'city', 'zipcode', 'state_name', 'state_id', 'country_id', 'address_notes',
        'latitude', 'longitude'
    ];

public function cityname(){
    
return $this->hasOne(LocationCity::class, 'city_id', 'city');

}
    public function locationStateName()
    {
        return $this->hasOne(LocationState::class, 'state_id', 'state_id');
    }
}