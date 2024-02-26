<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuisnessProfileModel extends Model
{
    use HasFactory;

    protected $table = 'site_settings'; // Replace 'your_table_name' with the actual table name

    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'business_name',
        'address',
        'phone',
        'email',
        'website',
        'legal_name',
        'hvac',
        'description_short',
        'description_long',
        'message_on_docs',
        'terms_condition',
        'logo',
        'date_format',
        'time_format',
        'timezone',
        'favicon',
        'allowed_file_types',
        'allowed_file_size',
        'google_map_key',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
