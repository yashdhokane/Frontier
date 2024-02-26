<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'tag_ids',
        'close_date',
        'deleted_at',
        'created_at',
        'updated_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'assigned_user_id' is the foreign key
    }
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id'); // Assuming 'assigned_user_id' is the foreign key
    }
    public function addedby()
    {
        return $this->belongsTo(User::class, 'added_by'); // Assuming 'assigned_user_id' is the foreign key
    }
    public function jobDetails()
    {
        return $this->belongsTo(JobDetails::class, 'id', 'job_id'); // Assuming 'assigned_user_id' is the foreign key
    }

    /*   public function techniciannote()
{
    return $this->belongsTo(JobNoteModel::class, 'technician_id', 'user_id');
}
*/

    public function jobserviceinfo()
    {
        return $this->belongsTo(JobServices::class, 'id', 'job_id'); // Assuming 'assigned_job_id' is the foreign key
    }

    public function jobproductinfo()
    {
        return $this->belongsTo(JobProduct::class, 'id', 'job_id'); // Assuming 'assigned_job_id' is the foreign key
    }

    public function JobAssign()
    {
        return $this->belongsTo(JobAssign::class, 'id','job_id'); // Assuming 'assigned_timezone_id' is the foreign key
    }
}
