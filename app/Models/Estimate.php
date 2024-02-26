<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    protected $table = 'estimate_templates';
    protected $primaryKey = 'template_id';
    protected $fillable = [
        'template_name',
        'template_description',
        'template_category_id',
        'template_status',
        'estimate_subtotal',
        'estimate_tax',
        'estimate_discount',
        'estimate_total',
        'added_by',
        'last_updated_by',
    ];
    public function services()
    {
        return $this->hasMany(EstimateTemplatesServices::class, 'template_id');
    }

    public function products()
    {
        return $this->hasMany(EstimateTemplatesProductItems::class, 'estimate_id');
    }

}