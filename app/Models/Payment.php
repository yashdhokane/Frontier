<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_id','customer_id','invoice_number','issue_date','due_date','sub_total','discount','discount_type','tax','calculate_tax','total','status','recurring','billing_cycle','billing_interval','billing_frequency','file','file_original_name','note','credit_note','estimate_id','send_status','due_amount','parent_id','invoice_recurring_id','created_by','last_updated_by','bank_account_id','ip_address','quickbooks_invoice_id'];

     public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Assuming 'assigned_customer_id' is the foreign key
    }
     public function JobModel()
    {
        return $this->belongsTo(JobModel::class, 'job_id'); // Assuming 'assigned_job_id' is the foreign key
    }
     public function UserAddress()
    {
        return $this->belongsTo(CustomerUserAddress::class, 'customer_id','user_id'); // Assuming 'assigned_customer_id' is the foreign key
    }

     public function jobserviceinfo()
    {
        return $this->belongsTo(JobServices::class, 'job_id','job_id'); // Assuming 'assigned_job_id' is the foreign key
    }
public function jobDetails()
    {
        return $this->belongsTo(JobDetails::class, 'job_id','job_id'); // Assuming 'assigned_job_id' is the foreign key
    }

    
    public function JobAppliances()
    {
        return $this->belongsTo(JobAppliances::class, 'job_id', 'job_id');
    }

}
