<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolAssign extends Model
{
    use HasFactory;
    protected $table = 'tool_assigned';
    protected $primaryKey = 'p_auto_id';


    // Define fillable fields if needed
    protected $fillable = ['product_id', 'technician_id', 'quantity'];

    public function Product()
    {
        return $this->belongsTo(Tool::class, 'product_id', 'product_id');
    }

    public function Technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }
     public function TechnicianName()
    {
        return $this->hasMany(User::class, 'id', 'technician_id');
    }
}