<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TimeZone;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\JobAppliances;

use App\Models\UserAppliances;

use App\Models\JobNoteModel;
use App\Models\JobProduct;
use App\Models\JobOtherModel; 
use App\Models\UserNotesCustomer;
use App\Models\Event;  
use App\Models\SiteSettings;  
use App\Models\JobTechEvents; 
use App\Models\UserNotification;  

use App\Models\ProductAssigned;
use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\File;

use App\Models\JobModel;
use App\Models\Products;


use App\Models\JobFile;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ApiController extends Controller
{
  
  /* public function user_login(Request $request)
{
    // Find user by email
    $user = User::where('email', '=', $request->email)->first();

    // Check if user exists
    if ($user) {
        // Check if password matches
        if (Hash::check($request->password, $user->password)) {
            // Check if user's role is "technician"
            if ($user->role === 'technician') {
                  Auth::login($user);
                
                return response()->json(['status' => true, 'data' => $user, 'message' => 'Login Successful'],200);
            } else {
                return response()->json(['status' => false, 'message' => 'Unauthorized access'],201);
            }
        } else {
            // Password does not match
            return response()->json(['status' => false, 'message' => 'Invalid email or password'],201);
        }
    } else {
        // User not found
        return response()->json(['status' => false, 'message' => 'Invalid email or password'],201);
    }
}*/
public function user_login(Request $request)
{
    // Validate request data
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Find user by email
    $user = User::where('email', $request->email)->first();

    // Check if user exists
    if (!$user) {
        return response()->json(['status' => false, 'message' => 'Invalid email or password'], 401);
    }

    // Check if password matches
    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['status' => false, 'message' => 'Invalid email or password'], 401);
    }

    // Check if user's role is "technician"
    if ($user->role !== 'technician') {
        return response()->json(['status' => false, 'message' => 'Unauthorized access'], 401);
    }

    // Update user's last login timestamp
    $user->update(['last_login' => now()]);

    // Store user's timezone information in session
    $timezoneId = $user->timezone_id;
    $timezone = TimeZone::where('timezone_id', $timezoneId)->first();
    Session::put('timezone_id', $timezoneId);
    Session::put('timezone_name', $timezone->timezone_name);
    Session::put('time_interval', $timezone->time_interval);

    // Log user login history
    $ipAddress = $request->ip();
    DB::table('user_login_history')->updateOrInsert(
        ['user_id' => $user->id],
        ['ip_address' => $ipAddress, 'login' => now()]
    );

    // Authenticate user
    Auth::login($user);

    return response()->json([
        'status' => true,
        'data' => $user,
        'timezone_id' => $timezoneId,
        'timezone_name' => $timezone->timezone_name,
        'time_interval' => $timezone->time_interval,
        'message' => 'Login Successful'
    ], 200);
}

    public function getNotification(Request $request)
    {
        $technicianId = $request->input('technician_id');

        if (!$technicianId) {
            return response()->json(['status' => false, 'message' => 'Technician ID is required'], 500);
        }

        $notifications = UserNotification::with('notice')->where('user_id', $technicianId)->get();

        if ($notifications->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No notifications found'], 201);
        }

        return response()->json(['status' => true, 'message' => 'Notifications found', 'data' => $notifications], 200);
    }


