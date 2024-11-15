<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingSettingOption extends Model
{
    use HasFactory;

    use HasFactory;

    // Table name
    protected $table = 'routing_settings_options';

    // Primary key
    protected $primaryKey = 'option_id';

    // Fillable fields
    protected $fillable = [
        'routing_id',
        'routing_option',
        'routing_value'
    ];

    // Disable timestamps if the table doesn't have created_at and updated_at fields managed by Eloquent
    public $timestamps = false;

    /**
     * Define a belongs-to relationship with RoutingSetting
     */
    public function routingSetting()
    {
        return $this->belongsTo(RoutingSetting::class, 'routing_id', 'routing_id');
    }
}
