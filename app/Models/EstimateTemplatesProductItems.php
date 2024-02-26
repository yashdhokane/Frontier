<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateTemplatesProductItems extends Model
{
    use HasFactory;
    protected $table = 'estimate_templates_product_items';
    protected $primaryKey = 'item_id';
    protected $fillable = [
        'product_id',
        'estimate_id',
        'product_name',
        'price_product',
        'quantity_product',
        'tax',
        'discount',
        'cost_product',
        'description_product'
    ];

}