public function reset_password(Request $request)
{
    $request->validate([
        // 'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)
        ->where('role', 'technician')
        ->first();

    if (!$user) {
        return response()->json(['status' => false, 'error' => 'User not found.'], 201);
    }

    $newPassword = Str::random(10);

    $user->password = Hash::make($newPassword);
    $user->save();

    $companyName = "Frontier"; 

    Mail::send('emailtouser.forget_password_email', ['newPassword' => $newPassword, 'companyName' => $companyName], function ($message) use ($request) {
        $message->to($request->email)->subject('Updated Password');
        $message->from('yashdhokane890@gmail.com', 'Admin');
    });

    return response()->json(['status' => true, 'message' => 'Password reset successful. Check your email for the new password.'], 200);
}




public function jobdetailsfetch(Request $request)
{
    $jobId = $request->input('job_id');

    // Check if the specified job_id exists in both JobModel and schedule
    $job = JobModel::with('user','JobNote','JobOtherModelData', 'JobAssign', 'addresscustomer','jobproductinfohasmany.producthasmany', 'jobserviceinfohasmany.servicehasmany','usertechnician', 'jobdetailsinfo.apliencename','jobdetailsinfo.manufacturername','jobdetailsinfo1.Appliances','jobdetailsinfo1.Appliances.appliance','jobdetailsinfo1.Appliances.manufacturer')
        ->where('id', $jobId)
        ->whereExists(function ($query) use ($jobId) {
            $query->select(DB::raw(1))
                ->from('schedule')
                ->whereRaw('schedule.job_id = jobs.id')
                ->where('jobs.id', $jobId);
        })
        ->first();
       
       $html = '<div>';

        $html .= '
            <h3>Job Details - ' .'#'. ($job->id ?? '') . '</h3>
            <p>Phone No.: ' . ($job->user->mobile ?? '') . '</p>
            <p>Email: ' . ($job->user->email ?? '') . '</p>
            <p>Address: ' . ($job->addresscustomer->address_line1 ?? '') . ', ' . ($job->addresscustomer->city ?? '') . ', ' . ($job->addresscustomer->state_name ?? '') . ', ' . ($job->addresscustomer->zipcode ?? '') . '</p>
            <p>View Location: </p>';
  

    $html .= '
        <p>Job: ' . ($job->job_title ?? '') . '</p>
 <p>Appliance: ' . 
        ($job->jobdetailsinfo1->appliances->appliance->appliance_name ?? '') . ' / ' . 
        ($job->jobdetailsinfo1->appliances->manufacturer->manufacturer_name ?? '') . ' / ' . 
        ($job->jobdetailsinfo1->appliances->model_number ?? '') . ' / ' . 
        ($job->jobdetailsinfo1->appliances->serial_number ?? '') . 
    '</p>
        <p>Warranty: ' . ($job->warranty_type ?? '') . '</p>
        <p>Priority: ' . ($job->priority ?? '') . '</p>';
            if (!$job->JobOtherModelData || !($job->JobOtherModelData->tech_completed == "no" && $job->JobOtherModelData->customer_signature == null)) {
  $html .= '
                <p>Job is complete</p>';
            $html .= '<p>Schedule: ' . ($job->JobOtherModelData->job_schedule ? Carbon::parse($job->JobOtherModelData->job_schedule)->format('m-d-y h:i A') : '') . '</p>';
            $html .= '<p>Enroute: ' . ($job->JobOtherModelData->job_enroute ? Carbon::parse($job->JobOtherModelData->job_enroute)->format('m-d-y h:i A') : '') . '</p>';
            $html .= '<p>Start: ' . ($job->JobOtherModelData->job_start ? Carbon::parse($job->JobOtherModelData->job_start)->format('m-d-y h:i A') : '') . '</p>';
            $html .= '<p>Finish: ' . ($job->JobOtherModelData->job_end ? Carbon::parse($job->JobOtherModelData->job_end)->format('m-d-y h:i A') : '') . '</p>';
            $html .= '<p>Repair Complete: ' . ($job->JobOtherModelData->tech_completed ?? '') . '</p>';
            $html .= '<p>Additional Details: ' . ($job->JobOtherModelData->additional_details ?? '') . '</p>';
            $html .= '<p>Admin\'s Remark: ' . ($job->JobOtherModelData->closed_job_comment ?? '') . '</p>';
  }
       $html .= '  <div>
            <h4>Services & Parts</h4>';

    foreach ($job->jobserviceinfohasmany as $serviceInfo) {
        foreach ($serviceInfo->servicehasmany as $service) {
            $html .= '<p>' . ($service->service_name ?? '') . '</p>';
        }
    }

    foreach ($job->jobproductinfohasmany as $productInfo) {
        foreach ($productInfo->producthasmany as $product) {
            $html .= '<p>' . ($product->product_name ?? '') . '</p>';
        }
    }

    $html .= '
        </div>
        <p>Note For Technician: ' . ($job->JobNote->note ?? '') . '</p>
    </div>';


    return response()->json(['status' => true, 'html' => $html,'data' => $job, 'message' => 'The specified job is available in both JobModel and schedule'], 200);
}


public function getTechnicianJobs(Request $request)
{
    $userId = $request->input('user_id');

   $timezone_name = Session::get('timezone_name');

    $currentDate = Carbon::now($timezone_name)->toDateString();
   // dd($currentDate);
    // Check if there are any jobs for the specified technician (user_id) with the current date
    $jobs = JobModel::with('user','JobNote','JobOtherModelData', 'JobAssign', 'addresscustomer','jobproductinfohasmany.producthasmany', 'jobserviceinfohasmany.servicehasmany','usertechnician', 'jobdetailsinfo.apliencename','jobdetailsinfo.manufacturername')
         ->where('technician_id', $userId)    
    ->whereExists(function ($query) use ($currentDate) {
             $query->select(DB::raw(1))
            ->from('schedule')
            ->whereRaw('schedule.job_id = jobs.id')
            ->whereDate('schedule.start_date_time', $currentDate);
    })
    ->get();

    

    if ($jobs->isEmpty()) {
        return response()->json(['status' => false,  'message' => 'Today no jobs are available'],201);
    }

    return response()->json(['status' => true, 'data' => $jobs , 'message' => 'Today jobs are available'],200);
}

public function getCustomerHistory(Request $request)
{
    $userId = $request->input('user_id');

    // Retrieve customer history where user_id matches customer_id in JobModel
    $customerHistory = JobModel::with('usertechnician', 'JobOtherModelData')
                                ->where('customer_id', $userId)
                                ->get();

    if ($customerHistory->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'No customer history found for the specified user'], 201);
    }

    // Prepare HTML output
    $html = '<h3>Customer History</h3>';
    $html .= '<ul>';
    
    foreach ($customerHistory as $index => $history) {
        $html .= '<li>';
        $html .= 'Sr No: ' . ($index + 1) . '<br>';
        $html .= 'Technician: ' . $history->usertechnician->name . '<br>';
        $html .= 'Job Schedule: ' . $history->JobOtherModelData->job_schedule . '<br>';
        $html .= 'Description: ' . $history->description . '<br>';
        $html .= '</li>';
    }

    $html .= '</ul>';

    return response()->json(['status' => true, 'html' => $html, 'data' => $customerHistory], 200);
}



