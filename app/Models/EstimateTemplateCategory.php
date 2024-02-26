<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateTemplateCategory extends Model
{
    use HasFactory;
    protected $table = 'estimate_template_category';

    protected $fillable = [
        'parent_id',
        'category_name',
        'category_image',
        'added_by',
        'last_updated_by',
    ];

    public function parent()
    {
        return $this->belongsTo(EstimateTemplateCategory::class, 'parent_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

}