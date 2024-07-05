<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;
    protected $table = 'tools';
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
        'is_featured',
    ];
    public function meta()
    {
        return $this->hasOne(ToolMeta::class, 'product_id', 'product_id');
    }

    public function toolassign()
    {
        return $this->hasMany(ToolAssign::class, 'product_id');
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