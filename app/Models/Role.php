<?php

namespace App\Models;

use illuminate\Database\Eloquent\Factories\HasFactory;
use illuminate\Database\Eloquent\Model;

class Role extends Model
{

    use HasFactory;
    // Define any custom attributes or methods here
    protected $fillable = ['name'];

       // Custom method to get users with this role
       public function getUsers()
       {
           return $this->users()->get();
       }
}
