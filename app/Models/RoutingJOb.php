<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutingJOb extends Model
{
    use HasFactory;
    protected $table = 'routing_job_routes';
    protected $primaryKey = 'auto_id';
    protected $fillable = ['user_id', 'created_by', 'updated_by','best_route','short_route','custom_route'];
}
