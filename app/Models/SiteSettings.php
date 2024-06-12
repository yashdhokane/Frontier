<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $table = 'site_settings';
    protected $primaryKey = 'id';
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
        'app_disclaimer',
        'message_on_docs',
        'terms_condition',
        'logo',
        'date_format',
        'time_format',
        'timezone_id',
        'timezone',
        'favicon',
        'allowed_file_types',
        'allowed_file_size',
        'google_map_key',
        'license_number',
    ];
}
