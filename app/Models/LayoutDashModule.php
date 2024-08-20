<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutDashModule extends Model
{
    use HasFactory;
    public $timestamps = false; 
    protected $table = 'layout_dash_modules';
    protected $primaryKey = 'id';

    protected $fillable = ['layout_id','module_id','position','is_active','updated_by','columns'];

    public function ModuleList()
    {
        return $this->belongsTo(LayoutDashModuleList::class, 'module_id', 'module_id');
    }
}
