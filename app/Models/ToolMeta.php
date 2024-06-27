<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolMeta extends Model
{
    use HasFactory;
    protected $table = 'tool_meta';
    protected $primaryKey = 'meta_id';
    protected $fillable = ['product_id', 'meta_key', 'meta_value'];
}