<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingTriggerTechnician extends Model
{
    use HasFactory;
    protected $table = 'routing_trigger_technicians';

    // Define the primary key (if it's not the default 'id')
    protected $primaryKey = 'trigger_id';

    // Allow mass assignment for these fields
    protected $fillable = [
        'routing_id',
        'technician_id',
        'days_selected',
        'job_confirmed',
        'parts_available',
        'last_updated',
    ];

    // Define timestamps if not using the default 'created_at' and 'updated_at' columns
    public $timestamps = true;

    /**
     * Relationship: A RoutingTriggerTechnician belongs to a RoutingTrigger
     */
    public function routingTrigger()
    {
        return $this->belongsTo(RoutingTrigger::class, 'routing_id', 'routing_id');
    }

    /**
     * Relationship: A RoutingTriggerTechnician belongs to a Technician
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'id', 'technician_id');
    }
}
