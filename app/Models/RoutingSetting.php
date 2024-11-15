<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingSetting extends Model
{
    use HasFactory;
    protected $table = 'routing_settings';

    // Primary key
    protected $primaryKey = 'routing_id';

    // Fillable fields
    protected $fillable = [
        'user_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'routing_details',
        'routing_cron',
        'routing_cron_date',
        'is_active'
    ];

    // Disable timestamps if the table doesn't have created_at and updated_at fields managed by Eloquent
    public $timestamps = false;

    /**
     * Define a one-to-many relationship with RoutingSettingOption
     */
    public function options()
    {
        return $this->hasMany(RoutingSettingOption::class, 'routing_id', 'routing_id');
    }

    public function technicians()
{
    return $this->hasMany(RoutingSettingOption::class, 'routing_id', 'routing_id');
}

}
