<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayoutCustomizer extends Model
{
    use HasFactory;
     public $timestamps = false; // Disable timestamps
    protected $table = 'layout_customizers';
    protected $primaryKey = 'id';

    protected $fillable = ['layout_name','added_by','updated_by'];

    public function Customizer()
    {
        return $this->belongsTo(Services::class, 'layout_id');
    }
}
