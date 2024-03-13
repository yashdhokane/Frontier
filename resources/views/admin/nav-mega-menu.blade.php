<a class="nav-link dropdown-toggle waves-effect waves-dark megamenutop1" role="button" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<span class="d-none d-md-block">More <i data-feather="chevron-down"
			class="feather-sm"></i></span>
	<span class="d-block d-md-none"><i class="ri-keyboard-line"></i></span>
</a>

<div class="dropdown-menu dropdown-menu-animate-up">
                     
 	<div class="mega-dropdown-menu row">
		<div class="col-lg-3 mb-4">
			<h4 class="mb-3">Price Book</h4>
			<ul class="list-style-none">
				<li><a href="{{ route('services.index') }}" class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Services
						</span></a></li>
				<li><a href="{{ route('product.index') }}" class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Materials
						</span></a></li>
				<li><a href="{{ route('estimate.index') }}" class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Estimate
							Templates </span></a></li>
			</ul>
		</div>
		<div class="col-lg-3 mb-4">
			<h4 class="mb-3">Settings</h4>
			<ul class="list-style-none">
				<li><a href="{{ route('buisnessprofile.index') }}" class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Profile
						</span></a></li>
				<li><a href="{{ route('businessHours.business-hours') }} " class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Business
							Hours </span></a></li>
				<li><a href="{{ route('servicearea.index') }}" class="sidebar-link"><i
							class="mdi mdi-book-multiple"></i><span class="hide-menu"> Service Area
						</span></a></li>
			</ul>
		</div>
		<div class="col-lg-3 col-xl-2 mb-4">
			<h4 class="mb-3">Profiles</h4>
			<ul class="list-style-none">
				<li><a href="{{ route('technicians.index')}}"><i class="fas fa-user"></i>
						Technicians</a></li>
				<li><a href="{{ route('users.index') }}"><i class="fas fa-user-plus"></i>
						Customers</a></li>
				<li><a href="{{ route('dispatcher.index') }}"><i class="fas fa-user-plus"></i>
						Dispatchers</a></li>
			</ul>
		</div>
		<div class="col-lg-3 mb-4">
			<h4 class="mb-3">Reporting</h4>
			<ul class="list-style-none">
				<li><a href="#."><i class="fas fa-list"></i> Technician Earnings</a></li>
				<li><a href="#."><i class="fas fa-list"></i> Call Monitoring</a></li>
				<li><a href="{{route('performanncematrix')}}"><i class="fas fa-list"></i> Performance Matrix</a></li>
			</ul>
		</div>
	</div>

</div>