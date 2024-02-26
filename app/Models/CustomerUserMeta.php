<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUserMeta extends Model {
    use HasFactory;

    protected $table = 'user_meta';
        protected $primaryKey = 'meta_id';

    protected $fillable = ['user_id', 'meta_key', 'meta_value'];

      public function address()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'user_id');
    }
}

