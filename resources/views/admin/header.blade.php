@auth

@if(auth()->user()->role == 'dispatcher')

@elseif(auth()->user()->role == 'admin')


@elseif(auth()->user()->role == 'superadmin')


@else

@endif
@endauth



<!-- Default Sidebar for other roles -->
<!-- Add your default sidebar code here -->
<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-dark">

        <div class="navbar-header">
			@include('admin.nav-logo')
        </div>
         
		<div class="navbar-collapse collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto">

                <li class="nav-item d-none d-md-block">
					<a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i data-feather="menu" class="feather-sm"></i></a>
                </li>

                <li class="toplinks"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>

                <li @if (request()->routeIs('schedule')) class="toplinks selected" @else class="toplinks" @endif
                    class="toplinks"><a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i> Schedule</a></li>

                <li class="toplinks"><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Customer</a></li>

				<!-- mega menu -->
				<li class="nav-item dropdown mega-dropdown">
                    @include('admin.nav-mega-menu')
                </li>
				<!-- End mega menu -->

                <!-- create new -->
				<li class="nav-item dropdown">
					@include('admin.nav-create-new')
				</li>
				<!-- End create new -->

                <!-- SEARCH -->
				<li class="nav-item search-box">
					@include('admin.nav-search')
				</li>
				<!-- END SEARCH -->

            </ul>

            <!-- Right side toggle and nav items -->
            <ul class="navbar-nav">
					@php
						use Carbon\Carbon;
					$currentFormattedDate = Carbon::now($timezoneName)->format('D d, M\' y');
					$currentFormattedDateTime = Carbon::now($timezoneName)->format('h:i:s A T');
					@endphp
				<li class="nav-item dropdown align-self-center px-2">
					<div class="nav-clock"><span>{{ $currentFormattedDate }}</span><br/>{{ $currentFormattedDateTime }}</div>
				</li>
				
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-dark" href="{{ route('map') }}"><i class="fas fa-map-marker-alt ft20" ></i> </a>
				</li>

                <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-dark" href="{{ route('buisnessprofile.index') }}"><i class="far fa-sun ft20" ></i></a>
				</li>

                <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle waves-effect waves-dark" href="https://dispatchannel.com/portal/reports/jobs"><i class="fas fa-chart-line ft20" ></i> </a>
				</li>

 				<!-- NOTIFICATION -->
				<li class="nav-item dropdown">
				@include('admin.nav-notification')
				</li>
				<!-- END NOTIFICATION -->

 				<!-- MESSAGES -->
				<li class="nav-item dropdown">
				@include('admin.nav-messages')
				</li>
				<!-- END MESSAGES -->

 
                <!-- USER PROFILE AND SEARCH -->
				<li class="nav-item dropdown">
					@include('admin.nav-user-profile')
				</li>
				<!-- END USER PROFILE AND SEARCH -->
 
                <link rel="stylesheet" href="{{ asset('public/admin/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
  
            </ul>

        </div>

    </nav>


</header>