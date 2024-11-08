<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = 'vendors';
    protected $primaryKey = 'vendor_id';

    protected $fillable = ['parent_id', 'vendor_name', 'vendor_description', 'vendor_image', 'added_by','updated_by','is_active','address_line_1','address_line_2','city','state','zipcode_id','city_id'];

}
