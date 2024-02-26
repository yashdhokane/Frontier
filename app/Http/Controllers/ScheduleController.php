<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Products;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Storage;

class ScheduleController extends Controller
{

    public function index(Request $request)
    {

        $data = $request->all();

        if (isset($data['date']) && !empty($data['date'])) {
            $currentDate = Carbon::parse($data['date']);
        } else {
            $currentDate = Carbon::now();
        }

        $formattedDate = $currentDate->format('l, F j, Y');

        $previousDate = $currentDate->copy()->subDay()->format('Y-m-d');

        $tomorrowDate = $currentDate->copy()->addDay()->format('Y-m-d');

        $filterDate = $currentDate->format('Y-m-d');

        $user_array = [];

        $user_data_array = [];

        $assignment_arr = [];

        $technician = User::where('role', 'technician')->where('status', 'active')->get();

        if (isset($technician) && !empty($technician->count())) {
            foreach ($technician as $key => $value) {
                $user_array[] = $value->id;
                $user_data_array[$value->id]['name'] = $value->name;
                $user_data_array[$value->id]['color_code'] = $value->color_code;
                $user_data_array[$value->id]['user_image'] = $value->user_image;
            }
        }

        if (isset($user_array) && !empty($user_array)) {

            foreach ($user_array as $key => $value) {

                $assignment_arr[$value] = [];

                $result = DB::table('job_assigned')
                    ->select(
                        'job_assigned.id as assign_id',
                        'job_assigned.job_id as job_id',
                        'job_assigned.start_date_time',
                        'job_assigned.end_date_time',
                        'job_assigned.start_slot',
                        'job_assigned.end_slot',
                        'job_assigned.duration',
                        'jobs.id as main_id',
                        'jobs.job_code',
                        'jobs.job_title',
                        'jobs.status',
                        'jobs.address',
                        'jobs.city',
                        'jobs.state',
                        'jobs.zipcode',
                        'jobs.created_at',
                        'users.name as customername',
                        'users.email as customeremail',
                        'technician.name as technicianname',
                        'technician.email as technicianemail',
                        'technician.color_code',
                        'technician.user_image',
                        'job_assigned.technician_id'
                    )
                    ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                    ->join('users', 'users.id', 'jobs.customer_id')
                    ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
                    ->where('job_assigned.technician_id', $value)
                    ->where('job_assigned.start_date_time', 'LIKE', "%$filterDate%")
                    ->get();

                if (isset($result) && !empty($result->count())) {
                    foreach ($result as $key2 => $value2) {
                        $assignment_arr[$value][$value2->start_slot][] = $value2;
                    }
                }
            }
        }

        return view('schedule.index', compact('user_array', 'user_data_array', 'assignment_arr', 'formattedDate', 'previousDate', 'tomorrowDate', 'filterDate'));
    }