public function getTechnicianJobsHistory(Request $request)
{
    // Get user_id and date from the request
    $userId = $request->input('user_id');
    $date = $request->input('date');

    // Check if the date is provided
    if (!$date) {
        // If the date is missing, return a 400 Bad Request response
        return response()->json(['status' => false, 'message' => 'Date is required'], 201);
    }

    // Parse and format the date using Carbon
    try {
        $formattedDate = Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Invalid date format'], 201);
    }

    // Filter the jobs by technician_id and the specified date
    $jobs = JobModel::with('user','JobNote', 'JobAssign', 'addresscustomer', 'usertechnician', 'jobdetailsinfo.manufacturername')
     ->where('technician_id', $userId)
    ->whereExists(function ($query) use ($formattedDate) {
        $query->select(DB::raw(1))
            ->from('schedule')
            ->whereRaw('schedule.job_id = jobs.id')
            ->whereDate('schedule.start_date_time', $formattedDate);
    })
    ->get();
        //->where('technician_id', $userId)
        // ->whereDate('created_at', $formattedDate)
        // ->get();

    if ($jobs->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'No jobs available for the specified date'], 201);
    }

    return response()->json(['status' => true, 'data' => $jobs , 'message' => 'History jobs are available'],200);
}


public function getcustomerJobsHistory(Request $request)
    {
        $userId = $request->input('user_id');
        
       // dd($currentDate);
        // Check if there are any jobs for the specified technician (user_id) with the current date
        $jobs = JobModel::with('user')->where('customer_id', $userId)
                    ->get();

        if ($jobs->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Today no  jobs are available '], 201);
        }

        return response()->json(['status' => true, 'data' => $jobs],200);
    }

// public function jobfileUploadByTechnician(Request $request)
// {
//     // Validate the incoming request
//     $request->validate([
//         'job_id' => 'required',
//         'user_id' => 'required',
//         'attachments.*' => 'required|', // Adjust max file size and allowed mime types as needed
//     ]);

//     $userId = $request->input('user_id');
//     $jobId = $request->input('job_id');

//     // Process file uploads
//     foreach ($request->file('attachments') as $uploadedFile) {
//         // Check if the file upload was successful
//         if ($uploadedFile->isValid()) {
//             // Generate a unique filename
//             $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
//             $imageName1 = $uploadedFile->getClientOriginalName();

//             // Construct the full path for the directory
//             $directoryPath = public_path('images/users/' . $userId);

//             // Ensure the directory exists; if not, create it
//             if (!File::exists($directoryPath)) {
//                 File::makeDirectory($directoryPath, 0777, true);
//             }

//             // Move the uploaded file to the unique directory
//             if ($uploadedFile->move($directoryPath, $imageName1)) {
//                 // Create a new instance of JobFile model
//                 $file = new JobFile();
//                 $file->job_id = $jobId;
//                 $file->user_id = $userId;
//                 $file->filename = $imageName1;
//                 $file->path = $directoryPath;
//                 $file->type = $uploadedFile->getClientMimeType();
//                 $file->storage_location = 'local'; // Assuming storage location is local
//              } $file->save();
                
//                 // You can return a response inside the loop for each uploaded file
//                 return response()->json(['message' => 'File uploaded successfully', 'file' => $file], 201);
//             }
//         }
  

//     // If you want to return a single response after all files are uploaded, you can move this line outside the loop
//     return response()->json(['message' => 'All files uploaded successfully'], 201);
// }
// }


//oldfileupload
//   public function jobfileUploadByTechnician(Request $request)
//     {
//         // Validate the incoming request
//         $request->validate([
//             'job_id' => 'required|',
//             'user_id' => 'required|',
         
//         ]);

//     $jobId = $request->input('job_id');
//         $userId = $request->input('user_id');

    

//     // Create a new instance of UserFiles model
//     $file = new jobFile();
  
//     // Process file upload
//     if ($request->hasFile('attachment')) {
//         $uploadedFile = $request->file('attachment');

//         // Check if the file upload was successful
//         if ($uploadedFile->isValid()) {
//             // Generate a unique filename
//             $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
//             $imageName1 = $uploadedFile->getClientOriginalName();

//             // Construct the full path for the directory
//             $directoryPath = public_path('images/users/' . $userId);

//             // Ensure the directory exists; if not, create it
//             if (!File::exists($directoryPath)) {
//                 File::makeDirectory($directoryPath, 0777, true);
//             }

//             // Move the uploaded file to the unique directory
//             if ($uploadedFile->move($directoryPath, $imageName1)) {
//                 // Save file details to the database
//                 $file->user_id = $userId;
//                                 $file->job_id = $jobId;

                
//                 $file->filename = $imageName1;
//                 $file->path = $directoryPath;
//                 $file->type = $uploadedFile->getClientMimeType();
//                // $file->size = $uploadedFile->getSize(); // Add file size
//                 $file->storage_location = 'local'; // Assuming storage location is local
//             }
//                 $file->save();

