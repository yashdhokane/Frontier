<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_category'; // Assuming your table name is 'service_category'

    protected $fillable = [
        'parent_id',
        'category_name',
        'category_image',
        'added_by',
        'updated_by',
    ];
    // Define relationships if needed

}