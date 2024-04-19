<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProduct extends Model
{
    use HasFactory;
    protected $table = 'job_product_items';
    protected $fillable = [
        'item_id',
        'product_id',
        'job_id',
        'product_description',
        'product_name',
        'base_price',
        'quantity',
        'tax',
        'discount',
        'sub_total',
    ];
    public function product()
    {
        return $this->hasOne(Products::class, 'product_id', 'product_id');
    }
}