//                   return response()->json(['message' => 'File uploaded successfully', 'file' => $file], 201);
//     }}}

/*public function jobfileUploadByTechnician(Request $request)
{
    $request->validate([
        'job_id' => 'required',
        'user_id' => 'required',
        'product_ids' => 'required',
        'attachment' => 'required|file',
        'note' => 'required',
        'sign' => 'required',
        'additional_details' => 'required',
        'is_complete' => 'required',
    ]);

    $jobId = $request->input('job_id');
    $userId = $request->input('user_id');
    $productIds = $request->input('product_ids');
    $note = $request->input('note');
    $sign = $request->input('sign');
    $additionalDetails = $request->input('additional_details');
    $isComplete = $request->input('is_complete');

    // foreach ($productIds as $productId) {
        $product = Products::where('product_id', $productIds)->first();

        if ($product) {
            $jobProduct = new JobProduct();
            $jobProduct->job_id = $jobId;
            $jobProduct->product_id = $product->product_id;
            $jobProduct->product_description = $product->product_description;
            $jobProduct->product_name = $product->product_name;
            $jobProduct->base_price = $product->base_price;
            $jobProduct->quantity = $product->quantity;
            $jobProduct->tax = $product->tax;
            $jobProduct->sub_total = $product->base_price * $product->quantity;
            $jobProduct->save();
        }
    // }

    $file = new jobFile();

    if ($request->hasFile('attachment')) {
        $uploadedFile = $request->file('attachment');

        if ($uploadedFile->isValid()) {
            $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $imageName1 = $uploadedFile->getClientOriginalName();
            $directoryPath = public_path('images/users/' . $userId);

            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true);
            }

            if ($uploadedFile->move($directoryPath, $imageName1)) {
                $file->user_id = $userId;
                $file->job_id = $jobId;
                $file->filename = $imageName1;
                $file->path = $directoryPath;
                $file->type = $uploadedFile->getClientMimeType();
                $file->storage_location = 'local';
                $file->save();
            }
        }
    }

    // Store additional details in JobOtherModel
    $jobOther = new JobOtherModel();
    $jobOther->user_id = $userId;
    $jobOther->job_id = $jobId;
    $jobOther->sign = $sign;
    $jobOther->additional_details = $additionalDetails;
    $jobOther->is_complete = $isComplete;
    $jobOther->save();

    // Store the note in JobNoteModel if provided
    if (!empty($note)) {
        $jobNote = new JobNoteModel();
        $jobNote->user_id = $userId;
        $jobNote->job_id = $jobId;
        $jobNote->note = $note;
        $jobNote->added_by = $userId;
        $jobNote->updated_by = $userId;
        $jobNote->save();
    }

    return response()->json(['status' => true, 'message' => 'File uploaded successfully', 'data' => $file], 200);
}
*/

//  public function jobfileUploadByTechnician(Request $request)
//     {
//         try {
//             // Validate input data
//             $request->validate([
//                 'job_id' => 'required|integer',
//                 'user_id' => 'required|integer',
//                 'product_ids' => 'required|',
//                 //'product_ids.*' => 'integer', // Validate each product_id as an integer
//                 // 'attachment' => 'required|array',
//                 // 'attachment.*' => 'required|string', // Validate base64 encoded strings
//                 'note' => 'required|string',
//                 //'sign' => 'required|',
//                 'additional_details' => 'required|string',
//                 'is_complete' => 'required|string',
//                  'tech_completed' => 'required|string',

                
//             ]);
//             $tech_completed=$request->input('is_complete');
//             $jobId = $request->input('job_id');
//             $userId = $request->input('user_id');
//             $productIdsString = $request->input('product_ids');
//             $note = $request->input('note');
//             $sign = $request->input('sign');
//             $additionalDetails = $request->input('additional_details');
//             $isComplete = $request->input('tech_completed');
//             //dd($productIds);
//             // Save product details for each product ID
//             $productIdsArray = explode(',', $productIdsString);

//             // Save product details for each product ID
//             foreach ($productIdsArray as $productId) {
//                 // Fetch the product details from the Products model
//                 $product = Products::where('product_id', $productId)->first();

//                 if ($product) {
//                     // Create and save a new JobProduct instance
//                     $jobProduct = new JobProduct();
//                     $jobProduct->job_id = $jobId;
//                     $jobProduct->product_id = $product->product_id;
//                     $jobProduct->product_description = $product->product_description;
//                     $jobProduct->product_name = $product->product_name;
//                     $jobProduct->base_price = $product->base_price;
//                     $jobProduct->quantity = 1; // Assuming quantity is always 1 for each product
//                     $jobProduct->tax = $product->tax;
//                     $jobProduct->discount = $product->discount;

//                     $jobProduct->sub_total = $product->base_price * $jobProduct->quantity;
//                     $jobProduct->save();
//                 } else {
//                     // Handle case where product is not found
//                     throw new \Exception("Product not found: $productId");
//                     }
//             }

