<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationState extends Model
{
    use HasFactory;
    protected $table = 'location_states';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'state_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state_name','state_tax','created_at','updated_at', 'state_code'];

}