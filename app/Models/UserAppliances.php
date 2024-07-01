<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAppliances extends Model
{
    use HasFactory;
    protected $table = 'user_appliances';
    protected $primaryKey = 'appliance_id';

    protected $fillable = [
        'user_id', 'appliance_type_id','manufacturer_id','model_number','serial_number',
    ];

    public function appliance()
    {
        return $this->belongsTo(AppliancesType::class,'appliance_type_id','appliance_type_id');
    }
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class,'manufacturer_id','id');
    }
    public function user()
    {
        return $this->hasMany(User::class,'id','user_id');
    }


}