//             // Handle file uploads
//             $directoryPath = public_path('images/users/' . $userId);

//             if (!File::exists($directoryPath)) {
//                 File::makeDirectory($directoryPath, 0777, true);
//             }

//            /* foreach ($request->attachment as $image) {
//                 // Extract the base64 string and decode it
//                 if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
//                     $data = substr($image, strpos($image, ',') + 1);
//                     $data = base64_decode($data);
//                     if ($data === false) {
//                         throw new \Exception('base64_decode failed');
//                     }
//                     $extension = $type[1]; // Extract the extension from the regex match
//                     $imageName = uniqid('IMG') . '.' . $extension;
//                     $filePath = $directoryPath . '/' . $imageName;

//                     // Save the file to the directory
//                     if (file_put_contents($filePath, $data)) {
//                         // Create and save job file record
//                         $file = new JobFile();
//                         $file->user_id = $userId;
//                         $file->job_id = $jobId;
//                         $file->filename = $imageName;
//                         $file->path = $filePath;
//                         $file->type = $extension;
//                         $file->storage_location = 'local';
//                         $file->save();
//                     } else {
//                         // Handle file save error
//                         throw new \Exception("Failed to save file.");
//                     }
//                 } else {
//                     throw new \Exception("Invalid base64 data");
//                 }
//             }
//      */
//        if ($request->has('attachment') && is_array($request->attachment)) {
//             foreach ($request->attachment as $image) {
//                 if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
//                     $data = substr($image, strpos($image, ',') + 1);
//                     $data = base64_decode($data);
//                     if ($data === false) {
//                         throw new \Exception('base64_decode failed');
//                     }
//                     $extension = $type[1];
//                     $imageName = uniqid('IMG') . '.' . $extension;
//                     $filePath = $directoryPath . '/' . $imageName;

//                     if (file_put_contents($filePath, $data)) {
//                         $file = new JobFile();
//                         $file->user_id = $userId;
//                         $file->job_id = $jobId;
//                         $file->filename = $imageName;
//                         $file->path = $filePath;
//                         $file->type = $extension;
//                         $file->storage_location = 'local';
//                         $file->save();
//                     } else {
//                         throw new \Exception("Failed to save file.");
//                     }
//                 } else {
//                     throw new \Exception("Invalid base64 data");
//                 }
//             }
//         }


//             $event = JobTechEvents::where('job_id', $jobId)->first();
//             $customer = JobModel::where('id', $jobId)->first();


//             if ($event) {
//                 // Define the directory path for storing images
//                 $directoryPath = public_path('images/users/' . $customer->customer_id);

//                 // Create the directory if it doesn't exist
//                 if (!File::exists($directoryPath)) {
//                     File::makeDirectory($directoryPath, 0777, true);
//                 }

//                 if (!empty($request->sign)) {
//                     $sign = $request->sign; // Assuming $sign is coming from the request

//                     // Extract the base64 string and decode it
//                     if (preg_match('/^data:image\/(\w+);base64,/', $sign, $type)) {
//                         $data = substr($sign, strpos($sign, ',') + 1);
//                         $data = base64_decode($data);
//                         if ($data === false) {
//                             throw new \Exception('base64_decode failed');
//                         }
//                         $extension = $type[1]; // Extract the extension from the regex match
//                         $signName = uniqid('SIGN') . '.' . $extension;
//                         $signPath = $directoryPath . '/' . $signName;

//                         // Save the sign file to the directory
//                         if (file_put_contents($signPath, $data)) {
//                             // Update the event with the new details
//                             // $event->user_id = $userId;
//                             // $event->job_id = $jobId;
//                             $event->customer_signature = $signName;
//                             // $event->sign_path = $signPath; // Store sign file path if needed
//                             $event->additional_details = $additionalDetails;
//                             $event->is_repair_complete = $isComplete;
//                             $event->tech_completed = $tech_completed;

                          


//                             // Send notice about job completion

//                             // Save the event
//                             $event->save();
//                         } else {
//                             throw new \Exception("Failed to save sign file.");
//                         }
//                     } else {
//                         throw new \Exception("Invalid base64 data for sign");
//                     }
//                 }
//             } else {
//                 throw new \Exception("Event not found for the given job ID.");
//             }

//             // Store the note in JobNoteModel if provided
//             if (!empty($note)) {
//                 $jobNote = new JobNoteModel();
//                 $jobNote->user_id = $userId;
//                 $jobNote->job_id = $jobId;
//                 $jobNote->note = $note;
//                 $jobNote->added_by = $userId;
//                 $jobNote->source_type = 'app';

//                 $jobNote->updated_by = $userId;
//                 $jobNote->save();
//             }

//             return response()->json(['status' => true, 'message' => 'Files and products uploaded successfully'], 200);
//         } catch (\Exception $e) {
//             return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
//         }
//     }


