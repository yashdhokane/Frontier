@php
    // Set to 'off' to hide the sidebar
    $header = null; // Set to 'off' to hide the header
@endphp

@auth

    @if (auth()->user()->role == 'dispatcher')
    @elseif(auth()->user()->role == 'admin')

    @elseif(auth()->user()->role == 'superadmin')
    @else
    @endif
@endauth


<link rel="stylesheet" href="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" >
<link rel="stylesheet" href="{{ asset('public/admin/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}"/>

<!-- Default Sidebar for other roles -->
@if (request('header') == 'off')
    <!-- Do not display the header -->
@elseif ($header == 'off')
    <!-- Do not display the header -->
@else
    <header class="topbar">
	<style>
/* Show the dropdown on hover */
/* Show the dropdown on hover */
.mega-dropdown:hover .dropdown-menu {
    display: block;
    visibility: visible;
    opacity: 1;
}

/* Align the dropdown menu to the left */
.mega-dropdown .dropdown-menu {
    left: 0 !important;
    right: auto !important;
    transform: none !important;
    min-width: 100%; /* Ensures the menu stretches properly */
}


</style>
        <link rel="stylesheet" href="{{ url('public/admin/dashboard/style.css') }}">

        <nav class="navbar top-navbar navbar-expand-md navbar-dark">

            <div class="navbar-header">
                @include('admin.nav-logo')
            </div>

            <div class="navbar-collapse collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto">
				
					<li class="toplinks headnav_logo_li"><a href="{{ route('home') }}"><img src="https://dispatchannel.com/portal/public/admin/assets/images/dispatchannel-logo.png" alt="Dispatchannel Logo" title="Dispatchannel Logo" class="dark-logo"></a></li>
					
                    @if ($prefix != 'inbox')
                        <li class="nav-item d-none d-md-block" style="display: none !important;">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i data-feather="menu" class="feather-sm"></i></a>
                        </li>
                    @endif
					
                    <li @if (request()->routeIs('schedule')) class="toplinks selected" @else class="toplinks" @endif
                        class="toplinks"><a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i> Schedule</a></li>
					
					<li class="toplinks"><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Customers</a></li>
					
					<li class="toplinks"><a href="{{ route('index.routing.new') }}"><i class="fas fas fa-cogs"></i> Routing</a></li>
					
					<li class="toplinks"><a href="{{ route('parameters') }}"><i class="fas fa-cog"></i> Parameters</a></li>
					
					<!-- mega menu -->
                    <li class="nav-item dropdown mega-dropdown">
                        @include('admin.nav-mega-menu')
                    </li>
                    <!-- End mega menu -->
                      
                    <!-- SEARCH -->
                    <li class="nav-item search-box">
                        @include('admin.nav-search')
                    </li>
                    <!-- END SEARCH -->

                </ul>

                <!-- Right side toggle and nav items -->
                <ul class="navbar-nav">
                    @php
                        $currentFormattedDate = \Carbon\Carbon::now($timezoneName)->format('D d, M\' y');
                        $currentFormattedDateTime = \Carbon\Carbon::now($timezoneName)->format('h:i:s A T');
                    @endphp

					{{--
                     <li class="nav-item dropdown align-self-center px-2" style="display:none!important;">
                        <div class="nav-clock"><span>{{ $currentFormattedDate }}</span><br />
                            <span id="liveTime"></span>
                        </div>
                    </li>
					--}}
 
                    <li class="nav-item header_li">
                       @include('admin.nav-openJobs')
                    </li>
 
                    <li class="nav-item header_li">
                       @include('admin.nav-stickynotes')
                    </li>

					{{--
                    <!-- CUSTOMIZER -->
                    <li class="nav-item custitem2" title="Customizer" style="width: 30px;">
					 @include('admin.costomizer') 
                    </li>
                    <!-- END CUSTOMIZER --> 
					--}}
					
					<!-- NOTIFICATION -->
                    <li class="nav-item dropdown" title="Notification">
                        @include('admin.nav-notification')
                    </li>
                    <!-- END NOTIFICATION -->

                    <!-- MESSAGES -->
                    <li class="nav-item dropdown" title="Messages">
                        @include('admin.nav-messages')
                    </li>
                    <!-- END MESSAGES -->


                    <!-- USER PROFILE AND SEARCH -->
                    <li class="nav-item dropdown">
                        @include('admin.nav-user-profile')
                    </li>
                    <!-- END USER PROFILE AND SEARCH -->

                 </ul>

            </div>

        </nav>


        

    </header>

    <script>
        const storeColorNoteUrl = "{{ route('store.colorNote') }}";
        const updateColorNoteUrl = "{{ route('update.colorNote') }}";
        const storeEditNoteUrl = "{{ route('note.get') }}";
        const deleteNoteUrl = "{{ route('note.delete') }}";
        const csrfToken = "{{ csrf_token() }}";

        function updateTime() {
            const timezoneName = '{{ $timezoneName }}'; // Dynamic timezone from backend
            const options = {
                timeZone: timezoneName,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                timeZoneName: 'short'
            };

            const formatter = new Intl.DateTimeFormat('en-US', options);
            const now = new Date();
            const formattedTime = formatter.format(now);

            document.getElementById('liveTime').innerText = formattedTime;
        }

        setInterval(updateTime, 1000); // Update every second
        updateTime(); // Initial call

        function updateCheckboxValue1(checkbox) {
            const time_constraint_job_value =  $('#time_constraint_job_value').val();
            const auto_publishing_job_value =  $('#auto_publishing_job_value').val();
            const priority_job_value =  $('#priority_job_value').val();
            const number_of_calls =  $('#number_of_calls').val();
            const auto_rerouting =  $('#auto_rerouting').val();
          
            let selectedJobIds = [];

            if (time_constraint_job_value === 'on' || auto_publishing_job_value === 'on'|| priority_job_value === 'on' || auto_rerouting === 'on') {
                $("input[name='jobIds[]']:checked").each(function () {
                    selectedJobIds.push($(this).val());
                });
                var time_constraints = time_constraint_job_value;
                 
                 var auto_publishing_value = auto_publishing_job_value;
        

                $.ajax({
                    url: '{{ route('index.routing.Routesettingstore') }}',  
                    type: 'POST',
                    data: {
                        jobIds: selectedJobIds,
                        time_constraints: time_constraints,
                        auto_publishing: auto_publishing_value,
                        priority_job_value: priority_job_value,
                        auto_rerouting: auto_rerouting,
                        number_of_calls: number_of_calls,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#frontier_loader').show();
                            $('#time_constraint_job').bootstrapSwitch('state', false);
                            $('#autoRouteTimesticky').bootstrapSwitch('state', false);
                            $('#auto_publishingMain').bootstrapSwitch('state', false);
                            $('#priority_routing').bootstrapSwitch('state', false);
                            $('#auto_rerouting_switch').bootstrapSwitch('state', false);
                            $('#jobIds').prop('checked', false);
                            $('#time_constraint_job_value').val('no');
                            
                            // Clear existing tbody
                            $('#sticky_job_list tbody').empty();

                            $('#sticky_job_list tbody').append(response.html);

                            setTimeout(function() {
                                $('#frontier_loader').hide();
                            }, 1000);
                        }
                    },
                });
            }
        }
          
        function updateConstraintValue(checkbox) {
            const isChecked = $(checkbox).is(':checked');
            const hiddenField = $('#time_constraint_job_value');

            if (hiddenField.length) {
                hiddenField.val(isChecked ? 'on' : 'off');
            }
        }

        function updatePublishingValue(checkbox) {
            const isChecked = $(checkbox).is(':checked');
            const hiddenField = $('#auto_publishing_job_value');
            const autoPublishingTime1 = $('#autoPublishingTime1');

            if (hiddenField.length) {
                hiddenField.val(isChecked ? 'on' : 'off');
            }
             if (autoPublishingTime1.length) {
                autoPublishingTime1.css('display', isChecked ? 'block' : 'none').css('important', 'true');
            }
        }

        function updatePriorityValue(checkbox) {
            const isChecked = $(checkbox).is(':checked');
            const hiddenField = $('#priority_job_value');

            if (hiddenField.length) {
                hiddenField.val(isChecked ? 'on' : 'off');
            }
        }

        function updateReroutingValue(checkbox) {
            const isChecked = $(checkbox).is(':checked');
            const hiddenField = $('#auto_rerouting');
            const rerouteTimeField = $('#autoReRouteTime1');

            if (hiddenField.length) {
                hiddenField.val(isChecked ? 'on' : 'off');
            }

            if (rerouteTimeField.length) {
                rerouteTimeField.css('display', isChecked ? 'block' : 'none').css('important', 'true');
            }
        }



        function toggleAllCheckboxes(allCheckbox) {
            // Check or uncheck all jobIds based on allCheckbox state
            let jobIds = document.querySelectorAll('.jobIds');
            jobIds.forEach(function(jobId) {
                jobId.checked = allCheckbox.checked;
            });
        }

        function checkAllSelected() {
            // Check if all jobIds are selected
            let allCheckbox = document.getElementById('allcheckbox');
            let jobIds = document.querySelectorAll('.jobIds');
            let allChecked = document.querySelectorAll('.jobIds:checked').length === jobIds.length;
            allCheckbox.checked = allChecked;
        }


      
    
    </script>
     
  
@endif
