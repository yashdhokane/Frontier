<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [     'name',
                                'phone',
                                'designation',
                                'age',
                                'role',
                                'doj',
                                'image',
                                'salary',
                                'email',
                                'password',
                                'role',
                                'task_assign',
                                'status',
                                'lat',
                                'lng',
                        ];

    // Define relationships
    // For example, if a technician belongs to a specific department
    public function users()
    {
        return $this->belongsTo(User::class);
    }

     // For example, if a technician belongs to a specific department
     public function customers()
     {
         return $this->belongsTo(Customer::class);
     }

     public function tickets()
     {
         return $this->hasMany(Ticket::class);
     }

     public function schedules() {
        return $this->hasMany(Schedule::class, 'technician_id');

    }

    
      
    
    public function tasks()
    {
        return $this->hasMany(Task::class, 'Task');
    }
     public function Location()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'id');
    }
	// User.php (assuming this is your User model)




  public function tag()
    {
        return $this->hasOne(UserTagIdCategory::class, 'user_id', 'id');
    }
    
	 public function meta()
    {
        return $this->hasOne(CustomerUserMeta::class, 'user_id', 'id');
    }
    
     public function leadsourcename()
    {
        return $this->hasOne(Leadsource::class, 'user_id', 'id');
    }

     public function Note()
    {
        return $this->hasOne(UserNotesCustomer::class, 'user_id', 'id');
    }
    
}
