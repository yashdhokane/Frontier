<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateTemplatesServices extends Model
{
    use HasFactory;
    protected $table = 'estimate_templates_services';
    protected $primaryKey = 'connection_id';
    protected $fillable = [
        'connection_id',
        'template_id',
        'service_id',
        'description_service',
        'quantity_service',
        'cost_service',
        'price_service',
        'discount_service',
        'tax_service',
    ];

}