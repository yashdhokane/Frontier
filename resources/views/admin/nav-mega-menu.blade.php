<a class="nav-link dropdown-toggle waves-effect waves-dark megamenutop1" role="button" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<span class="d-none d-md-block">More <i data-feather="chevron-down"
			class="feather-sm"></i></span>
	<span class="d-block d-md-none"><i class="ri-keyboard-line"></i></span>
</a>

<div class="dropdown-menu dropdown-menu-animate-up">
                     
 	<div class="mega-dropdown-menu row">
	
		<div class="col-lg-2 mb-0">
			<h5 class="mb-1">Profiles</h5>
			<ul class="list-style-none">
				<li>
					<a href="{{ route('users.index') }}"><i class="ri-group-line"></i> Customers </a>
				</li>
				<li>
					<a href="{{ route('technicians.index') }}"><i class="ri-contacts-line"></i> Technicians </a>
				</li>
				<li>
					<a href="{{ route('dispatcher.index') }}"><i class="ri-admin-line"></i> Dispatchers </a>
				</li>
				<li>
					<a href="{{ route('multiadmin.index') }}"><i class="ri-admin-fill"></i> Admin </a>
				</li>
			</ul>
		</div>
		
		<div class="col-lg-2 mb-0">
			<h5 class="mb-1">Jobs & Payments</h5>
			<ul class="list-style-none">		
				<li>
					<a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i> Schedule </a>
				</li>            
				<li>
					<a href="{{ route('map') }}"><i class="fas fa-calendar-check"></i> Reschedule </a>
				</li>
				<li>
					<a href="{{ route('tickets.index') }}"><i class="mdi mdi-book-multiple"></i>  Jobs List </a>
				</li>
				<li>
					<a href="{{ route('payment-list') }}"><i class="ri-money-dollar-box-line"></i>  Payments & Invoices </a>
				</li>
 			</ul>			
		</div>
		
		<div class="col-lg-2 mb-0">
			<h5 class="mb-1">Price Book</h5>
			<ul class="list-style-none">
				<li>
					<a href="{{ route('services.index') }}"><i class="ri-book-line"></i> Services </a>
				</li>
				<li>
					<a href="{{ route('product.index') }}"><i class="ri-book-mark-line"></i> Parts </a>
				</li>
				<li>
					<a href="{{route('assign_product')}}"><i class="ri-book-2-line"></i> Assign Parts </a>
				</li>
				<li>
					<a href="{{route('manufacturer.index')}}"><i class="ri-building-3-line"></i> Manufacturers </a>
				</li>
				<li>
					<a href="{{ route('estimate.index') }}"><i class="ri-article-line"></i> Estimate Templates </a>
				</li>
			</ul>
		</div>
  						
 		<div class="col-lg-2 mb-0">
 			<h5 class="mb-1">Settings</h5>
			<ul class="list-style-none">
 				<li>
					<a href="{{ route('buisnessprofile.index') }}"><i class="ri-file-list-line"></i> Business Profile </a>
				</li>
				<li>
					<a href="{{ route('businessHours.business-hours') }}"><i class="ri-24-hours-line"></i> Working Hours </a>
				</li>
				<li>
					<a href="{{ route('servicearea.index') }}"><i class="ri-service-line"></i> Service Area </a>
				</li>
				<li>
					<a href="{{ route('manufacturer.index') }}"><i class="ri-building-2-line"></i> Manufacturer </a>
				</li>
				<li>
					<a href="{{ route('tax.index') }}"><i class="ri-bar-chart-fill"></i> Tax </a>
				</li>
 			</ul>
		</div>
 		
		<div class="col-lg-2 mb-0">
			<h5 class="mb-1">Reporting</h5>
			<ul class="list-style-none">
				<li><a href="{{route('jobreport.index')}}"><i class="ri-file-chart-line"></i> Jobs Report</a></li>
				<li><a href="{{route('technicianreport.index')}}"><i class="ri-file-chart-line"></i> Technicians Report</a></li>
				<li><a href="{{route('employeereport.index')}}"><i class="ri-file-chart-line"></i> Employees Report</a></li>
				<li><a href="{{route('performanncematrix')}}"><i class="ri-file-chart-line"></i> Performance Matrix </a></li>
				<li><a href="{{route('fleetreport')}}"><i class="ri-file-chart-line"></i> Fleet Report </a></li>
 			</ul>
		</div>
		
		<div class="col-lg-2 mb-0">
 			<h5 class="mb-1">Help & Support</h5>
			<ul class="list-style-none">
				<li>
 					<a href="#."><i class="ri-file-list-line"></i> Contact Support </a>
				</li>
  				<li>
 					<a href="#."><i class="ri-file-list-line"></i> View Website </a>
				</li>
				<li>
 					<a href="#."><i class="ri-file-list-line"></i> Download App </a>
				</li>
 				<li>
 					<a href="#."><i class="ri-file-list-line"></i> Privacy Policy </a>
				</li>
				<li>
 					<a href="#."><i class="ri-file-list-line"></i> Documentation </a>
				</li>
  			</ul>
		</div>
		
		
	</div>

</div>