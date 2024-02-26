<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTagIdCategory extends Model
{
    use HasFactory;
    protected $table = 'user_tags_list';
    protected $primaryKey = 'tag_id';

    protected $fillable = [
        'user_id', 'tag_name', 'created_by',
    ];

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}