public function jobfileUploadByTechnician(Request $request)
{
    try {
        // Validate input data
        $request->validate([
            'job_id' => 'integer|nullable',
            'user_id' => 'integer|nullable',
            'product_ids' => 'string|nullable',
            'note' => 'string|nullable',
            'sign' => 'string|nullable',
            'additional_details' => 'string|nullable',
            'is_complete' => 'string|nullable',
            'tech_completed' => 'string|nullable',
            'attachment' => 'array|nullable',
            'attachment.*' => 'string|nullable', // Validate base64 encoded strings
        ]);

        $jobId = $request->input('job_id');
        $userId = $request->input('user_id');
        $productIdsString = $request->input('product_ids');
        $note = $request->input('note');
        $sign = $request->input('sign');
        $additionalDetails = $request->input('additional_details');
        $isComplete = $request->input('is_complete');
        $techCompleted = $request->input('tech_completed');

        // Process product IDs if provided
        if ($productIdsString) {
            $productIdsArray = explode(',', $productIdsString);

            foreach ($productIdsArray as $productId) {
                $product = Products::where('product_id', $productId)->first();

                if ($product) {
                    $jobProduct = new JobProduct();
                    $jobProduct->job_id = $jobId;
                    $jobProduct->product_id = $product->product_id;
                    $jobProduct->product_description = $product->product_description;
                    $jobProduct->product_name = $product->product_name;
                    $jobProduct->base_price = $product->base_price;
                    $jobProduct->quantity = 1;
                    $jobProduct->tax = $product->tax;
                    $jobProduct->discount = $product->discount;
                    $jobProduct->sub_total = $product->base_price * $jobProduct->quantity;
                    $jobProduct->save();
                } else {
                    throw new \Exception("Product not found: $productId");
                }
            }
        }

        // Handle file uploads if provided
        if ($userId && $request->has('attachment') && is_array($request->attachment)) {
            $directoryPath = public_path('images/users/' . $userId);

            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true);
            }

            foreach ($request->attachment as $image) {
                if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
                    $data = substr($image, strpos($image, ',') + 1);
                    $data = base64_decode($data);
                    if ($data === false) {
                        throw new \Exception('base64_decode failed');
                    }
                    $extension = $type[1];
                    $imageName = uniqid('IMG') . '.' . $extension;
                    $filePath = $directoryPath . '/' . $imageName;

                    if (file_put_contents($filePath, $data)) {
                        $file = new JobFile();
                        $file->user_id = $userId;
                        $file->job_id = $jobId;
                        $file->filename = $imageName;
                        $file->path = $filePath;
                        $file->type = $extension;
                        $file->storage_location = 'local';
                        $file->save();
                    } else {
                        throw new \Exception("Failed to save file.");
                    }
                } else {
                    throw new \Exception("Invalid base64 data");
                }
            }
        }

        // Process job tech events if jobId is provided
        if ($jobId) {
            $event = JobTechEvents::where('job_id', $jobId)->first();
            $customer = JobModel::where('id', $jobId)->first();

            if ($event) {
                $directoryPath = public_path('images/users/' . $customer->customer_id);

                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                if ($sign) {
                    if (preg_match('/^data:image\/(\w+);base64,/', $sign, $type)) {
                        $data = substr($sign, strpos($sign, ',') + 1);
                        $data = base64_decode($data);
                        if ($data === false) {
                            throw new \Exception('base64_decode failed');
                        }
                        $extension = $type[1];
                        $signName = uniqid('SIGN') . '.' . $extension;
                        $signPath = $directoryPath . '/' . $signName;

                        if (file_put_contents($signPath, $data)) {
                            $event->customer_signature = $signName;
                            $event->additional_details = $additionalDetails;
                            $event->is_repair_complete = $isComplete;
                            $event->tech_completed = $techCompleted;
                            $event->save();
                        } else {
                            throw new \Exception("Failed to save sign file.");
                        }
                    } else {
                        throw new \Exception("Invalid base64 data for sign");
                    }
                } else {
                    $event->additional_details = $additionalDetails;
                    $event->is_repair_complete = $isComplete;
                    $event->tech_completed = $techCompleted;
                    $event->save();
                }
            } else {
                throw new \Exception("Event not found for the given job ID.");
            }
        }

        // Store the note in JobNoteModel if provided
        if ($note) {
            $jobNote = new JobNoteModel();
            $jobNote->user_id = $userId;
            $jobNote->job_id = $jobId;
            $jobNote->note = $note;
            $jobNote->added_by = $userId;
            $jobNote->source_type = 'app';
            $jobNote->updated_by = $userId;
            $jobNote->save();
        }

        return response()->json(['status' => true, 'message' => 'Files and products uploaded successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
    }
}




   public function getPartsByTechnicianId(Request $request)
{
    // Retrieve the technician ID from the request
    $technicianId = $request->input('technician_id');

    // Retrieve the ProductAssigned record by technician ID
    $productAssigned = ProductAssigned::with('Product')->where('technician_id', $technicianId)->get();
    $productAssigned = ProductAssigned::with(['Product' => function($query) {
            $query->where('is_featured', 'yes');
        }])->whereHas('Product', function($query) {
            $query->where('is_featured', 'yes');
        })->where('technician_id', $technicianId)->get();
    // Check if any ProductAssigned record found
    if ($productAssigned->isEmpty()) {
        // If no ProductAssigned record found, return a response with status false and error message
        return response()->json(['status' => false, 'message' => 'No parts assigned to the specified technician'], 201);
    }

    // If ProductAssigned record(s) found, return a response with status true and the parts data
    return response()->json(['status' => true, 'parts' => $productAssigned],200);
}



