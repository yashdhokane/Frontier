<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentNotes extends Model
{
    use HasFactory;
    protected $table = 'payment_notes';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','payment_id','payment_note','added_by','updated_by','note_read'];
}
