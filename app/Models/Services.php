<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services'; // Assuming your table name is 'services'
    protected $primaryKey = 'service_id';
    protected $fillable = [

        'service_category_id',
        'service_code',
        'service_name',
        'service_description',
        'service_image',
        'created_by',
        'updated_by',
        'service_cost',
        'service_discount',
        'service_tax',
        'service_total',
        'service_time',
        'service_online',
        'service_for',
        'troubleshooting_question1',
        'troubleshooting_question2',
        'service_cost',
        'service_quantity',
        'service_discount',
        'service_tax',
        'service_total',
    ];

    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with the admin who last updated the service
    public function updatedByAdmin()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    // public function servicecategoryid()
    // {
    //     return $this->hasOne(ServiceCategory::class, 'service_category_id', 'id');
    // }
    // Inside the Service model
    public function servicecategoryid()
    {
        return $this->hasOne(ServiceCategory::class, 'service_category_id', 'id');
    }

}
