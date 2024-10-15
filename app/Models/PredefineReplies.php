<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefineReplies extends Model
{
    use HasFactory;
    protected $table = 'predefined_replies';
    protected $primaryKey = 'pt_id';

    public $timestamps = false;

    protected $fillable = ['pt_title', 'pt_content','pt_active','pt_date_added'];
}
