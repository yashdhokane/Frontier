<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutDashModuleList extends Model
{
    use HasFactory;
    public $timestamps = false; 
    protected $table = 'layout_dash_modules_list';
    protected $primaryKey = 'module_id';

    protected $fillable = ['module_name','module_code'];
}
