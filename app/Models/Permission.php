<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // Define any custom attributes or methods here
    protected $table ='permissions';
    protected $fillable = ['name', 'description'];

        // Custom method to check if a permission is assigned to a role
    public function isAssignedToRole($roleId)
    {
        return $this->roles()->where('id', $roleId)->exists();
    }
}
