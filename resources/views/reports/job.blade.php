@extends('home')

@section('content')

<div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
		<div class="page-breadcrumb">
			<div class="row">
				<div class="col-5 align-self-center">
					<h3 class="page-title">Job Report</h3>
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
       
              
			  
<div class="container">
	
	<div class="row">
	
		<div class="col-md-3">
			<div class="card">
 				<div class="card-body">
					<h4 class="card-title">Date</h4>
					<ul class="list-group list-group-flush" style="margin-left: -20px;">
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=job_revenue">Job revenue earned</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=average_job_size">Average job size</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=job_count">Job count</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=daily">Daily</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=weekly">Weekly</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=monthly">Monthly</a></li>
					</ul>
				</div>
			</div>
 		</div>
		
		<div class="col-md-3">
			<div class="card">
 				<div class="card-body">
					<h4 class="card-title">Type</h4>
					<ul class="list-group list-group-flush" style="margin-left: -20px;">
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=job_tags">Job tags</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=job_fields">Job type</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=job_lead_source">Job lead source</a></li>
 					</ul>
				</div>
			</div>
 		</div>
		
		<div class="col-md-3">
			<div class="card">
 				<div class="card-body">
					<h4 class="card-title">Customer</h4>
					<ul class="list-group list-group-flush" style="margin-left: -20px;">
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=customer">Customer name</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=zipcode">Zip code</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=city">City</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=state">State</a></li>
  					</ul>
				</div>
			</div>
 		</div>
		
		<div class="col-md-3">
			<div class="card">
 				<div class="card-body">
					<h4 class="card-title">Other</h4>
					<ul class="list-group list-group-flush" style="margin-left: -20px;">
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=manufacturers">Manufacturers</a></li>
						<li class="list-group-item"><i class="ri-bar-chart-line mx-2"></i> <a href="jobs-report-data.php?type=appliances">Appliances</a></li>
   					</ul>
				</div>
			</div>
 		</div>
  		
		
	</div>
	 
	 
	
</div>



			  
              
            </div>
          </div>
        </div>
          </div>
        </div>
        @stop