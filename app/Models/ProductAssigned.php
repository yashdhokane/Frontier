<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAssigned extends Model
{
    use HasFactory;
    protected $table = 'products_assigned';
    protected $primaryKey = 'p_auto_id';


    // Define fillable fields if needed
    protected $fillable = ['product_id', 'technician_id'];

    public function Product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }

    public function Technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }


}
