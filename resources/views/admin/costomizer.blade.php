<aside class="customizer">
	<a href="javascript:void(0)" class="service-panel-toggle"><i data-feather="settings" class="feather-sm fa fa-spin"></i></a>
	
	<div class="customizer-body">
	
        <ul class="nav customizer-tab" role="tablist">
			
			<li class="nav-item"><a class="nav-link active" id="tab-activity-tab" data-bs-toggle="pill" href="#tab-activity" role="tab" aria-controls="tab-activity" aria-selected="false" ><i class="ri-timer-line fs-6"></i></a></li>
			
			<li class="nav-item"><a class="nav-link " id="timezone-tab" data-bs-toggle="pill" href="#timezone" role="tab" aria-controls="timezone" aria-selected="true" ><i class="ri-tools-fill fs-6"></i></a></li>
 			
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
                    <h4 class="mb-2 mt-2">Current Time</h4>
                    <div class="mt-2">
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
                    <h4 class="mb-2 mt-4">Timezone</h4>
                    <div class="mt-2">
                        {{ $time->TimeZone->timezone_name }}<br />{{ $time->TimeZone->gmt_offset }} Hours
                    </div>
                </div>
            </div>
            <!-- End Tab Timezone -->
			
			   @php
            $today = now()->toDateString(); // Get today's date
            $technicians = App\Models\JobModel::with('user', 'usertechnician')
            ->whereDate('created_at', $today) // Filter by today's date
            ->orderBy('created_at', 'desc')
            ->get();
            @endphp

																							
			<!-- Tab Activity -->
		 <div class="tab-pane fade p-3 show active" id="tab-activity" role="tabpanel"
                aria-labelledby="tab-activity-tab">
                <h4 class="mt-3 mb-3">Job Timeline</h4>
                <div class="steamline">
                    @foreach ($technicians as $technician)
                    <div class="sl-item">
                        <div class="sl-left bg-light-success text-success"><i class="ri-calendar-check-fill"></i></div>
                        <div class="sl-right">
                            <div class="font-medium">
                                Ticket: {{ $technician->job_title ?? '' }}
                                Customer: {{ $technician->user->name ?? '' }}
                                <span class="sl-date" style="display: block;">
                                    ADDRESS:
                                    @if (isset($technician->address) && $technician->address !== '')
                                    {{ $technician->address }},
                                    @endif

                                    @if (isset($technician->city) && $technician->city !== '')
                                    {{ $technician->city }},
                                    @endif

                                    @if (isset($technician->state) && $technician->state !== '')
                                    {{ $technician->state }},
                                    @endif

                                    @if (isset($technician->zipcode) && $technician->zipcode !== '')
                                    {{ $technician->zipcode }}
                                    @endif
                                </span>
                            </div>
                            <div class="desc">Technician: {{ $technician->usertechnician->name ?? '' }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
			<!-- End Tab Activity -->
			
			<!-- Tab Information -->
			<div class="tab-pane fade p-3" id="chat" role="tabpanel" aria-labelledby="pills-profile-tab">
				<h4 class="mt-3 mb-3">Information</h4>
				<ul class="list-group list-group-flush">
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Contact Support </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">View Website </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Download App </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Privacy Policy </a></li>
					<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Documentation </a></li>
				</ul>
				<div class="mt-3 mb-3">All Rights Reserved by Frontier Tech Services. Designed and Developed by <a href="https://gaffis.com/" target="blank">Gaffis Technologies Private Limited</a>.</div>
			</div>
			<!-- End Tab Information -->
			
			
			
		</div>
		
	</div>

</aside>