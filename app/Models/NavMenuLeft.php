<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NavMenuLeft extends Model
{
    use HasFactory;

    protected $table = 'nav_menus_left';

    // Specify the primary key column (if different from 'id')
    protected $primaryKey = 'menu_id';


    // Disable timestamps
    public $timestamps = false;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'menu_id',
        'parent_id',
        'menu_name',
        'menu_link',
        'module_id',
        'menu_icon',
        'menu_class',
        'order_by',
        'more_link',

    ];

    public function children()
    {
        return $this->hasMany(NavMenuLeft::class, 'parent_id', 'menu_id');
    }
    
    public function userpermission()
    {
        return $this->hasOne(UserPermission::class, 'module_id', 'module_id')
            ->where('user_id', Auth::id()); // Fetch permissions for the authenticated user
    }

}