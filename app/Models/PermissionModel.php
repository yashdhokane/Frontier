<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    use HasFactory;
    protected $table = 'permission_modules';
    protected $primaryKey = 'module_id';

    protected $fillable = [
        'module_name',
        'module_details',
        'parent_id',
    ];

    public function module()
    {
        return $this->hasOne(UserPermission::class, 'module_id', 'module_id');
    }

}