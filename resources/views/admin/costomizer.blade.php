<aside class="customizer">
	<a href="javascript:void(0)" class="service-panel-toggle"><i data-feather="settings" class="feather-sm fa fa-spin"></i></a>
	
	<div class="customizer-body">
	
        <ul class="nav customizer-tab" role="tablist">
			
			<li class="nav-item"><a class="nav-link active" id="tab-activity-tab" data-bs-toggle="pill" href="#tab-activity" role="tab" aria-controls="tab-activity" aria-selected="false" ><i class="ri-calendar-line fs-6"></i></a></li>
  			
			<li class="nav-item"><a class="nav-link " id="timezone-tab" data-bs-toggle="pill" href="#timezone" role="tab" aria-controls="timezone" aria-selected="true" ><i class="ri-timer-line fs-6"></i></a></li>
			
			
 			
			<li class="nav-item"><a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#chat" role="tab" aria-controls="chat" aria-selected="false" ><i class="ri-information-line fs-6"></i></a></li>
        </ul>
		
        <div class="tab-content" id="pills-tabContent">
		
			 @php
                use App\Models\User;
                use App\Models\TimeZone;

                $zone = TimeZone::all();

                $id = Auth::User()->id;

                $time = User::with('TimeZone')->where('id', $id)->first();

            @endphp
			
            <!-- Tab Timezone -->
            <div class="tab-pane fade" id="timezone" role="tabpanel" aria-labelledby="timezone-tab">
                <div class="p-3 border-bottom">
 					<h5 class="card-title uppercase mt-3 mb-1">Current Time</h5>
                    <div class="mt-1">
                        <?php
                        // Assuming $time->TimeZone->country_name contains a valid country name
                        $country_name = $time->TimeZone->timezone_name;
                        
                        // Map country names to timezone identifiers
                        // $timezone_mapping = [
                        //     'India' => 'Asia/Kolkata',
                        //     'United States' => 'America/New_York',
                        //     // Add more mappings as needed
                        // ];
                        
                        // Check if the country name exists in the mapping
                        if (isset($country_name)) {
                            // Create a new DateTimeZone object using the timezone identifier
                            $timezone = new DateTimeZone($country_name);
                        
                            // Create a new DateTime object with the current time and the specified timezone
                            $date = new DateTime('now', $timezone);
                        
                            // Format the datetime according to the desired format
                            $formatted_time = $date->format('D, jS F Y, h:i:sa');
                        
                            // Output the formatted time
                            echo $formatted_time;
                        } else {
                            // Handle the case where no valid timezone identifier was found for the country name
                            echo 'Invalid country name or timezone not found.';
                        }
                        ?>
                     </div>
                    <h5 class="card-title uppercase mt-4 mb-1">Timezone</h5>
                    <div class="mt-1">
                        {{ $time->TimeZone->timezone_name }}<br />{{ $time->TimeZone->gmt_offset }} Hours
                    </div>
                </div>
            </div>
            <!-- End Tab Timezone -->
			
			   @php
            $today = now()->toDateString(); // Get today's date
            $technicians = App\Models\Schedule::with('technician', 'JobModel')
            ->where('schedule_type','job')
            ->whereDate('start_date_time', $today) 
            ->orderBy('start_date_time', 'desc')
            ->get();
            @endphp

																							
			<!-- Tab Activity -->
		 <div class="tab-pane fade p-3 show active" id="tab-activity" role="tabpanel"
                aria-labelledby="tab-activity-tab">
                <h5 class="card-title uppercase mt-3 mb-3">Today's Jobs</h5>
                <div class="steamline">
                    @foreach ($technicians as $technician)
                    <div class="sl-item">
                        <div class="sl-left bg-light-success text-success"><i class="ri-calendar-check-fill"></i></div>
                        <div class="sl-right">
                            <div class="font-medium">
                                <div class="ft13 uppercase bold">{{ $technician->JobModel->job_title ?? '' }}</div>
                                <div class="ft12"><i class="ri-user-line"></i> {{ $technician->JobModel->user->name ?? '' }}</div>
                                <div class="sl-date ft12" style="display: block;">
									<i class="ri-map-pin-fill"></i> 
									@if (isset($technician->JobModel->address) && $technician->JobModel->address !== '')
                                    {{ $technician->JobModel->address }},
                                    @endif

                                    @if (isset($technician->JobModel->city) && $technician->JobModel->city !== '')
                                    {{ $technician->JobModel->city }},
                                    @endif

                                    @if (isset($technician->JobModel->state) && $technician->JobModel->state !== '')
                                    {{ $technician->JobModel->state }},
                                    @endif

                                    @if (isset($technician->JobModel->zipcode) && $technician->JobModel->zipcode !== '')
                                    {{ $technician->JobModel->zipcode }}
                                    @endif
                                </div>
                            </div>
                            <div class="desc ft12"><i class="ri-tools-line"></i> {{ $technician->technician->name ?? '' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
			<!-- End Tab Activity -->
			
			<!-- Tab Information -->
			<div class="tab-pane fade p-3" id="chat" role="tabpanel" aria-labelledby="pills-profile-tab">
				<h5 class="card-title uppercase mt-3 mb-3">Quick Links</h5>
				<ul class="list-group list-group-flush">
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('download')}}">Download App </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">View Website </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('contact')}}">Contact Support </a></li>
 					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('about')}}">About Dispat Channel</a></li>
 					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('reviews')}}">Reviews</a></li>
 					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('privacy')}}">Privacy Policy </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="{{route('documentation')}}">Documentation </a></li>
				</ul>
				<div class="mt-3 mb-3">All Rights Reserved by Frontier Tech Services. Designed and Developed by <a href="https://gaffis.com/" target="blank">Gaffis Technologies Private Limited</a>.</div>
			</div>
			<!-- End Tab Information -->
			
			
			
		</div>
		
	</div>

</aside>