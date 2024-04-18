<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSettings extends Model
{
    use HasFactory;
    protected $table = 'user_settings';
 protected $primaryKey ='setting_id';
    protected $fillable = [
        'setting_id',
        'user_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed',
        'two_factor_email_confirmed',
        'email_notifications',
        'sms_notification',
        'email_verified',
        'email_verify_code',
        'country_id',
        'admin_approval',
        'remember_token',
    ];

    // If you don't have timestamps in your table, set it to false
    public $timestamps = false;
}