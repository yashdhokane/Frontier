<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlagJob extends Model
{
    use HasFactory;
    protected $table = 'flag_tags';
    protected $primaryKey = 'flag_id';
    protected $fillable = ['flag_name', 'flag_desc', 'flag_icon'];
}
