<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteJobTitle extends Model
{
    use HasFactory;
    protected $table = 'site_job_titles';
    protected $primaryKey = 'field_id';
    protected $fillable = ['flag_name', 'created_by', 'updated_by'];
}