    public function create(Request $request)
    {

        $data = $request->all();

        $time = str_replace(" ", ":00 ", $data['time']);

        $date = $data['date'];

        $dateTime = Carbon::parse("$date $time");

        $dateTime = $dateTime->format('Y-m-d\TH:i');

        $technician = User::where('id', $data['id'])->first();

        return view('schedule.create', compact('technician', 'dateTime'));

    }

    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::where('name', 'LIKE', '%' . $query . '%')->where('role', 'customer')->get();
        return response()->json($filterResult);
    }

    public function autocompleteTechnician(Request $request)
    {
        $query = $request->get('query');
        $filterResult = User::where('name', 'LIKE', '%' . $query . '%')->where('role', 'technician')->get();
        return response()->json($filterResult);
    }

    public function autocompleteServices(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Service::select('service_name as name')->where('service_name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    }

    public function autocompleteProduct(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Products::select('product_name as name')->where('product_name', 'LIKE', '%' . $query . '%')->get();
        return response()->json($filterResult);
    }

    public function getCustomerDetails(Request $request)
    {
        $data = $request->all();

        $customer = [];

        if (isset($data['name']) && !empty($data['name'])) {

            $getCustomerDetails = User::where('name', $data['name'])->first();

            if (isset($getCustomerDetails) && !empty($getCustomerDetails)) {
                $customer = $getCustomerDetails->toArray();
                $getCustomerAddressDetails = DB::table('user_address')->where('user_id', $getCustomerDetails->id)->get()->toArray();
                $customer['address'] = $getCustomerAddressDetails;
            }
        }

        return response()->json($customer);

    }

    public function getProductDetails(Request $request)
    {
        $data = $request->all();

        $product = [];

        if (isset($data['searchProduct']) && !empty($data['searchProduct'])) {

            $getProductDetails = Products::where('product_name', $data['searchProduct'])->where('status', 'Publish')->first();

            if (isset($getProductDetails) && !empty($getProductDetails)) {
                $product = $getProductDetails->toArray();
            }
        }

        return response()->json($product);

    }

    public function getServicesDetails(Request $request)
    {
        $data = $request->all();

        $serives = [];

        if (isset($data['searchServices']) && !empty($data['searchServices'])) {

            $getServicesDetails = Service::where('service_name', $data['searchServices'])->first();

            if (isset($getServicesDetails) && !empty($getServicesDetails)) {
                $serives = $getServicesDetails->toArray();
            }
        }

        return response()->json($serives);

    }

    public function createSchedule(Request $request)
    {

        $data = $request->all();

        try {

            if (isset($data) && !empty($data)) {

                if (isset($data['job_id']) && !empty($data['job_id'])) {

                    $start_date_time = Carbon::parse($data['datetime']);

                    $duration = (int) $data['duration'];

                    $end_date_time = $start_date_time->copy()->addMinutes($duration);

                    $technician = User::where('id', $data['technician_id'])->first();

                    $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                    $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                    $getCustomerDetails = User::select('user_address.*')
                        ->join('user_address', 'user_address.user_id', 'users.id')
                        ->where('users.id', $data['customer_id'])->where('user_address.address_type', $data['address'])
                        ->first();

                    $jobsData = [
                        'job_code' => (isset($data['job_code']) && !empty($data['job_code'])) ? $data['job_code'] : '',
                        'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                        'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                        'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                        'job_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                        'description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                        'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                        'tax' => $service_tax + $product_tax,
                        'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                        'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                        'commission_total' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                        'address_type' => (isset($data['address']) && !empty($data['address'])) ? $data['address'] : '',
                        'address' => (isset($getCustomerDetails->address_line1) && !empty($getCustomerDetails->address_line1)) ? $getCustomerDetails->address_line1 : '',
                        'city' => (isset($getCustomerDetails->city) && !empty($getCustomerDetails->city)) ? $getCustomerDetails->city : '',
                        'state' => (isset($getCustomerDetails->state_name) && !empty($getCustomerDetails->state_name)) ? $getCustomerDetails->state_name : '',
                        'zipcode' => (isset($getCustomerDetails->zipcode) && !empty($getCustomerDetails->zipcode)) ? $getCustomerDetails->zipcode : '',
                        'latitude' => (isset($getCustomerDetails->latitude) && !empty($getCustomerDetails->latitude)) ? $getCustomerDetails->latitude : 0,
                        'longitude' => (isset($getCustomerDetails->longitude) && !empty($getCustomerDetails->longitude)) ? $getCustomerDetails->longitude : 0,
                        'updated_by' => auth()->id(),
                        'job_field_ids' => 0,
                        'tag_ids' => 0,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $jobId = DB::table('jobs')->where('id', $data['job_id'])->update($jobsData);

                    $jobNotes = [
                        'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $jobNotesID = DB::table('job_notes')->where('job_id', $data['job_id'])->update($jobNotes);

                    $JobAssignedData = [
                        'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                        'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                        'assign_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                        'assign_description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                        'duration' => (isset($data['duration']) && !empty($data['duration'])) ? $data['duration'] : '',
                        'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                        'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'start_slot' => $start_date_time->format('H'),
                        'end_slot' => $end_date_time->format('H'),
                        'technician_note_id' => $jobNotesID
                    ];

                    $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                    if (isset($data['service_id']) && !empty($data['service_id']) && (isset($data['service_quantity']) && !empty($data['service_quantity']) && $data['service_quantity'] != 0)) {

                        $serviceData = [
                            'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                            'service_description' => (isset($data['service_description']) && !empty($data['service_description'])) ? $data['service_description'] : '',
                            'service_name' => (isset($data['service_name']) && !empty($data['service_name'])) ? $data['service_name'] : '',
                            'base_price' => (isset($data['service_cost']) && !empty($data['service_cost'])) ? $data['service_cost'] : '',
                            'quantity' => (isset($data['service_quantity']) && !empty($data['service_quantity'])) ? $data['service_quantity'] : '',
                            'tax' => $service_tax,
                            'discount' => (isset($data['service_discount']) && !empty($data['service_discount'])) ? $data['service_discount'] : '',
                            'sub_total' => (isset($data['service_total']) && !empty($data['service_total'])) ? $data['service_total'] : '',
                        ];

                        $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                    }

                    if (isset($data['product_id']) && !empty($data['product_id']) && (isset($data['product_quantity']) && !empty($data['product_quantity']) && $data['product_quantity'] != 0)) {

                        $productData = [
                            'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                            'product_description' => (isset($data['product_description']) && !empty($data['product_description'])) ? $data['product_description'] : '',
                            'product_name' => (isset($data['product_name']) && !empty($data['product_name'])) ? $data['product_name'] : '',
                            'base_price' => (isset($data['product_cost']) && !empty($data['product_cost'])) ? $data['product_cost'] : '',
                            'quantity' => (isset($data['product_quantity']) && !empty($data['product_quantity'])) ? $data['product_quantity'] : '',
                            'tax' => $product_tax,
                            'discount' => (isset($data['product_discount']) && !empty($data['product_discount'])) ? $data['product_discount'] : '',
                            'sub_total' => (isset($data['product_total']) && !empty($data['product_total'])) ? $data['product_total'] : '',
                        ];

                        $productDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($productData);
                    }

                    if ($request->hasFile('photos')) {

                        $fileData = [];

                        foreach ($request->file('photos') as $file) {

                            $fileName = $data['job_id'] . '_' . $file->getClientOriginalName();

                            $path = 'schedule';

                            $file->storeAs($path, $fileName);

                            $fileData[] = [
                                'job_id' => $data['job_id'],
                                'user_id' => auth()->id(),
                                'path' => $path . '/',
                                'filename' => $fileName,
                                'type' => $file->getMimeType(),
                                'size' => $file->getSize(),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];

                        }

                        $fileDataInsert = DB::table('job_files')->insert($fileData);

                    }

                    $height_slot = $duration / 60;
                    $height_slot_px = $height_slot * 80 - 10;

                    $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:'.$height_slot_px.'px;background:'.$technician->color_code.';" data-id="' . $jobId . '">
                    <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $data['customer_name'] . '</h5>
                    <p style="font-size: 11px;"><i class="fas fa-clock"></i>' . $start_date_time->format('h a') . ' -- ' . $data['job_code'] . ' <br>' . $data['job_title'] . '</p>
                    <p style="font-size: 12px;">' . $getCustomerDetails->city . ',' . $getCustomerDetails->state_name . '</p></div>';

                    return ['html' => $returnDate, 'start_date' => $start_date_time->copy()->format('g'), 'technician_id' => $data['technician_id']];

                } else {

                    $technician = User::where('id', $data['technician_id'])->first();

                    $start_date_time = Carbon::parse($data['datetime']);

                    $duration = (int) $data['duration'];

                    $end_date_time = $start_date_time->copy()->addMinutes($duration);

                    $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                    $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                    $getCustomerDetails = User::select('user_address.*')
                        ->join('user_address', 'user_address.user_id', 'users.id')
                        ->where('users.id', $data['customer_id'])->where('user_address.address_type', $data['address'])
                        ->first();

                    $jobsData = [
                        'job_code' => (isset($data['job_code']) && !empty($data['job_code'])) ? $data['job_code'] : '',
                        'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                        'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                        'job_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                        'job_type' => (isset($data['job_type']) && !empty($data['job_type'])) ? $data['job_type'] : '',
                        'description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                        'priority' => (isset($data['priority']) && !empty($data['priority'])) ? $data['priority'] : '',
                        'tax' => $service_tax + $product_tax,
                        'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                        'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                        'commission_total' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                        'address_type' => (isset($data['address']) && !empty($data['address'])) ? $data['address'] : '',
                        'address' => (isset($getCustomerDetails->address_line1) && !empty($getCustomerDetails->address_line1)) ? $getCustomerDetails->address_line1 : '',
                        'city' => (isset($getCustomerDetails->city) && !empty($getCustomerDetails->city)) ? $getCustomerDetails->city : '',
                        'state' => (isset($getCustomerDetails->state_name) && !empty($getCustomerDetails->state_name)) ? $getCustomerDetails->state_name : '',
                        'zipcode' => (isset($getCustomerDetails->zipcode) && !empty($getCustomerDetails->zipcode)) ? $getCustomerDetails->zipcode : '',
                        'latitude' => (isset($getCustomerDetails->latitude) && !empty($getCustomerDetails->latitude)) ? $getCustomerDetails->latitude : 0,
                        'longitude' => (isset($getCustomerDetails->longitude) && !empty($getCustomerDetails->longitude)) ? $getCustomerDetails->longitude : 0,
                        'added_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'job_field_ids' => 0,
                        'tag_ids' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $jobId = DB::table('jobs')->insertGetId($jobsData);

                    $jobNotes = [
                        'job_id' => $jobId,
                        'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                        'added_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $jobNotesID = DB::table('job_notes')->insertGetId($jobNotes);

                    $JobAssignedData = [
                        'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                        'customer_id' => (isset($data['customer_id']) && !empty($data['customer_id'])) ? $data['customer_id'] : '',
                        'job_id' => $jobId,
                        'assign_title' => (isset($data['job_title']) && !empty($data['job_title'])) ? $data['job_title'] : '',
                        'assign_description' => (isset($data['description']) && !empty($data['description'])) ? $data['description'] : '',
                        'duration' => (isset($data['duration']) && !empty($data['duration'])) ? $data['duration'] : '',
                        'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                        'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                        'added_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'start_slot' => $start_date_time->format('H'),
                        'end_slot' => $end_date_time->format('H'),
                        'technician_note_id' => $jobNotesID
                    ];

                    $jobAssignedID = DB::table('job_assigned')->insertGetId($JobAssignedData);

                    if (isset($data['service_id']) && !empty($data['service_id']) && (isset($data['service_quantity']) && !empty($data['service_quantity']) && $data['service_quantity'] != 0)) {

                        $serviceData = [
                            'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                            'job_id' => $jobId,
                            'service_description' => (isset($data['service_description']) && !empty($data['service_description'])) ? $data['service_description'] : '',
                            'service_name' => (isset($data['service_name']) && !empty($data['service_name'])) ? $data['service_name'] : '',
                            'base_price' => (isset($data['service_cost']) && !empty($data['service_cost'])) ? $data['service_cost'] : '',
                            'quantity' => (isset($data['service_quantity']) && !empty($data['service_quantity'])) ? $data['service_quantity'] : '',
                            'tax' => $service_tax,
                            'discount' => (isset($data['service_discount']) && !empty($data['service_discount'])) ? $data['service_discount'] : '',
                            'sub_total' => (isset($data['service_total']) && !empty($data['service_total'])) ? $data['service_total'] : '',
                        ];

                        $serviceDataInsert = DB::table('job_service_items')->insertGetId($serviceData);
                    }

                    if (isset($data['product_id']) && !empty($data['product_id']) && (isset($data['product_quantity']) && !empty($data['product_quantity']) && $data['product_quantity'] != 0)) {

                        $productData = [
                            'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                            'job_id' => $jobId,
                            'product_description' => (isset($data['product_description']) && !empty($data['product_description'])) ? $data['product_description'] : '',
                            'product_name' => (isset($data['product_name']) && !empty($data['product_name'])) ? $data['product_name'] : '',
                            'base_price' => (isset($data['product_cost']) && !empty($data['product_cost'])) ? $data['product_cost'] : '',
                            'quantity' => (isset($data['product_quantity']) && !empty($data['product_quantity'])) ? $data['product_quantity'] : '',
                            'tax' => $product_tax,
                            'discount' => (isset($data['product_discount']) && !empty($data['product_discount'])) ? $data['product_discount'] : '',
                            'sub_total' => (isset($data['product_total']) && !empty($data['product_total'])) ? $data['product_total'] : '',
                        ];

                        $productDataInsert = DB::table('job_product_items')->insertGetId($productData);
                    }

                    if ($request->hasFile('photos')) {

                        $fileData = [];

                        foreach ($request->file('photos') as $file) {

                            $fileName = $jobId . '_' . $file->getClientOriginalName();

                            $path = 'schedule';

                            $file->storeAs($path, $fileName);

                            $fileData[] = [
                                'job_id' => $jobId,
                                'user_id' => auth()->id(),
                                'path' => $path . '/',
                                'filename' => $fileName,
                                'type' => $file->getMimeType(),
                                'size' => $file->getSize(),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ];

                        }

                        $fileDataInsert = DB::table('job_files')->insert($fileData);

                    }

                    $height_slot = $duration / 60;
                    $height_slot_px = $height_slot * 80 - 10;

                    $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:'.$height_slot_px.'px;background:'.$technician->color_code.';" data-id="' . $jobId . '">
                    <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $data['customer_name'] . '</h5>
                    <p style="font-size: 11px;"><i class="fas fa-clock"></i>' . $start_date_time->format('h a') . ' -- ' . $data['job_code'] . ' <br>' . $data['job_title'] . '</p>
                    <p style="font-size: 12px;">' . $getCustomerDetails->city . ',' . $getCustomerDetails->state_name . '</p></div>';

                    return ['html' => $returnDate];
                }

            }

        } catch (\Exception $e) {

            Storage::append('CreateSchedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

            return 'false';
        }

    }

    public function edit(Request $request)
    {

        $data = $request->all();

        $jobDetails = DB::table('jobs')
            ->select(
                'jobs.id',
                'jobs.job_title',
                'jobs.discount',
                'jobs.gross_total',
                'jobs.commission_total',
                'users.name as customername',
                'technician.name as technicianname',
                'technician.id as technician_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_service_items.service_id',
                'job_service_items.service_name',
                'job_service_items.base_price as service_cost',
                'job_service_items.quantity as service_quantity',
                'job_service_items.discount as service_discount',
                'job_service_items.sub_total as service_total',
                'job_service_items.service_description',
                'job_service_items.tax as service_tax',
                'job_product_items.product_id',
                'job_product_items.product_name',
                'job_product_items.base_price as product_cost',
                'job_product_items.quantity as product_quantity',
                'job_product_items.discount as product_discount',
                'job_product_items.sub_total as product_total',
                'job_product_items.product_description',
                'job_product_items.tax as product_tax',
                'job_notes.note'
            )
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'jobs.technician_id')
            ->leftJoin('job_assigned', 'job_assigned.job_id', 'jobs.id')
            ->leftJoin('job_service_items', 'job_service_items.job_id', 'jobs.id')
            ->leftJoin('job_product_items', 'job_product_items.job_id', 'jobs.id')
            ->leftJoin('job_notes', 'job_notes.job_id', 'jobs.id')
            ->where('jobs.id', $data['id'])->first();


        $start_date_time = Carbon::parse($jobDetails->start_date_time);

        $end_date_time = Carbon::parse($jobDetails->end_date_time);

        $jobDetails->start_date_time = $start_date_time->format('Y-m-d\TH:i');

        $jobDetails->end_date_time = $end_date_time->format('Y-m-d\TH:i');

        return view('schedule.edit', compact('jobDetails'));

    }

    public function updateSchedule(Request $request)
    {

        $data = $request->all();

        try {

            if (isset($data) && !empty($data)) {

                $start_date_time = Carbon::parse($data['start_date_time']);

                $old_start_date_time = Carbon::parse($data['old_start_date_time']);

                $technician = User::where('id', $data['technician_id'])->first();

                $current_date = Carbon::now();

                $result = $start_date_time->gt($old_start_date_time) ? $start_date_time->copy()->format('g') : null;

                //$result = $old_start_date_time->copy()->greaterThan($start_date_time) ? null  : $start_date_time->copy()->format('g');

                $end_date_time = Carbon::parse($data['end_date_time']);

                $duration = $start_date_time->copy()->diffInMinutes($end_date_time->copy());

                $jobDetails = DB::table('jobs')->where('id', $data['job_id'])->first();

                $service_tax = (isset($data['service_tax']) && !empty($data['service_tax'])) ? $data['service_tax'] : 0;

                $product_tax = (isset($data['product_tax']) && !empty($data['product_tax'])) ? $data['product_tax'] : 0;

                $jobsData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'tax' => $service_tax + $product_tax,
                    'discount' => (isset($data['discount']) && !empty($data['discount'])) ? $data['discount'] : 0,
                    'gross_total' => (isset($data['total']) && !empty($data['total'])) ? $data['total'] : 0,
                    'commission_total' => (isset($data['subtotal']) && !empty($data['subtotal'])) ? $data['subtotal'] : 0,
                    'updated_by' => auth()->id(),
                    'job_field_ids' => 0,
                    'tag_ids' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobId = DB::table('jobs')->where('id', $data['job_id'])->update($jobsData);

                $jobNotes = [
                    'note' => (isset($data['technician_notes']) && !empty($data['technician_notes'])) ? $data['technician_notes'] : '',
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $jobNotesID = DB::table('job_notes')->where('job_id', $data['job_id'])->update($jobNotes);

                $JobAssignedData = [
                    'technician_id' => (isset($data['technician_id']) && !empty($data['technician_id'])) ? $data['technician_id'] : '',
                    'duration' => $duration,
                    'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                    'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                    'updated_by' => auth()->id(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'start_slot' => $start_date_time->format('H'),
                    'end_slot' => $end_date_time->format('H'),
                ];

                $jobAssignedID = DB::table('job_assigned')->where('job_id', $data['job_id'])->update($JobAssignedData);

                if (isset($data['service_id']) && !empty($data['service_id']) && (isset($data['service_quantity']) && !empty($data['service_quantity']) && $data['service_quantity'] == 0)) {

                    DB::table('job_service_items')->where('job_id', $data['job_id'])->delete();

                } elseif (isset($data['service_id']) && !empty($data['service_id'])) {

                    $serviceData = [
                        'service_id' => (isset($data['service_id']) && !empty($data['service_id'])) ? $data['service_id'] : '',
                        'service_name' => (isset($data['service_name']) && !empty($data['service_name'])) ? $data['service_name'] : '',
                        'base_price' => (isset($data['service_cost']) && !empty($data['service_cost'])) ? $data['service_cost'] : '',
                        'quantity' => (isset($data['service_quantity']) && !empty($data['service_quantity'])) ? $data['service_quantity'] : '',
                        'tax' => $service_tax,
                        'discount' => (isset($data['service_discount']) && !empty($data['service_discount'])) ? $data['service_discount'] : '',
                        'sub_total' => (isset($data['service_total']) && !empty($data['service_total'])) ? $data['service_total'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_service_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                if (isset($data['product_id']) && !empty($data['product_id']) && (isset($data['product_quantity']) && !empty($data['product_quantity']) && $data['product_quantity'] == 0)) {

                    DB::table('job_product_items')->where('job_id', $data['job_id'])->delete();

                } elseif (isset($data['product_id']) && !empty($data['product_id'])) {

                    $serviceData = [
                        'product_id' => (isset($data['product_id']) && !empty($data['product_id'])) ? $data['product_id'] : '',
                        'product_name' => (isset($data['product_name']) && !empty($data['product_name'])) ? $data['product_name'] : '',
                        'base_price' => (isset($data['product_cost']) && !empty($data['product_cost'])) ? $data['product_cost'] : '',
                        'quantity' => (isset($data['product_quantity']) && !empty($data['product_quantity'])) ? $data['product_quantity'] : '',
                        'tax' => $product_tax,
                        'discount' => (isset($data['product_discount']) && !empty($data['product_discount'])) ? $data['product_discount'] : '',
                        'sub_total' => (isset($data['product_total']) && !empty($data['product_total'])) ? $data['product_total'] : '',
                    ];

                    $serviceDataInsert = DB::table('job_product_items')->where('job_id', $data['job_id'])->update($serviceData);
                }

                $height_slot = $duration / 60;
                $height_slot_px = $height_slot * 80 - 10;

                $returnDate = '<div class="dts mb-1 edit_schedule flexibleslot" data-bs-toggle="modal" data-bs-target="#edit" style="cursor: pointer;height:'.$height_slot_px.'px;background:'.$technician->color_code.';" data-id="' . $jobId . '">
                <h5 style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">' . $data['customer_name'] . '</h5>
                <p style="font-size: 11px;"><i class="fas fa-clock"></i>' . $start_date_time->format('h a') . ' -- ' . $jobDetails->job_code . ' <br>' . $jobDetails->job_title . '</p>
                <p style="font-size: 12px;">' . $jobDetails->city . ',' . $jobDetails->state . '</p></div>';

                return ['html' => $returnDate, 'start_date' => $result, 'technician_id' => $data['technician_id']];

            }

        } catch (\Exception $e) {

            Storage::append('UpdateSchedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

            return 'false';
        }

    }

    public function autocompletesearchOldJob(Request $request)
    {
        $query = $request->get('query');

        $filterResult = DB::table('jobs')
            ->select('jobs.job_title as name', 'jobs.id', 'users.name as customer')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->where('jobs.id', 'LIKE', '%' . $query . '%')
            ->orWhere('users.name', 'like', '%' . $query . '%')
            ->get();

        return response()->json($filterResult);
    }

    public function getExistingSchedule(Request $request)
    {

        $data = $request->all();

        if (isset($data['jobid']) && !empty($data['jobid'])) {

            $jobDetails = DB::table('jobs')
                ->select(
                    'jobs.id',
                    'jobs.job_title',
                    'jobs.priority',
                    'jobs.description',
                    'jobs.job_code',
                    'jobs.job_type',
                    'jobs.discount',
                    'jobs.gross_total',
                    'jobs.commission_total',
                    'jobs.address_type',
                    'users.name as customername',
                    'users.id as customer_id',
                    'technician.name as technicianname',
                    'technician.id as technician_id',
                    'job_assigned.start_date_time',
                    'job_assigned.duration',
                    'job_assigned.end_date_time',
                    'job_service_items.service_id',
                    'job_service_items.service_name',
                    'job_service_items.base_price as service_cost',
                    'job_service_items.quantity as service_quantity',
                    'job_service_items.discount as service_discount',
                    'job_service_items.sub_total as service_total',
                    'job_service_items.service_description',
                    'job_service_items.tax as service_tax',
                    'job_product_items.product_id',
                    'job_product_items.product_name',
                    'job_product_items.base_price as product_cost',
                    'job_product_items.quantity as product_quantity',
                    'job_product_items.discount as product_discount',
                    'job_product_items.sub_total as product_total',
                    'job_product_items.product_description',
                    'job_product_items.tax as product_tax',
                    'job_notes.note as technician_notes'
                )
                ->join('users', 'users.id', 'jobs.customer_id')
                ->join('users as technician', 'technician.id', 'jobs.technician_id')
                ->leftJoin('job_assigned', 'job_assigned.job_id', 'jobs.id')
                ->leftJoin('job_service_items', 'job_service_items.job_id', 'jobs.id')
                ->leftJoin('job_product_items', 'job_product_items.job_id', 'jobs.id')
                ->leftJoin('job_notes', 'job_notes.job_id', 'jobs.id')
                ->where('jobs.id', $data['jobid'])->first();

            $start_date_time = Carbon::parse($jobDetails->start_date_time);

            $end_date_time = Carbon::parse($jobDetails->end_date_time);

            $jobDetails->start_date_time = $start_date_time->format('Y-m-d\TH:i');

            $jobDetails->end_date_time = $end_date_time->format('Y-m-d\TH:i');

            if (isset($jobDetails->customer_id) && !empty($jobDetails->customer_id)) {
                $getCustomerAddressDetails = DB::table('user_address')->where('user_id', $jobDetails->customer_id)->get()->toArray();
                $jobDetails->address = $getCustomerAddressDetails;
            }

            return $jobDetails;

        }

    }


}
