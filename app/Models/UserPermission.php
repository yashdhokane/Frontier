<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $table = 'user_permissions';

    protected $fillable = [
        'user_id',
        'module_id',
        'permission',
    ];

    public function module()
    {
        return $this->belongsTo(PermissionModel::class, 'module_id', 'module_id');
    }

}