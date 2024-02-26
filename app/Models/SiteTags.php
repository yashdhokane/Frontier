<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteTags extends Model
{
    use HasFactory;
    protected $table = 'site_tags';
    protected $primaryKey = 'tag_id';

    protected $fillable = [
        'tag_name', 'created_by','updated_by','count',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}