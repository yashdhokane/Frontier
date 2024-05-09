<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;

class MapController extends Controller
{

    public function index(Request $request)
    {

        $locationServiceSouthWest = DB::table('location_service_area')->where('area_name','South West')->first();

        $query = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', 'job_assigned.job_id')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->orderBy('job_assigned.pending_number', 'asc');

        if (isset($request->area_id) && !empty($request->area_id)) {
            $query->where('jobs.service_area_id', $request->area_id);
        }elseif (isset($locationServiceSouthWest->area_id) && !empty($locationServiceSouthWest->area_id)) {
            $query->where('jobs.service_area_id', $locationServiceSouthWest->area_id);
        }

        $data = $query->get();

       

        $locationServiceArea = DB::table('location_service_area')->where('area_type','region')->get();

        return view('maps.index', compact('data', 'locationServiceArea','locationServiceSouthWest'));
    }

    public function getMarkerDetails(Request $request)
    {
        $data = $request->all();

        $result = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail',
                'jobs.job_field_ids'
            )
            ->join('jobs', 'jobs.id', 'job_assigned.job_id')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
            ->where('job_assigned.job_id', $data['id'])->first();

            $jobFieldsIds = explode(',', $result->job_field_ids);

            $jobFields = DB::table('site_job_fields')
                ->whereIn('field_id', $jobFieldsIds)
                ->get();

                $show_status = ''; 

                foreach ($jobFields as $item) {
                    $show_status .= '<span class="mb-1 badge bg-success">' . $item->field_name . '</span> '; 
                }
                if (empty($jobFields)) {
                    $show_status = '<span class="mb-1 badge bg-secondary">No Fields</span>'; 
                }

        $content = "
			<div class='maplocationpopup'>
			<h4 style='margin-bottom: 0px;'>" . $result->subject . "</h4>
			<div class='mt-2'><span class='mb-1 badge bg-primary'>" . $result->job_id . "</span> " . $show_status . "</div>
			<div class='mt-2'>" . $result->name . ", " . $result->address . ", " . $result->city . ", " . $result->state . ", " . $result->zipcode . "</div>
 			<div class='mt-2'><a href='tickets/". $result->job_id ."' class='btn btn-success waves-effect waves-light btn-sm btn-info'>View</a> <a href='#' class='btn btn-warning waves-effect waves-light btn-sm btn-info reschedule' data-job_id='" . $result->job_id . "'>Reschedule</a></div>
		</div>";

        return response()->json(['content' => $content]);
    }

    public function getTechnicianAreaWise(Request $request)
    {

        $data = $request->all();

        $options = "<option selected value=''>-- Select Technician --</option>";

        if (isset($data['id']) && !empty($data['id'])) {

            $technician = User::select('id', 'name')->where('role', 'technician')->where('service_areas', 'LIKE', '%' . $data['id'] . '%')->get();

            if (isset($technician) && !empty($technician->count())) {

                foreach ($technician as $key => $value) {
                    $options .= "<option value='" . $value->id . "'>" . $value->name . "</option>";
                }
            }

        }

        return $options;
    }

    public function getJobDetails(Request $request)
    {
      $locationServiceSouthWest = DB::table('location_service_area')->where('area_name','South West')->first();

        $data = $request->all();

        if (isset($data) && !empty($data)) {

            $job_id = $data['job_id'];
            $count = $data['count'];

            $getData = DB::table('job_assigned')
                ->select(
                    'job_assigned.start_date_time',
                    'job_assigned.job_id',
                    'jobs.job_title',
                    'jobs.address',
                    'jobs.city',
                    'jobs.state',
                    'jobs.zipcode',
                    'users.name',
                    'job_assigned.duration',
                    'job_assigned.driving_hours',
                )
                ->join('users', 'users.id', 'job_assigned.customer_id')
                ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                ->where('job_assigned.job_id', $job_id)->first();

            $start_date_time = Carbon::parse($getData->start_date_time);

            $getData->start_date_time = $start_date_time->format('Y-m-d\TH:i');

             $technician = null;

        if (isset($request->area_id) && !empty($request->area_id)) {
            $technician = User::select('id', 'name')->where('role', 'technician')->where('service_areas', 'LIKE', '%' . $request->area_id . '%')->get();
        }elseif (isset($locationServiceSouthWest->area_id) && !empty($locationServiceSouthWest->area_id)) {
            $technician = User::select('id', 'name')->where('role', 'technician')->where('service_areas', 'LIKE', '%' . $locationServiceSouthWest->area_id . '%')->get();
        }

            return view('maps.reschedule_list', compact('getData', 'count','technician'));

        }
    }

    public function rescheduleJob(Request $request)
    {

        $data = $request->all();

        try {

        if (isset($data['technician_id']) && !empty($data['technician_id'])) {

            $technician_id = $data['technician_id'];

            if (isset($data['rescheduleData']) && !empty($data['rescheduleData'])) {

                foreach ($data['rescheduleData'] as $key => $value) {

                    $start_date_time = Carbon::parse($value['start_date_time']);

                    $end_date_time = $start_date_time->copy()->addMinutes($value['duration']);

                    $jobData = DB::table('jobs')->where('id', $value['job_id'])->update([
                        'technician_id' => $technician_id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    $JobAssignedData = [
                        'technician_id' => $technician_id,
                        'duration' => $value['duration'],
                        'driving_hours' => $value['driving_hours'],
                        'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                        'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'start_slot' => $start_date_time->format('H'),
                        'end_slot' => $end_date_time->format('H'),
                    ];

                    $jobAssignedID = DB::table('job_assigned')->where('job_id', $value['job_id'])->update($JobAssignedData);

                    $scheduleData = [
                        'technician_id' => $technician_id,
                        'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                        'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                        'updated_by' => auth()->id(),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'start_slot' => $start_date_time->format('H'),
                        'end_slot' => $end_date_time->format('H'),
                    ];

                    $schedule = DB::table('schedule')->where('job_id', $value['job_id'])->update($scheduleData);
                
                    $technician_name = User::where('id',$technician_id)->first();
                    $now = Carbon::now();
                    $formattedDate = $start_date_time->format('D, M j');
                    $formattedTime = $now->format('g:ia');
                    $formattedDateTime = "{$formattedDate} at {$formattedTime}";
                    $activity ='Job Re-Scheduled for '. $formattedDateTime;

                    app('JobActivityManager')->addJobActivity($value['job_id'], $activity);
                    app('sendNotices')(
                        "Reschedule Job","Reschedule Job (#{$value['job_id']} - {$value['customer_name']}) added by {$technician_name->name}",
                        url()->current(), 
                        'job'
                    );

                }

            }

        }

        return 'true';

        } catch (\Exception $e) {

            Storage::append('Reschedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

            return 'false';
        }

    }

}
