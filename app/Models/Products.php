<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'product_id';


    // Define fillable fields if needed
    protected $fillable = [
        'product_category_id',
        'product_manu_id',
        'product_code',
        'product_name',
        'product_short',
        'product_description',
        'product_image',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'base_price',
        'discount',
        'tax',
        'total',
        'stock',
        'assigned_to',
    ];
    public function meta()
    {
        return $this->hasOne(ProductMeta::class, 'product_id', 'product_id');
    }

    public function manufacturername() 
    {
        return $this->hasOne(manufacturer::class, 'id', 'product_manu_id');
    }

    public function categoryProduct() 
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

}