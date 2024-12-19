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
			 <div class="col-7 align-self-center">
			 
				 <!-- Dropdown Selection -->
				<div class="row">
					<div class="col-md-12 mb-1X">
						<h6 class="">Filters</h6>
					</div>
					<div class="col-md-3">
						<select id="dataFilter" class="form-control">
							<option value="">Select All</option>
							<option value="date_range">Select Dates</option>
							<option value="month">Select Month</option>
							<option value="year">Select Year</option>
						</select>
					</div>
					
					<!-- Date Range Picker -->
					<div class="col-md-3" id="dateRangePicker" style="display: none;">
						<input type="date" id="fromDate" class="form-control" placeholder="From Date">
					</div>
					<div class="col-md-3" id="toDatePicker" style="display: none;">
						<input type="date" id="toDate" class="form-control" placeholder="To Date">
					</div>

					<!-- Month Dropdown -->
					<div class="col-md-3" id="monthPicker" style="display: none;">
						<select id="selectMonth" class="form-control">
							<option value="">Select Month</option>
							@foreach(range(1,12) as $month)
								<option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }} 2024</option>
							@endforeach
						</select>
					</div>

					<!-- Year Dropdown -->
					<div class="col-md-3" id="yearPicker" style="display: none;">
						<select id="selectYear" class="form-control">
							<option value="">Select Year</option>
						    @foreach(range(2023, now()->year ) as $year)
				           <option value="{{ $year }}">{{ $year }}</option>
				            @endforeach

						</select>
					</div>
					<div class="col-md-3" id="yearPicker" style="display: none;">
						<select id="selectYear" class="form-control">
							<option value="">Select Year</option>
						    @foreach(range(2023, now()->year ) as $year)
				           <option value="{{ $year }}">{{ $year }}</option>
				            @endforeach

						</select>
					</div>
				
                  <div class="col-md-3 text-right">
                 <button id="submitButton" class="btn btn-primary">Submit</button>
                 </div>


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
        <div class="container">
    
	<?php 
	/*
	<!-- Dropdown Selection -->
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="dataFilter" class="form-control">
                <option value="">Select All</option>
                <option value="date_range">Select Dates</option>
                <option value="month">Select Month</option>
                <option value="year">Select Year</option>
            </select>
        </div>
        
        <!-- Date Range Picker -->
        <div class="col-md-3" id="dateRangePicker" style="display: none;">
            <input type="date" id="fromDate" class="form-control" placeholder="From Date">
        </div>
        <div class="col-md-3" id="toDatePicker" style="display: none;">
            <input type="date" id="toDate" class="form-control" placeholder="To Date">
        </div>

        <!-- Month Dropdown -->
        <div class="col-md-4" id="monthPicker" style="display: none;">
            <select id="selectMonth" class="form-control">
                <option value="">Select Month</option>
                @foreach(range(1,12) as $month)
                    <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }} 2024</option>
                @endforeach
            </select>
        </div>

        <!-- Year Dropdown -->
        <div class="col-md-4" id="yearPicker" style="display: none;">
            <select id="selectYear" class="form-control">
                <option value="">Select Year</option>
               @foreach(range(1900, now()->year ) as $year)
    <option value="{{ $year }}">{{ $year }}</option>
     @endforeach

            </select>
        </div>
    </div>
	*/ 
	?>

    <!-- Table Section -->

	
  </div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
		<div class="card">
			
			<div class="card-body card-border shadow">

				    <div class="row">


					@if (request('type') == 'job_revenue' || empty(request('type')))

						 <div id="dataTable" class="table-responsive table-custom2">
              
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
            <?php
            $monthName = date('F', mktime(0, 0, 0, $monthJob->month, 1));
            $year = date('Y', mktime(0, 0, 0, $monthJob->month, 1));
            ?>
            <tr 
                data-month="{{ $monthJob->month }}" 
                data-year="{{ $year }}" 
                data-date-range="{{ date('Y-m', mktime(0, 0, 0, $monthJob->month, 1)) }}">
                <td>{{ $monthName }} {{ $year }}</td>
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
                    <tr 
                        data-date="{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}" 
                        data-month="{{ \Carbon\Carbon::parse($item->date)->month }}" 
                        data-year="{{ \Carbon\Carbon::parse($item->date)->year }}">
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
                    <tr 
                        data-date="{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}" 
                        data-month="{{ \Carbon\Carbon::parse($item->date)->month }}" 
                        data-year="{{ \Carbon\Carbon::parse($item->date)->year }}">
                        <td>{{ date('M d, Y', strtotime($item->date)) }}</td>
                        <td>${{ number_format($item->daily_gross_total, 2) }}</td>
                        <td>{{ $item->job_count }}</td>
                        <td>${{ number_format($item->daily_gross_total / $item->job_count, 2) }}</td>
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
                    <?php
                    // Get the week number
                    $weekNumber = substr($item->week, 4); // Extract the week number from the ISO week format
                    $year = substr($item->week, 0, 4); // Extract the year from the ISO week format
                    // Calculate the start date of the week
                    $startDate = \Carbon\Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
                    // Calculate the end date of the week
                    $endDate = \Carbon\Carbon::now()->setISODate($year, $weekNumber)->endOfWeek();
                    ?>
                    <tr 
                        data-week="{{ $item->week }}" 
                        data-year="{{ $year }}" 
                        data-month="{{ $startDate->month }}">
                        <td>{{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</td>
                        <td>${{ number_format($item->weekly_gross_total, 2) }}</td>
                        <td>{{ $item->job_count }}</td>
                        <td>${{ number_format($item->weekly_gross_total / $item->job_count, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
							</div>
						</div>
					@elseif($_REQUEST['type'] == 'monthly')
						<div class="col-md-12">
							<div class="table-responsive table-custom2">
								 <table class="table table-hover table-striped customize-table mb-0 v-middle" id="monthly">
            <thead class="table-light">
                <tr>
                    <th class="border-bottom border-top">Jobs by Month</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthly as $month)
                    <?php
                    // Get the month and year from the database
                    $monthYear = date('M Y', mktime(0, 0, 0, $month->month, 1));
                    // Calculate the start date of the month
                    $startDate = date('M 01, Y', mktime(0, 0, 0, $month->month, 1));
                    // Calculate the end date of the month
                    $endDate = date('M t, Y', mktime(0, 0, 0, $month->month, 1));
                    ?>
                    <tr 
                        data-month="{{ $month->month }}" 
                        data-year="{{ date('Y', mktime(0, 0, 0, $month->month, 1)) }}">
                        <td>{{ $startDate }} - {{ $endDate }}</td>
                        <td>${{ number_format($month->weekly_gross_total, 2) }}</td>
                        <td>{{ $month->job_count }}</td>
                        <td>${{ number_format($month->weekly_gross_total / $month->job_count, 2) }}</td>
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
                    <th class="border-bottom border-top">Jobs by Tags</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tagCounts as $tag)
                    <tr 
                        data-tag="{{ $tag->tag_name }}">
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
                    <th class="border-bottom border-top">Jobs by Type</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($priorityCounts as $priority)
                    <tr data-priority="{{ $priority->priority }}">
                        <td>{{ $priority->priority }}</td>
                        <td>${{ number_format($priority->total_gross_total, 2) }}</td>
                        <td>{{ $priority->job_count }}</td>
                        <td>${{ number_format($priority->total_gross_total / $priority->job_count, 2) }}</td>
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
                    <th class="border-bottom border-top">Status</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($jobstatus as $item)
        <tr data-status="{{ $item->status }}" 
            data-created-at="{{ $item->created_at }}" 
            data-month="{{ \Carbon\Carbon::parse($item->created_at)->format('m') }}" 
            data-year="{{ \Carbon\Carbon::parse($item->created_at)->format('Y') }}">
            
            <td>{{ $item->status }}</td>
            <td>${{ number_format($item->total_gross_total, 2) }}</td>
            <td>{{ $item->job_count }}</td>
            <td>${{ number_format($item->total_gross_total / $item->job_count, 2) }}</td>
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
                    <th class="border-bottom border-top">Jobs by Lead Source</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leadSourceCounts as $leadSource)
                    <tr data-lead-source="{{ $leadSource->lead_source }}">
                        <td>{{ $leadSource->lead_source }}</td>
                        <td>${{ number_format($leadSource->total_gross_total, 2) }}</td>
                        <td>{{ $leadSource->job_count }}</td>
                        <td>${{ number_format($leadSource->total_gross_total / $leadSource->job_count, 2) }}</td>
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
                    <tr data-customer-name="{{ $customer->name }}">
                        <td>{{ $customer->name }}</td>
                        <td>${{ number_format($customer->total_revenue, 2) }}</td>
                        <td>{{ $customer->total_jobs }}</td>
                        <td>${{ number_format($customer->total_revenue / $customer->total_jobs, 2) }}</td>
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
                    <tr data-zipcode="{{ $zipCodeDetail->zipcode }}">
                        <td>{{ $zipCodeDetail->zipcode }}</td>
                        <td>${{ number_format($zipCodeDetail->total_gross_total, 2) }}</td>
                        <td>{{ $zipCodeDetail->job_count }}</td>
                        @if ($zipCodeDetail->job_count > 0)
                            <td>${{ number_format($zipCodeDetail->total_gross_total / $zipCodeDetail->job_count, 2) }}</td>
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
                    <tr data-city="{{ $cityDetail->city }}">
                        <td>{{ $cityDetail->city }}</td>
                        <td>${{ number_format($cityDetail->total_gross_total, 2) }}</td>
                        <td>{{ $cityDetail->job_count }}</td>
                        @if ($cityDetail->job_count > 0)
                            <td>${{ number_format($cityDetail->total_gross_total / $cityDetail->job_count, 2) }}</td>
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
                    <th class="border-bottom border-top">State</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                    <th class="border-bottom border-top">Job Count</th>
                    <th class="border-bottom border-top">Avg. Job Size</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stateDetails as $stateDetail)
                    <tr data-state="{{ $stateDetail->state }}">
                        <td>{{ $stateDetail->state }}</td>
                        <td>${{ number_format($stateDetail->total_gross_total, 2) }}</td>
                        <td>{{ $stateDetail->job_count }}</td>
                        @if ($stateDetail->job_count > 0)
                            <td>${{ number_format($stateDetail->total_gross_total / $stateDetail->job_count, 2) }}</td>
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
                    <tr data-manufacturer="{{ $manufacturer->manufacturer_name }}">
                        <td>{{ $manufacturer->manufacturer_name }}</td>
                        <td>${{ number_format($manufacturer->total_revenue, 2) }}</td>
                        <td>{{ $manufacturer->total_jobs }}</td>
                        <td>${{ number_format($manufacturer->total_revenue / $manufacturer->total_jobs, 2) }}</td>
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
                    <tr data-appliance="{{ $appliance->appliance_name }}">
                        <td>{{ $appliance->appliance_name }}</td>
                        <td>${{ number_format($appliance->total_revenue, 2) }}</td>
                        <td>{{ $appliance->total_jobs }}</td>
                        <td>${{ number_format($appliance->total_revenue / $appliance->total_jobs, 2) }}</td>
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
document.addEventListener("DOMContentLoaded", function () {
    const filterType = document.getElementById("dataFilter");
    const fromDateInput = document.getElementById("fromDate");
    const toDateInput = document.getElementById("toDate");
    const monthInput = document.getElementById("selectMonth");
    const yearInput = document.getElementById("selectYear");

    // Select all tables
    const tableRowsMonthJobRevenue = document.querySelectorAll("#monthjobRevenue tbody tr");
    const tableRowsJobCount = document.querySelectorAll("#job_count tbody tr");
    const dailyRows = document.querySelectorAll("#daily tbody tr");
    const weeklyRows = document.querySelectorAll("#weekly tbody tr");
    const monthlyRows = document.querySelectorAll("#monthly tbody tr");
        const statusRows = document.querySelectorAll("#status tbody tr");


    // Handle filter submission
    document.getElementById("submitButton").addEventListener("click", function () {
        const filterValue = filterType.value;

        // Hide all rows initially
        hideAllRows();

        // Apply filter logic based on filter type
        if (filterValue === "date_range") {
            // Filter by Date Range
            const fromDate = new Date(fromDateInput.value);
            const toDate = new Date(toDateInput.value);

            // Apply date filter for each table
            filterByDateRange(tableRowsMonthJobRevenue, fromDate, toDate);
            filterByDateRange(tableRowsJobCount, fromDate, toDate);
            filterByDateRange(dailyRows, fromDate, toDate);
            filterByDateRange(weeklyRows, fromDate, toDate);
            filterByDateRange(monthlyRows, fromDate, toDate);
            filterByDateRange(statusRows, fromDate, toDate);

        } else if (filterValue === "month") {
            // Filter by Month
            const selectedMonth = monthInput.value;

            // Apply month filter for each table
            filterByMonth(tableRowsMonthJobRevenue, selectedMonth);
            filterByMonth(tableRowsJobCount, selectedMonth);
            filterByMonth(dailyRows, selectedMonth);
            filterByMonth(weeklyRows, selectedMonth);
            filterByMonth(monthlyRows, selectedMonth);
            filterByMonth(statusRows, selectedMonth);

        } else if (filterValue === "year") {
            // Filter by Year
            const selectedYear = yearInput.value;

            // Apply year filter for each table
            filterByYear(tableRowsMonthJobRevenue, selectedYear);
            filterByYear(tableRowsJobCount, selectedYear);
            filterByYear(dailyRows, selectedYear);
            filterByYear(weeklyRows, selectedYear);
            filterByYear(monthlyRows, selectedYear);
            filterByYear(statusRows, selectedYear);

        } else if (filterValue === "created_at") {
            // Filter by Created At (Single Date)
            const selectedDate = fromDateInput.value; // Using "fromDate" as an example for date selection

            // Apply created_at filter for each table
            filterByCreatedAt(tableRowsMonthJobRevenue, selectedDate);
            filterByCreatedAt(tableRowsJobCount, selectedDate);
            filterByCreatedAt(dailyRows, selectedDate);
            filterByCreatedAt(weeklyRows, selectedDate);
            filterByCreatedAt(monthlyRows, selectedDate);
            filterByCreatedAt(statusRows, selectedDate);

        } else {
            // Show All Data
            showAllRows(tableRowsMonthJobRevenue);
            showAllRows(tableRowsJobCount);
            showAllRows(dailyRows);
            showAllRows(weeklyRows);
            showAllRows(monthlyRows);
            showAllRows(statusRows);

        }
    });

    // Helper function to hide all rows
    function hideAllRows() {
        hideRows(tableRowsMonthJobRevenue);
        hideRows(tableRowsJobCount);
        hideRows(dailyRows);
        hideRows(weeklyRows);
        hideRows(monthlyRows);
         hideRows(statusRows);
    }

    function hideRows(rows) {
        rows.forEach(row => row.style.display = "none");
    }

    // Helper function to show all rows
    function showAllRows(rows) {
        rows.forEach(row => row.style.display = "");
    }

    // Function to filter by date range
    function filterByDateRange(rows, fromDate, toDate) {
        rows.forEach(row => {
            const rowDate = new Date(row.dataset.date);
            if (rowDate >= fromDate && rowDate <= toDate) {
                row.style.display = ""; // Show matching row
            }
        });
    }

    // Function to filter by month
    function filterByMonth(rows, selectedMonth) {
        rows.forEach(row => {
            if (row.dataset.month === selectedMonth) {
                row.style.display = ""; // Show matching row
            }
        });
    }

    // Function to filter by year
    function filterByYear(rows, selectedYear) {
        rows.forEach(row => {
            if (row.dataset.year === selectedYear) {
                row.style.display = ""; // Show matching row
            }
        });
    }

    // Function to filter by created_at (single date)
    function filterByCreatedAt(rows, selectedDate) {
        rows.forEach(row => {
            const createdAt = new Date(row.dataset.createdAt); // Assuming data-created-at attribute exists
            const filterDate = new Date(selectedDate);
            if (createdAt.toDateString() === filterDate.toDateString()) {
                row.style.display = ""; // Show matching row
            }
        });
    }
});

	</script>

<script>
$(document).ready(function () {
    // Initialize DataTables
    let jobRevenueTable = $('#jobRevenue').DataTable({
        "order": [[0, "desc"]], // Default sorting by the first column
        "pageLength": 25, // Set the number of rows per page
        "searching": true,  // Enable search functionality
        "paging": true, // Enable pagination
        "info": true, // Show table info like "Showing 1 to 25 of 100 entries"
    });

    let urlParams = new URLSearchParams(window.location.search);
    let filterTypeFromUrl = urlParams.get('type'); // Get the 'type' parameter from the URL

    // Show/hide date/month/year pickers based on dropdown selection
    $('#dataFilter').on('change', function () {
        let filterType = $(this).val();
        $('#dateRangePicker, #toDatePicker, #monthPicker, #yearPicker').hide();

        if (filterType === 'date_range') {
            $('#dateRangePicker, #toDatePicker').show();
        } else if (filterType === 'month') {
            $('#monthPicker').show();
        } else if (filterType === 'year') {
            $('#yearPicker').show();
        }
    });

    // Function to fetch data using AJAX
    function fetchData() {
        let filterType = $('#dataFilter').val(); // Get the selected filter type from the dropdown
        let fromDate = $('#fromDate').val();
        let toDate = $('#toDate').val();
        let month = $('#selectMonth').val();
        let year = $('#selectYear').val();

        // AJAX request to fetch data
        $.ajax({
            url: "{{ route('data_report.fetch') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                type: filterType,
                type_from_url: filterTypeFromUrl,
                from_date: fromDate,
                to_date: toDate,
                month: month,
                year: year,
            },
            beforeSend: function () {
                $('#dataTable').html('<p class="text-center">Loading...</p>');
            },
            success: function (response) {
                // Update table content
                $('#dataTable').html(response);

                // Reinitialize DataTables for the updated content
                if ($.fn.DataTable.isDataTable('#jobRevenue')) {
                    $('#jobRevenue').DataTable().destroy();
                }

                $('#jobRevenue').DataTable({
                    "order": [[0, "desc"]],
                    "pageLength": 25,
                    "searching": true,
                    "paging": true,
                    "info": true,
                });
            },
            error: function () {
                alert('An error occurred while fetching data.');
            }
        });
    }

    // Default: Load all data on page load
    fetchData();

    // Fetch filtered data on Submit button click
    $('#submitButton').on('click', function () {
        fetchData(); // Fetch data based on filters
    });
});
</script>

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

