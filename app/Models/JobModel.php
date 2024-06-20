<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobModel extends Model
{
    use HasFactory;
    protected $table = 'jobs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_code',
        'customer_id',
        'technician_id',
        'job_title',
        'job_type',
        'status',
        'description',
        'priority',
        'service_area_id',
        'type_id',
        'close_date',
        'country_id',
        'added_by',
        'updated_by',
        'address_type',
        'address',
        'city',
        'state',
        'zipcode',
        'city_id',
        'latitude',
        'longitude',
        'tax',
        'discount',
        'gross_total',
        'commission_total',
        'job_field_ids',
        'service_area_id',
        'tag_ids',
        'close_date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];
   

   public function JobTechEvent()
    {
        return $this->hasOne(JobTechEvents::class, 'job_id', 'id');
    }
   public function Customerservice()
    {
        return $this->hasOne(CustomerDataServices::class, 'job_id', 'id');
    }
     public function Customernote()
    {
        return $this->hasOne(CustomerDataNotes::class, 'job_id', 'id');
    }
      public function JobOtherModelData()
    {
        return $this->hasOne(JobOtherModel::class, 'user_id', 'technician_id');
    }

    
      public function Customerdata()
    {
        return $this->hasOne(CustomerData::class, 'user_id', 'customer_id');
    }
public function jobfieldname()
    {
       return $this->hasOne(Jobfields::class, 'field_id', 'job_field_ids');
    }
  public function jobactivity()
    {
       return $this->hasMany(JobActivity::class, 'job_id', 'id');
    }

    public function serviceareaname()
    {
        return $this->hasOne(LocationServiceArea::class, 'area_id', 'service_area_id');
    }

    public function jobassignname()
    {
        return $this->hasOne(JobAssign::class, 'job_id', 'id');
    }

    public function jobserviceinfo()
    {
        return $this->hasOne(JobServices::class, 'job_id', 'id');
    }
    public function jobproductinfo()
    {
        return $this->hasOne(JobProduct::class, 'job_id', 'id');
    }


    public function jobdetailsinfo()
    {
        return $this->hasOne(JobDetails::class, 'job_id', 'id');
    }





    //customer data show  for jobs list page
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'assigned_user_id' is the foreign key
    }

    public function addresscustomer()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'customer_id');
    }
    public function Locationareaname($serviceAreas)
    {
        //dd($serviceAreas);
        // Assuming $serviceAreas is a comma-separated list of area IDs
        $areaIds = explode(',', $serviceAreas);

        // Retrieve all LocationServiceArea records that match any of the area IDs
        $areas = LocationServiceArea::whereIn('area_id', $areaIds)->pluck('area_name')->toArray();

        return $areas;
    }



    //technician data show  for jobs list page
    public function usertechnician()
    {
        return $this->belongsTo(User::class, 'technician_id'); // Assuming 'assigned_user_id' is the foreign key
    }

    public function addresstechnician()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'technician_id');
    }


    //dispatcher data show  for jobs list page
    public function userdispatcher()
    {
        return $this->belongsTo(User::class, 'added_by'); // Assuming 'assigned_user_id' is the foreign key
    }

    public function addressdispatcher()
    {
        return $this->hasOne(CustomerUserAddress::class, 'user_id', 'added_by');
    }



    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function addedby()
    {
        return $this->belongsTo(User::class, 'added_by'); // Assuming 'assigned_user_id' is the foreign key
    }
    /*   public function techniciannote()
  {
      return $this->belongsTo(JobNoteModel::class, 'technician_id', 'user_id');
  }
  */

  public function jobDetails()
    {
        return $this->belongsTo(JobDetails::class, 'id','job_id'); // Assuming 'assigned_user_id' is the foreign key
    }

     public function JobAssign()
    {
        return $this->belongsTo(JobAssign::class, 'id','job_id'); // Assuming 'assigned_job_id' is the foreign key
    }
    
    public function locationStateName()
    {
        return $this->hasMany(LocationState::class, 'state', 'state_code');
    }

    public function JobNote()
    {
        return $this->belongsTo(JobNoteModel::class, 'id', 'job_id');
    }
    public function JobAppliances()
    {
        return $this->belongsTo(JobAppliances::class, 'id', 'job_id');
    }

     public function schedule()
    {
        return $this->hasOne(Schedule::class, 'job_id', 'id');
    }
}