@extends('home')

@section('content')
    <div class="page-breadcrumb">

        <div class="row">
             <div class="col-8">
				<h4 class="page-title"> {{$siteSettings->business_name ?? null}}</h4>
				<div class="mt-2 mb-2" style="display: none">Offering INSTALLATION and REPAIR services for all major appliances at your home or business.<br/>
				We are Authorized Service centers for the following brands who trust our trained technicians to service their products properly.</div>
 			</div>
			<div class="col-4">
				<div class="text-end">Thu 28 Mar, 2024</div>
			</div>
         </div>

    </div>

    <!-- -------------------------------------------------------------- -->

    <!-- End Bread crumb and right sidebar toggle -->

    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->

    <!-- Container fluid  -->

    <!-- -------------------------------------------------------------- -->

    <div class="container-fluid">

		<div class="row">
			<div class="col-8">
 			 
				
  				<div class="row">
					<div class="col-lg-12">
						<div class="card card-border">
							<div class="card-body">
								<div class="row mt-1">
									<div class="col-md-6 col-lg-3 col-xlg-3">
										<div class="card card-hover">
											<div class="p-2 rounded bg-light-primary text-center">
												<h1 class="fw-light text-primary">{{$totalCalls}}</h1>
												<h6 class="text-primary">Total Jobs</h6>
											</div>
										</div>
 									</div>
									<div class="col-md-6 col-lg-3 col-xlg-3">
										<div class="card card-hover">
											<div class="p-2 rounded bg-light-success text-center">
											<h1 class="fw-light text-success">{{$opened}}</h1>
											<h6 class="text-success">Open Jobs</h6>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3 col-xlg-3">
										<div class="card card-hover">
											<div class="p-2 rounded bg-light-danger text-center">
												<h1 class="fw-light text-danger">{{$complete}}</h1>
												<h6 class="text-danger">Closed Jobs</h6>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3 col-xlg-3">
										<div class="card card-hover">
											<div class="p-2 rounded bg-light-warning text-center">
												<h1 class="fw-light text-warning">{{$inProgress}}</h1>
												<h6 class="text-warning">In Progress</h6>
											</div>
										</div>
									</div>
								</div>
								
								<div class="d-md-flex align-items-center mt-2">
									<div>
										<h4 class="card-title">Recent Tickets</h4>
										<h5 class="card-subtitle">Overview of Recent Tickets</h5>
									</div>
								</div>
								<div class="table-responsive mt-1">
									<table id="" class="table table-bordered text-nowrap">
										<thead>
											<tr>
												
												<th>Jobs Details</th>
											
												<th>Customer</th>
											
												<th>Technician</th>
													<th>Date</th>
											</tr>
										</thead>
										<tbody>
										@foreach ($job as $item)
											<tr>
												<td>
												<a href="{{ url('tickets/' . $item->id) }}"
												class="font-medium link">{{ $item->job_title ?? null }}</a><br />
												@if ($item->JobAppliances)
												<span style="font-size:12px;">
												Model: {{ $item->JobAppliances->Appliances->model_number ?? 'N/A' }} /
												Serial Number: {{ $item->JobAppliances->Appliances->serial_number ?? 'N/A' }}
												</span>
												@else
												<span style="font-size:12px;">
												Model: N/A / Serial Number: N/A
												</span>
												@endif
												</td>
												<td>{{ $item->user->name ?? null }}</td>
												<td>{{ $item->technician->name ?? null }}</td>
<td>
                                            @if ($item->jobassignname && $item->jobassignname->start_date_time)
                                            <div class="font-medium link">{{
                                                $convertDateToTimezone($item->jobassignname->start_date_time ?? null) }}</div>
                                            @else
                                            <div></div>
                                            @endif
                                            <div style="font-size:12px;">
                                                {{ $convertTimeToTimezone($item->JobAssign->start_date_time ?? null, 'H:i:a')
                                                }}
                                                to {{ $convertTimeToTimezone($item->JobAssign->end_date_time ?? null, 'H:i:a')
                                                }}
                                            </div>
                                        </td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row mt-3">
					<div>
						<h4 class="card-title mb-2">Active Technicians</h4>
 					</div>
				</div>
	<div class="row">
                @foreach($technicianuser as $item)
					<div class="col-lg-4">
						<div class="card card-border">
						<div class="card-body">
						  <h5 class="card-title"> {{$item->name ?? null}}</h5>
						  <h6 class="card-subtitle mb-2 text-muted d-flex align-items-center">
							<i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>  @if(isset($item->area_name) && !empty($item->area_name))
                                    {{ $item->area_name ?? null }}
                                    @endif
						  </h6>
						  <p class="card-text pt-2">
                        {{ $item->completed_jobs_count }}/{{ $item->total_jobs_count }} Job Completed<br/>
                        Completion Rate: {{ number_format($item->completion_rate, 2) }}%
                    </p>
