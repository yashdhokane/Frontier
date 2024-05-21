<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickyNotes extends Model
{
    use HasFactory;
    protected $table = 'sticky_notes';
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'user_id', 'note','color_code'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
