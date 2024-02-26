<?php



namespace App\Http\Controllers;


use App\Models\User;

use App\Models\BusinessHours;

use Illuminate\Http\Request;

use Carbon\Carbon;



class BusinessHoursController extends Controller

{

    public function businesshourspage()
    {
        
        $businessHours = BusinessHours::all();
        // dd($businessHours);

        return view('businessHours.business-hours', compact('businessHours'));
    }


    public function updateBusinessHours(Request $request)
    {
        // dd($request->all());
        // try {
           

            $data = $request->all();
            // dd($data);
            
            foreach ($data['day'] as $day => $values) {
                // dd($day,$values);
              if(isset($values['status']) && $values['status'] == 'on') {
                $status = "open";
              }else{
                $status = "close";
                // $values['start'] = "00:00";
                // $values['end'] = "00:00";
              }
                // if (strpos($day, '-') !== false) {
                //     $parts = explode('-', $day);
                //     // dd($day);
                //     foreach ($parts as $part) {
                //             BusinessHours::updateOrCreate(
                //                 ['day' => $part],
                //                 [
                //                     'start_time' => $values['start'],
                //                     'end_time' => $values['end'],
                //                     'open_close' => $status,
                //                 ]
                //             );
                //     }
                // } 
                // else {
                    BusinessHours::updateOrCreate(
                        ['day' => $day],
                        [
                            'start_time' => $values['start'],
                            'end_time' => $values['end'],
                            'open_close' => $status,
                        ]
                    );
                // }
                
            }
            
            return response()->json(['message' => 'Business hours updated successfully']);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Error updating business hours'], 500);
        // }
    }

    public function updateOnlineHours(Request $request)
    {
        // dd($request->all());
            $data = $request->all();
            foreach ($data['day'] as $day => $values) {
              if(isset($values['status']) && $values['status'] == 'on') {
                $status = "open";
              }else{
                $status = "close";
                // $values['start'] = "00:00";
                // $values['end'] = "00:00";
              }             
                    BusinessHours::updateOrCreate(
                        ['day' => $day],
                        [
                            'booking_start_time' => $values['start'],
                            'booking_end_time' => $values['end'],
                            'booking_open_close' => $status,
                        ]
                    );
            } 
            return response()->json(['message' => 'Business hours updated successfully']);
    }
    
}



