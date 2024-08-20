@extends('home')

@section('content')

    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->



    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">{{ $data }}</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="https://dispatchannel.com/portal/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('jobreport.index') }}">Job Report</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $data }}</li>
                        </ol>
                    </nav>
                </div>
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
        
		<div class="card">
			
			<div class="card-body card-border shadow">

				<div class="row">


					@if ($_REQUEST['type'] == 'job_revenue')
						
					
							<div class="col-md-12">
								<div class="table-responsive table-custom2">
									<table class="table table-hover table-striped customize-table mb-0 v-middle" id="jobRevenue">
										<thead class="table-light">
											<tr>
												<th class="border-bottom border-top">Jobs by Scheduled Day </th>
												<th class="border-bottom border-top">Job Revenue</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($jobs as $job)
												<tr>
													<td>{{ \Carbon\Carbon::parse($job->date)->format('M d, Y') }}</td>
													<td>${{ number_format($job->daily_gross_total, 2) }}</td>
												</tr>
											@endforeach


										</tbody>
									</table>
								</div>
							</div>
						
					@elseif($_REQUEST['type'] == 'average_job_size')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="monthjobRevenue">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Month </th>
											<th class="border-bottom border-top">Average Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($monthJobs as $monthJob)
											<tr>
												<td> <?php
												$startOfMonth = date('M 01, Y', mktime(0, 0, 0, $monthJob->month, 1));
												$endOfMonth = date('M t, Y', mktime(0, 0, 0, $monthJob->month, 1));
												?>
													{{ $startOfMonth }} - {{ $endOfMonth }}
												</td>
												<td>${{ number_format($monthJob->monthly_gross_total, 2) }}</td>
											</tr>
										@endforeach



									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'job_count')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="job_count">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Scheduled Day </th>
											<th class="border-bottom border-top">Job Count</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($monthJobscount as $item)
											<tr>

												<td>{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</td>
												<td>{{ $item->job_count }}</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'daily')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="daily">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Scheduled Day </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($daily as $item)
											<tr>
												<td>{{ date('M d, Y', strtotime($item->date)) }}</td>
												<td>${{ number_format($item->daily_gross_total, 2) }}</td>
												<td>{{ $item->job_count }}</td>
												<td>${{ number_format($item->daily_gross_total / $item->job_count, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'weekly')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="weekly">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Month </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($weekly as $item)
											<tr>
												<td>
													<?php
													// Get the week number
													$weekNumber = substr($item->week, 4); // Extract the week number from the ISO week format
													$year = substr($item->week, 0, 4); // Extract the year from the ISO week format
													// Calculate the start date of the week
													$startDate = \Carbon\Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
													// Check if it's the current week
													if ($startDate->startOfWeek()->isCurrentWeek()) {
														$startDate = \Carbon\Carbon::now()->startOfWeek();
													}
													// Calculate the end date of the week
													$endDate = \Carbon\Carbon::now()->setISODate($year, $weekNumber)->endOfWeek();
													// Check if it's the current week and adjust the end date
													if ($endDate->endOfWeek()->isCurrentWeek()) {
														$endDate = \Carbon\Carbon::now();
													}
													?>
													{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
												</td>
												<td>${{ number_format($item->weekly_gross_total, 2) }}</td>
												<td>{{ $item->job_count }}</td>
												<td>${{ number_format($item->weekly_gross_total / $item->job_count, 2) }}
												</td>
											</tr>
										@endforeach






									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'monthly')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="monthly">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Month </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($monthly as $month)
											<tr>
												<td>
													<?php
													// Get the month and year from the database
													$monthYear = date('M Y', mktime(0, 0, 0, $month->month, 1));
													// Calculate the start date of the month
													$startDate = date('M 01, Y', mktime(0, 0, 0, $month->month, 1));
													// Calculate the end date of the month
													$endDate = date('M t, Y', mktime(0, 0, 0, $month->month, 1));
													?>
													{{ $startDate }} - {{ $endDate }}
												</td>
												<td>${{ number_format($month->weekly_gross_total, 2) }}</td>
												<td>{{ $month->job_count }}</td>
												<td>${{ number_format($month->weekly_gross_total / $month->job_count, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'job_tags')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="job_tags">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Tags </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($tagCounts as $tag)
											<tr>
												<td>{{ $tag->tag_name }}</td>
												<td>${{ number_format($tag->total_gross_total, 2) }}</td>
												<td>{{ $tag->job_count }}</td>
												<td>${{ number_format($tag->total_gross_total / $tag->job_count, 2) }}</td>
											</tr>
										@endforeach

									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'job_fields')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="priority">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Type </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($priorityCounts as $priority)
											<tr>
												<td>{{ $priority->priority }}</td>
												<td>${{ number_format($priority->total_gross_total, 2) }}</td>
												<td>{{ $priority->job_count }}</td>
												<td>${{ number_format($priority->total_gross_total / $priority->job_count, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'Status')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="status">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Status </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($jobstatus as $item)
											<tr>
												<td>{{ $item->status }}</td>
												<td>${{ number_format($item->total_gross_total, 2) }}</td>
												<td>{{ $item->job_count }}</td>
												<td>${{ number_format($item->total_gross_total / $item->job_count, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'job_lead_source')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="job_lead_source">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Jobs by Lead Source </th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($leadSourceCounts as $leadSource)
											<tr>
												<td>{{ $leadSource->lead_source }}</td>
												<td>${{ number_format($leadSource->total_gross_total, 2) }}</td>
												<td>{{ $leadSource->job_count }}</td>
												<td>${{ number_format($leadSource->total_gross_total / $leadSource->job_count, 2) }}
												</td>
											</tr>
										@endforeach



									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'customer')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="customerReport">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Customer Name</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($customerDetails as $customer)
											<tr>
												<td>{{ $customer->name }}</td>
												<td>${{ number_format($customer->total_revenue, 2) }}</td>
												<td>{{ $customer->total_jobs }}</td>
												<td>${{ number_format($customer->total_revenue / $customer->total_jobs, 2) }}
												</td>
											</tr>
										@endforeach

									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'zipcode')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="zipcodeReport">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Zip Code</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($zipCodeDetails as $zipCodeDetail)
											<tr>
												<td>{{ $zipCodeDetail->zipcode }}</td>
												<td>${{ $zipCodeDetail->total_gross_total }}</td>
												<td>{{ $zipCodeDetail->job_count }}</td>
												@if ($zipCodeDetail->job_count > 0)
													<td> ${{ intval($zipCodeDetail->total_gross_total / $zipCodeDetail->job_count) }}
													</td>
												@else
													<td>$0</td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'city')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="cityReport">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">City</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($cityDetails as $cityDetail)
											<tr>
												<td>{{ $cityDetail->city }}</td>
												<td>${{ $cityDetail->total_gross_total }}</td>
												<td>{{ $cityDetail->job_count }}</td>
												@if ($cityDetail->job_count > 0)
													<td> ${{ intval($cityDetail->total_gross_total / $cityDetail->job_count) }}
													</td>
												@else
													<td>$0</td>
												@endif
											</tr>
										@endforeach

									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'state')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="stateReport">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">state</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($stateDetails as $stateDetail)
											<tr>
												<td>{{ $stateDetail->state }}</td>
												<td>${{ $stateDetail->total_gross_total }}</td>
												<td>{{ $stateDetail->job_count }}</td>
												@if ($stateDetail->job_count > 0)
													<td> ${{ intval($stateDetail->total_gross_total / $stateDetail->job_count) }}
													</td>
												@else
													<td>$0</td>
												@endif
											</tr>
										@endforeach

									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'manufacturers')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="manufacturers">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Manufacturers</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($CountsManufacturer as $manufacturer)
											<tr>
												<td>{{ $manufacturer->manufacturer_name }}</td>
												<td>${{ number_format($manufacturer->total_revenue, 2) }}</td>
												<td>{{ $manufacturer->total_jobs }}</td>
												<td>${{ number_format($manufacturer->total_revenue / $manufacturer->total_jobs, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'appliances')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								<table class="table table-hover table-striped customize-table mb-0 v-middle" id="appliances">
									<thead class="table-light">
										<tr>
											<th class="border-bottom border-top">Appliances</th>
											<th class="border-bottom border-top">Job Revenue</th>
											<th class="border-bottom border-top">Job Count</th>
											<th class="border-bottom border-top">Avg. Job Size</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($CountsAppliance as $appliance)
											<tr>
												<td>{{ $appliance->appliance_name }}</td>
												<td>${{ number_format($appliance->total_revenue, 2) }}</td>
												<td>{{ $appliance->total_jobs }}</td>
												<td>${{ number_format($appliance->total_revenue / $appliance->total_jobs, 2) }}
												</td>
											</tr>
										@endforeach


									</tbody>
								</table>
							</div>
						</div>
					@endif


				</div>
			
			</div>
		
		</div>
 
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

 
    <script src="{{ url('public/admin/reports/script.js') }}"></script>
	
	
	<script>
        $(document).ready(function() {
            // Initialize DataTables for all IDs
            var tableIds = ['jobRevenue','monthjobRevenue',
                'job_count', 'daily', 'weekly', 'monthly', 'job_tags', 'priority', 'status',
                'job_lead_source', 'customerReport', 'zipcodeReport', 'cityReport', 'stateReport',
                'manufacturers', 'appliances'
            ];

            $.each(tableIds, function(index, tableId) {
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    $('#' + tableId).DataTable().destroy();
                }

                $('#' + tableId).DataTable({
                    "order": [[0, "desc"]],
                    "pageLength": 25
                    // Add more options as needed
                });
            });
        });
    </script>
@stop

