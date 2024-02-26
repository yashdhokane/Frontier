<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    use HasFactory;
    protected $table = 'user_tags';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tag_id',
        'user_id',
    ];

    // Define relationships if needed

    // Example relationship with Tag model
    public function tags()
    {
        return $this->belongsTo(SiteTags::class, 'tag_id', 'tag_id');
    }
}