<a href="{{ route('technicians.show', ['id' => $item->id]) }}" class="card-link">View Profile</a>
						</div>
						</div>
					</div>
                    @endforeach
					
                </div>


				<div class="row mt-3">
					<div>
						<h4 class="card-title mb-2">Top Customers</h4>
 					</div>
				</div>
<div class="row">
    @foreach ($customeruser as $user)
        <div class="col-lg-4">
            <div class="card card-border">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name ?? null }}</h5>
                    @foreach ($user->user_addresses as $address)
                        <h6 class="card-subtitle mb-2 text-muted d-flex align-items-center">
                            <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
                            {{ $address->address_line1 ?? null}}, {{ $address->city ?? null }}, {{ $address->state_name ?? null }}, {{ $address->zipcode ?? null }}
                        </h6>
                    @endforeach
                    <p class="card-text pt-2">
                        {{ count($user->jobs) }} Jobs<br/>
                    LifetimeValue: ${{ $user->gross_total ?? 0}}</p>
                    <a href="{{ route('users.show', ['id' => $user->id]) }}" class="card-link">View Profile</a>
                </div>
            </div>
        </div>
    @endforeach
</div>


				
				
    
			</div>
			
			<div class="col-4">
			
				<div class="alert alert-success" role="alert">
					<h4 class="alert-heading">Welcome to {{$siteSettings->business_name ?? null}}!</h4>
					<p class="mt-1">{!! $siteSettings->description_long ?? null !!}</p>
 				</div>

 				<div class="card card-border">
					<div class="card-body">
 						<div class="row">			
							<div class="col-6 mb-3">			
								<div class="text-white bg-primary rounded">
									<div class="card-body">
										<span><i class="ri-group-line" style="font-size: 36px;"></i></span>
										<h3 class="card-title mt-1 mb-0 text-white">{{$customerCount ?? null}}</h3>
										<p class="card-text text-white-50 fs-3 fw-normal">Customers</p>
									</div>
								</div>
							</div>
							<div class="col-6 mb-3">		
								<div class="text-white bg-warning rounded">
									<div class="card-body">
										<span><i class="ri-contacts-line" style="font-size: 36px;"></i></span>
										<h3 class="card-title mt-1 mb-0 text-white">{{$technicianCount ?? null}}</h3>
										<p class="card-text text-white-50 fs-3 fw-normal">Technicians</p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">			
							<div class="col-6">			
								<div class="text-white bg-success rounded">
									<div class="card-body">
										<span><i class="ri-admin-line" style="font-size: 36px;"></i></span>
										<h3 class="card-title mt-1 mb-0 text-white">{{$dispatcherCount ?? null}}</h3>
										<p class="card-text text-white-50 fs-3 fw-normal">Dispatchers</p>
									</div>
								</div>
							</div>
							<div class="col-6">			
								<div class="text-white bg-danger rounded">
									<div class="card-body">
										<span><i class="ri-admin-fill" style="font-size: 36px;"></i></span>
										<h3 class="card-title mt-1 mb-0 text-white">{{$adminCount ?? null}}</h3>
										<p class="card-text text-white-50 fs-3 fw-normal">Admin</p>
									</div>
								</div>
							</div>
						</div>
 					</div>
				</div>
				
				<div class="card card-border mt-4">
					<div class="card-body">
						<h4 class="card-title">Jobs by Service Types</h4>
						<div id="chart-pie-simple"></div>
					</div>
				</div>
				
				<div class="card card-border mt-4">
					<div class="card-body">
						<h4 class="card-title">Jobs by Manufacturer</h4>
						<div id="chart-pie-donut"></div>
					</div>
				</div>
				
 				<div class="card card-border mt-4">
					<div class="card-body">
						<h4 class="card-title">Help & Support</h4>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Contact Support </a></li>
							<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">View Website </a></li>
							<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Download App </a></li>
							<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Privacy Policy </a></li>
							<li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a href="#.">Documentation </a></li>
						</ul>
					</div>
				</div>
 
 				
 			
			</div>
			
 		</div>
        

         
 
        <!-- -------------------------------------------------------------- -->

        <!-- Recent comment and chats -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <!-- column -->

            <div class="col-lg-6">

                <br />

            </div>

            <!-- column -->

            <div class="col-lg-6">

                <br />

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Recent comment and chats -->

        <!-- -------------------------------------------------------------- -->
    </div>

    </div>
@endsection
