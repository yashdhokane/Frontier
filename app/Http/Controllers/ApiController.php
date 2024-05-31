<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JobNoteModel;
use App\Models\JobProduct;
use App\Models\JobOtherModel; 
use App\Models\UserNotesCustomer;
use App\Models\Event;  
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
  
   public function user_login(Request $request)
{
    // Find user by email
    $user = User::where('email', '=', $request->email)->first();

    // Check if user exists
    if ($user) {
        // Check if password matches
        if (Hash::check($request->password, $user->password)) {
            // Check if user's role is "technician"
            if ($user->role === 'technician') {
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
}

    public function getNotification(Request $request)
    {
        $technicianId = $request->input('technician_id');

        if (!$technicianId) {
            return response()->json(['status' => false, 'message' => 'Technician ID is required'], 500);
        }

        $notifications = UserNotesCustomer::where('user_id', $technicianId)->get();

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


public function getTechnicianJobs(Request $request)
{
    $userId = $request->input('user_id');
    $currentDate = Carbon::now()->toDateString();
    //   dd($currentDate);
    // Check if there are any jobs for the specified technician (user_id) with the current date
    $jobs = JobModel::with('user', 'JobAssign', 'addresscustomer', 'usertechnician', 'jobdetailsinfo.manufacturername')
        ->where('technician_id', $userId)
        ->whereDate('created_at', $currentDate)
        ->get();

    if ($jobs->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'Today no jobs are available'],201);
    }

    return response()->json(['status' => true, 'data' => $jobs],200);
}

public function getCustomerHistory(Request $request)
{
    $userId = $request->input('user_id');

    // Retrieve customer history where user_id matches customer_id in JobModel
    $customerHistory = JobModel::with('usertechnician')
                                ->where('customer_id', $userId)
                                ->get();

    if ($customerHistory->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'No customer history found for the specified user'], 201);
    }

    return response()->json(['status' => true, 'data' => $customerHistory],200);
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
    $jobs = JobModel::with('user', 'JobAssign', 'addresscustomer', 'usertechnician', 'jobdetailsinfo.manufacturername')->where('technician_id', $userId)
        ->whereDate('created_at', $formattedDate)
        ->get();

    if ($jobs->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'No jobs available for the specified date'], 201);
    }

    return response()->json(['status' => true, 'data' => $jobs],200);
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

public function jobfileUploadByTechnician(Request $request)
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
  


}


