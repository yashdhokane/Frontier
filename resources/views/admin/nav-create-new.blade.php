<a class="nav-link dropdown-toggle lightbg" href="#" id="navbarDropdown" role="button"
	data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	<span class="d-none d-md-block"><i class="fas fa-plus"></i> Create New <i
			data-feather="chevron-down" class="feather-sm"></i></span>
	<span class="d-block d-md-none"><i data-feather="plus" class="feather-sm"></i></span>
</a>
<div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="navbarDropdown">
	<a class="dropdown-item" href="{{ route('users.create') }}">Customer</a>
	<a class="dropdown-item" href="{{ route('technicians.create') }}">Technician</a>
	<a class="dropdown-item" href="{{ route('schedule') }}">Job</a>
	<a class="dropdown-item" href="{{ route('schedule') }}">Event</a>
	<a class="dropdown-item" href="{{ route('schedule') }}">Estimate</a>	
</div>