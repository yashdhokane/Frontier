<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customizer extends Model
{
    use HasFactory;
    public $timestamps = false; // Disable timestamps
    protected $table = 'customizers';
    protected $primaryKey = 'id';

    protected $fillable = ['element_id','position'];
}
