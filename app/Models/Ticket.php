<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [

        'subject',
        'status',
        'priority',
        'customer_id',
        'technician_id',
        'description',
          'ticket_number',
       

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'assigned_user_id' is the foreign key
    }


    public function technician()
    {
        return $this->belongsTo(Technician::class, 'technician_id'); // Assuming 'assigned_user_id' is the foreign key
    }
}
