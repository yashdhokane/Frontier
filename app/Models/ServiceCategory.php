<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;
    protected $table = 'service_category'; // Assuming your table name is 'service_category'

    protected $fillable = [
        'parent_id',
        'category_name',
        'category_image',
        'added_by',
        'updated_by',
    ];
    public function Services()
    {
        return $this->hasMany(Services::class, 'service_category_id');
    }
}