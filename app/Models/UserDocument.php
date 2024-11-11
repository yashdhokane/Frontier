<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $table = 'user_documents';

    // Specify the primary key if it's not `id`
    protected $primaryKey = 'id';

    // Disable auto-incrementing if it's not an integer key
    public $incrementing = true;

    // Define fillable fields to allow mass assignment
    protected $fillable = [
        'user_id',
        'document_type',
        'document_name',
        'file_path',

        'created_at',
        'updated_at',
    ];
}