public function technicianLogout(Request $request)
{
    auth()->logout();
   // $request->session()->invalidate();
    return response()->json(['status' => true,'message' => 'Technician logged out successfully'], 200);
}
public function updateTechnicianProfile(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'technician_id' => 'required',
        'name' => 'required|string',
        'email' => 'required|email',
    ]);

    try {
        // Find the technician by ID
        $technician = User::where('role','technician')->findOrFail($request->technician_id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Return a response with status false and error message if technician is not found
        return response()->json(['status' => false, 'message' => 'Technician not found'], 201);
    }

    // Update technician's name and email
    $technician->name = $request->name;
    $technician->email = $request->email;
    $technician->save();

    // Assuming the update is successful
    return response()->json(['status' => true, 'message' => 'Technician profile updated successfully', 'technician' => $technician], 200);
}


 public function calculateJobStatusPercentage(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'technician_id' => 'required',
        ]);

        $technicianId = $request->technician_id;

        // Get the total number of jobs for the technician
        $totalJobs = JobModel::where('technician_id', $technicianId)->count();

        // Get the number of closed jobs for the technician
        $closedJobs = JobModel::where('technician_id', $technicianId)
                     ->where('status', 'closed')
                        ->count();

        // Get the number of open jobs for the technician
        $openJobs = JobModel::where('technician_id', $technicianId)
                      ->where('status', 'open')
                      ->count();

        // Calculate the percentage of closed jobs
        $closedPercentage = $totalJobs > 0 ? ($closedJobs / $totalJobs) * 100 : 0;

        // Calculate the percentage of open jobs
        $openPercentage = $totalJobs > 0 ? ($openJobs / $totalJobs) * 100 : 0;

        // Calculate present and absent days
        $technician = User::find($technicianId);
        if (!$technician) {
            return response()->json(['status' => false, 'message' => 'Technician not found'], 404);
        }

        $createdAt = $technician->created_at;
        $today = Carbon::now();

        // Calculate the number of days from created_at to today, excluding only Sundays
        $presentDays = $this->calculateWorkingDays($createdAt, $today);

        // Get the number of days the technician was marked as absent in the Event model
        $absentDays = Event::where('technician_id', $technicianId)
                        //  ->where('status', 'absent') // Assuming you have a status field to indicate absence
                          ->count();

        // Calculate the present days by subtracting absent days from total working days
        $presentDays -= $absentDays;

        // Return the calculated percentages and presence/absence data
        return response()->json([
            'status' => true,
            'closed_percentage' => $closedPercentage,
            'open_percentage' => $openPercentage,
            'total_jobs' => $totalJobs,
            'closed_jobs' => $closedJobs,
            'open_jobs' => $openJobs,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
        ], 200);
    }

    private function calculateWorkingDays(Carbon $startDate, Carbon $endDate)
    {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }


  public function get_products(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'product_name' => 'required|',
    ]);

    // Retrieve the product name from the request
    $productName = $request->input('product_name');

    // Search for products containing the product name using the "like" operator
    $products = Products::where('product_name', 'like', '%' . $productName . '%')->get();

    // Check if any products were found
    if ($products->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'No product found'], 201);
    }

    // Return the found products
    return response()->json(['status' => true, 'message' => 'Product name found', 'data' => $products], 200);
}
  


 /* public function updateEnroute(Request $request)
    {
        $data = $request->validate([
            'job_id' => 'required|',
            'job_enroute' => 'required|',
        ]);

        $event = JobTechEvents::where('job_id', $data['job_id'])->first();
        if ($event) {
            $event->job_enroute = $data['job_enroute'];
           // $event->enroute_time =Carbon::now()->format('H:i:s');

            $event->save();
            return response()->json(['status' => true, 'data' => $event], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'Job not found'], 200);
        }
    } */
