<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingTrigger extends Model
{
    use HasFactory;

    protected $table = 'routing_trigger';

    // Define the primary key (if it's not the default 'id')
    protected $primaryKey = 'routing_id';

    // Allow mass assignment for these fields
    protected $fillable = [
        'routing_title',
        'created_by',
        'updated_by',
        'routing_details',
        'routing_cron',
        'routing_cron_date',
        'is_active',
    ];

    // Define timestamps if not using the default 'created_at' and 'updated_at' columns
    public $timestamps = true;

    /**
     * Relationship: A RoutingTrigger can have many technicians through routing_trigger_technicians
     */
    public function technicians()
    {
        return $this->hasMany(RoutingTriggerTechnician::class, 'routing_id', 'routing_id');
    }
}
