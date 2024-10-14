<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'status',
        'login',
        'source_id',
        'service_areas',
        'employee_id',
        'is_employee',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function customerdatafetch()
    {
        return $this->hasOne(CustomerData::class, 'user_id', 'id')->latest('updated_at');
    }


    public function Locationareaname()
    {
        return $this->hasOne(LocationServiceArea::class, 'area_id', 'service_areas');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function fleet()
    {
        return $this->hasOne(FleetDetails::class, 'user_id', 'id');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function Location()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'id');
    }
    public function locationStateName()
    {
        return $this->hasOne(LocationState::class, 'state_id', 'state_id');
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
        return $this->hasOne(SiteLeadSource::class, 'source_id', 'source_id');
    }

    public function Note()
    {
        return $this->hasOne(UserNotesCustomer::class, 'user_id', 'id');
    }
    public function tags()
    {
        return $this->belongsToMany(SiteTags::class, 'user_tags', 'user_id', 'tag_id');
    }

    // User.php

    public function homeAddress()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'id')
            ->where('address_type', 'home');
    }

    // Inside the User model
    public function TimeZone()
    {
        return $this->belongsTo(TimeZone::class, 'timezone_id');
    }

    public function userAddress()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'id');
    }
    public function fleetDetails()
    {
        return $this->hasOne(FleetDetails::class, 'user_id', 'id');
    }

    public function schedule_data()
    {
        return $this->hasOne(Schedule::class, 'technician_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'technician_id', 'id');
    }
    
    public function schedulesByRole($role)
    {
        if ($role == 'technician') {
            return $this->hasMany(JobAssign::class, 'technician_id', 'id')
                        ->orderBy('start_date_time', 'desc');
        } elseif ($role == 'customer') {
            return $this->hasMany(JobAssign::class, 'customer_id', 'id')
                        ->orderBy('start_date_time', 'desc'); 
        } else {
            return collect(); 
        }
    }
    
    

    public function LoginHistory()
    {
        return $this->hasOne(UserLoginHistory::class, 'user_id', 'id');
    }

}