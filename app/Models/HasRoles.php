<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

trait HasRoles
{
    public function hasRole($role)
    {
        return $this->role === $role; // Customize this based on your role logic
    }

    // Other role-related methods or attributes can be defined here

    public function isAdmin()
    {
        return $this->role === 'admin'; // Customize this based on your role logic
    }

    public function isTechnician()
    {
        return $this->role === 'technician'; // Customize this based on your role logic
    }
    
    public function isCustomer()
    {
        return $this->role === 'customer'; // Customize this based on your role logic
    }

    public function assignRole($role)
    {
        $this->role = $role;
        $this->save();
    }

    public function removeRole()
    {
        $this->role = null;
        $this->save();
    }

    // You can add other role-related methods or attributes here
}