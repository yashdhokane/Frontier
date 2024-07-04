<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutDash extends Model
{
    use HasFactory;
    public $timestamps = false; // Disable timestamps
    protected $table = 'layout_dash';
    protected $primaryKey = 'id';

    protected $fillable = ['layout_name','added_by','updated_by','is_editable'];

}
