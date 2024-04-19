<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobServices extends Model
{
    use HasFactory;
      protected $table = 'job_service_items'; // Replace 'your_table_name' with the actual table name

    protected $fillable = [
        'item_id',
        'service_id',
        'job_id',
        'service_description',
        'service_name',
        'base_price',
        'quantity',
        'tax',
        'discount',
        'sub_total',
    ];

    public function service()
    {
        return $this->hasOne(Services::class, 'service_id', 'service_id');
    }

}