public function updateEnroute(Request $request)
    {
        $data = $request->validate([
            'job_id' => 'required|',
            'job_enroute' => 'required|',
        ]);
        //  $modifyDateTime = app('modifyDateTime');
         //   $time_interval = Session::get('time_interval');
         //    $newFormattedDateTimeSubtract = $modifyDateTime($data['job_enroute'], $time_interval, 'subtract');

           // dd($newFormattedDateTimeSubtract,$time_interval);
             $event = JobTechEvents::where('job_id', $data['job_id'])->first();
             if ($event) {
               $event->job_enroute =$data['job_enroute'];
           // $event->enroute_time =Carbon::now()->format('H:i:s');

            $event->save();
            return response()->json(['status' => true, 'data' => $event], 201);
        } else {
            return response()->json(['status' => false, 'message' => 'Job not found'], 200);
        }
    }

 public function updateStart(Request $request)
{
    $data = $request->validate([
        'job_id' => 'required',
        'job_start' => 'required|',
    ]);
 $job = JobModel::with('technician', 'user')
                                ->where('id', $request->job_id)->first();

app('sendNoticesapp')(
    "Job started", 
    "Job started (#{$job->id} - {$job->user->name}) started by {$job->technician->name}", 
    url()->current(), 
    'job', 
    $job->technician_id, 
    $job->id
);
app('sendNoticesapp')(
    "Job started", 
    "Job started (#{$job->id} - {$job->user->name}) started by {$job->technician->name}", 
    url()->current(), 
    'job', 
    
    $job->added_by,
     $job->id
);


    $activity = 'Job started';
    $userId=$job->technician_id;
    $jobID = $job->id;  
    app('JobActivityManagerapp')->addJobActivity($jobID, $activity, $userId);

    $event = JobTechEvents::where('job_id', $data['job_id'])->first();

    if ($event) {
        $event->job_start = $data['job_start'];
       // $event->job_time = Carbon::now()->format('H:i:s');

        // Parse job_enroute and job_start as Carbon instances
        $jobEnroute = Carbon::parse($event->job_enroute);
        $jobStart = Carbon::parse($data['job_start']);

        // Calculate the difference in seconds
        $secondsDifference = $jobStart->diffInSeconds($jobEnroute);

        // Convert the difference in seconds to H:i:s format
        $hours = floor($secondsDifference / 3600);
        $minutes = floor(($secondsDifference % 3600) / 60);
        $seconds = $secondsDifference % 60;

        // Format the time difference as H:i:s
        $enrouteTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $event->enroute_time = $enrouteTime;
        $event->save();

        return response()->json(['status' => true, 'data' => $event], 201);
    } else {
        return response()->json(['status' => false, 'message' => 'Job not found'], 200);
    }
}

public function updateComplete(Request $request)
{
    // Validate the request data
    $data = $request->validate([
        'job_id' => 'required',
        'job_end' => 'required|',
    ]);
     $job = JobModel::with('technician', 'user')
                                ->where('id', $request->job_id)->first();

                                app('sendNoticesapp')(
    "Job Completed", 
    "Job Completed (#{$job->id} - {$job->user->name}) completed by {$job->technician->name}", 
    url()->current(), 
    'job', 
    $job->technician->id, 
    $job->id
);
app('sendNoticesapp')(
    "Job Completed", 
    "Job Completed (#{$job->id} - {$job->user->name}) completed by {$job->technician->name}", 
    url()->current(), 
    'job', 
    
    $job->added_by,
     $job->id
);
    $activity = 'Job completed';
    $userId = $job->technician->id; 
    $jobID = $job->id;  
    app('JobActivityManagerapp')->addJobActivity($jobID, $activity, $userId);
    // Retrieve the event associated with the job ID
    $event = JobTechEvents::where('job_id', $data['job_id'])->first();

    if ($event) {
        // Update the job end time
        $event->job_end = $data['job_end'];

        // Parse job_start and job_end as Carbon instances
        $jobStart = Carbon::parse($event->job_start);
        $jobEnd = Carbon::parse($data['job_end']);

        // Calculate the difference in time (without date) between job_start and job_end
        $jobStartTime = Carbon::createFromTime($jobStart->hour, $jobStart->minute, $jobStart->second);
        $jobEndTime = Carbon::createFromTime($jobEnd->hour, $jobEnd->minute, $jobEnd->second);
        
        if ($jobEndTime->lt($jobStartTime)) {
            // Adjust for the case where job_end is on the next day
            $jobEndTime->addDay();
        }

        $jobTimeDifference = $jobStartTime->diffInSeconds($jobEndTime);

        // Convert the difference to H:i:s format
        $hours = floor($jobTimeDifference / 3600);
        $minutes = floor(($jobTimeDifference % 3600) / 60);
        $seconds = $jobTimeDifference % 60;
        $jobTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Store job_time in the event
        $event->job_time = $jobTime;

        // Calculate total_time_on_job by adding enroute_time and job_time
        $enrouteTime = Carbon::createFromFormat('H:i:s', $event->enroute_time);
        $jobTimeCarbon = Carbon::createFromFormat('H:i:s', $jobTime);

        $totalTime = $jobTimeCarbon->copy()->addHours($enrouteTime->hour)->addMinutes($enrouteTime->minute)->addSeconds($enrouteTime->second);

        // Store the calculated total time in H:i:s format
        $event->total_time_on_job = $totalTime->format('H:i:s');

        // Save the updated event
        $event->save();

        // Return a successful response
        return response()->json(['status' => true, 'data' => $event], 201);
    } else {
        // Return a failure response if the job is not found
        return response()->json(['status' => false, 'message' => 'Job not found'], 200);
    }
}

     public function getAppDisclaimer()
    {
        try {
            $siteSetting = SiteSettings::findOrFail(1); // Fetch site setting with id 1
            $appDisclaimer = $siteSetting->app_disclaimer;

            return response()->json(['app_disclaimer' => $appDisclaimer], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resource not found.'], 201);
        }
    }


}


