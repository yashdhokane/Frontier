@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.togglebutton', function() {
                var $jobDetailsDiv = $('.openJobTechDetails');
                var $mapRouteDiv = $('.mapbestroute');
                var $button = $(this);

                // Toggle visibility of the divs
                $jobDetailsDiv.toggle();
                $mapRouteDiv.toggle();

                // Change button text based on the visibility of the map route div
                if ($mapRouteDiv.is(':visible')) {
                    $button.text('Show All Job Details');
                } else {
                    $button.text('Show Best Route');
                }
            });


            $(document).on('click', '.JobOpenModalButton', function(event) {
                event.preventDefault(); // Prevent the default anchor click behavior
                var tech_id = $(this).data('tech-id');
                var $jobDetailsDiv = $('.openJobTechDetails');
                var $mapRouteDiv = $('.mapbestroute');
                var $button = $('.togglebutton');

                $button.text('Show Best Route');

                $jobDetailsDiv.show();
                $mapRouteDiv.hide();
                $('#allJobsTechnician .popup-option123').attr('data-id', tech_id);
                var tech_name = $(this).data('tech-name');
                var date = $(this).data('date');
                $.ajax({
                    url: '{{ route('schedule.getALlJobDetails') }}',
                    method: 'GET',
                    data: {
                        tech_id: tech_id,
                        date: date
                    },
                    success: function(response) {
                        var jobs = response;
                        // console.log(jobs);
                        var ticketShowRoute = "{{ route('tickets.show', ':id') }}";
                        $('#allJobsTechnicianLabel46').empty();
                        $('#allJobsTechnicianLabel46').append(tech_name +
                            ' - Dispatch Schedule');

                        $('.openJobTechDetails').empty();




                        // Check if there are jobs in the response
                        if (jobs.length === 0) {
                            // If no jobs are available, show a message
                            $('.openJobTechDetails').append(
                                '<div class="col-12"><p>There is no job available.</p></div>'
                            );
                        } else {
                            // If jobs are available, iterate over each job and append its details
                            jobs.forEach(function(job) {
                                var fieldNames = '';

                                // Check if job_model exists and if fieldids is a valid array
                                if (job.job_model && Array.isArray(job.job_model
                                        .fieldids) && job.job_model.fieldids.length >
                                    0) {
                                    // Join the field names into a single string
                                    fieldNames = job.job_model.fieldids.map(function(
                                    f) {
                                        return f.field_name;
                                    }).join(', ');
                                }

                                // Conditionally add the badge if fieldNames is not empty
                                var fieldNamesBadge = fieldNames ?
                                    `<span class="badge bg-primary">${fieldNames}</span>` :
                                    '';
                                // Create the HTML structure for each job
                                var jobHtml = `
                               <div class="col-md-4 mb-3">
 	 
                                   <div class="card shadow-sm h-100 pp_job_info_full">
                                       <div class="card-body card-border card-shadow">
                                           <!-- Job ID and Badge -->
                                           <h5 class="card-title py-1">
                                               <strong class="text-uppercase">
                                                   #${job.job_model ? job.job_model.id : ''}  ${fieldNamesBadge}
                                                    <!-- <span class="badge bg-primary">${job.job_model ? job.job_model.status : ''}</span>  -->
                                                   ${job.job_model && job.job_model.warranty_type === 'in_warranty' ? `<span class="badge bg-warning">In Warranty</span>` : ''}
                                                   ${job.job_model && job.job_model.warranty_type === 'out_warranty' ? `<span class="badge bg-danger">Out of Warranty</span>` : ''}
                                               </strong>
                                           </h5>
                           
                                           <!-- Job Title and Description -->
                                           <div class="pp_job_info pp_job_info_box">
	                           				<h6 class="text-uppercase"> ${job.job_model ? (job.job_model.job_title.length > 20 ? job.job_model.job_title.substring(0, 20) + '...' : job.job_model.job_title) : ''} </h6>
	                           				<div class="description_info">${job.job_model ? job.job_model.description : ''}</div>
 	                           				<div class="pp_job_date text-primary">
	                           					${job.start_date_time && job.end_date_time ? formatDateRange(job.start_date_time, job.end_date_time, job.interval) : ''}
	                           				</div>
                                           </div>
                            
                                           <!-- User Info -->
                                          <div class="pp_user_info pp_job_info_box">
                                               <h6 class="text-uppercase"><i class="fas fa-user pe-2 fs-2"></i> ${job.job_model && job.job_model.user ? job.job_model.user.name : ''}</h6>
	                           				<div>
	                           					${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.address_line1 : ''},
	                           					${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.zipcode : ''}
	                           				</div>
	                           				<div>
	                           					${job.job_model && job.job_model.user ? job.job_model.user.mobile : ''}
	                           				</div>
	                           			</div>
                                           
                                           <!-- Equipment Info -->
                                           <div class="pp_job_info_box">
                                               <h6 class="text-uppercase">Equipment</h6>
	                           				<div> 
	                           					${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
	                           						? job.job_model.job_appliances.appliances.appliance.appliance_name 
							: ''} /  
						${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances && job.job_model.job_appliances.appliances.manufacturer 
							? job.job_model.job_appliances.appliances.manufacturer.manufacturer_name 
							: ''} /  
						${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
							? job.job_model.job_appliances.appliances.model_number 
							: ''} / 
						${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
							? job.job_model.job_appliances.appliances.serial_number 
							: ''}
					</div>
				</div>
				
				<div class="pp_job_info_box">
					<h6 class="text-uppercase">Parts & Services</h6>
 					<div> 
						<!-- Check and display parts -->
						${job.job_model && job.job_model.jobproductinfohasmany && job.job_model.jobproductinfohasmany.length > 0 
							? job.job_model.jobproductinfohasmany.map(product => `
    								${product.product && product.product.product_name ? `${product.product.product_name}, ` : ''}
    							`).join('') 
							: ''
						}

						<!-- Check and display services -->
						${job.job_model && job.job_model.jobserviceinfohasmany && job.job_model.jobserviceinfohasmany.length > 0 
							? job.job_model.jobserviceinfohasmany
								.map(service => service.service && service.service.service_name ? service.service.service_name : '')
								.filter(serviceName => serviceName !== '') // Filter out any empty service names
								.join(', ')  // Join with comma
								.replace(/,\s*$/, '')  // Remove the last comma if present
							: ''
						}
					</div>
                </div>
 
                <!-- Edit and View Buttons -->
                <div class="d-flex justify-content-between pt-2">
                    <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}?mode=edit#editdetails" target="_blank">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </a>
                    <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}" target="_blank">
                        <button class="btn btn-outline-primary btn-sm">
                            View
                        </button>
                    </a>
                </div>
				
            </div>
        </div>
    </div>`;



                                // Append the jobHtml into the container
                                $('.openJobTechDetails').append(jobHtml);

                            });



                        }
                        $('#allJobsTechnician').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:',
                            error);
                    }
                });

                $.ajax({
                    url: "{{ route('getLocation.bestroot') }}", // Replace with your Laravel route
                    type: 'GET',
                    data: {
                        id: tech_id,
                        date: date,
                    },
                    success: function(response) {
                        if (response.technician_location && response.sorted_customers) {
                            console.log('Technician Location:', response.technician_location);
                            console.log('Customer Locations:', response.sorted_customers);

                            // Clear previous map or error message
                            $('#map').empty();
                           
                            $('.mapbestroute').empty();


                             $('#map4').show();
    $('#sortableCustomerList3').empty();
    $('#map').empty();
    $('#map2').empty();
    $('#map3').empty();
    
    // Show and hide sortable lists as needed
    $('#sortableCustomerList').empty();
    $('#sortableCustomerList1').empty();
    $('#sortableCustomerList2').empty();
    
    // Toggle active class for buttons
    $('#defaultRoute').addClass('btn-active');
    $('#saveRoute').removeClass('btn-active');
    $('#centerSave').hide();

                            // Inject buttons, customer info, and map sections
                            $('.mapbestroute').append(`
                <form id="customerRouteForm" class="customerRouteFormed" >
                    <div style="display: flex; margin-bottom: 10px;">
                        <button id="defaultRoute" type="button" class="btn btn-outline-primary btn-sm btn-active" style="float: left;">Show Route</button>
                        <button id="saveRoute" type="button" class="btn btn-outline-primary btn-sm" style="float: left; margin-left: 5px;">Customized Route</button>
       <select id="priorityDropdown" name="priority" class="form-select form-control-sm" style="width: 120px; margin-left:5px;">
    <option value="" >Priority</option>
    <option value="low">Low</option>
    <option value="medium">Medium</option>
    <option value="high">High</option>
    <option value="urgent">Urgent</option>
</select>


                   <label id="largeCheckboxjoblabel" style="float: left; margin-left: 10px; display:none;">
                       <input type="checkbox" id="largeCheckboxjob" style="transform: scale(1.7); margin-right: 5px; margin-top: 10px; display:none;"> Confirm Jobs
                      </label>
                    </div>

                    <div style="display: flex; width: 100%; justify-content: space-between;">
                        <!-- Customer Information Section -->
                        <div id="customerInfo" style="width: 25%; border: 1px solid #ccc; padding: 10px; overflow-y: auto; height: 500px;">
                            <h5 class="job-title-h5">Jobs</h5>
                            <ul id="sortableCustomerList" style="list-style-type: none; padding: 0;"></ul>
                            <ul id="sortableCustomerList1" style="list-style-type1: none; padding: 0; display:none;"></ul>
                           <ul id="sortableCustomerList2" style="list-style-type1: none; padding: 0; display:none;"></ul>
                           <ul id="sortableCustomerList3" style="list-style-type1: none; padding: 0; display:none;"></ul>

  <div style="text-align: left; ">
  
                   <button id="centerSave" type="button" class="btn btn-outline-primary btn-sm" >Save</button>
                   </div>
                        </div>

                        <!-- Map Section -->
                        <div id="map" style="width: 75%; height: 500px; border: 1px solid #ccc;"></div>
                        <div id="map2" style="width: 75%; height: 500px; border: 1px solid #ccc; display: none;"></div>
                        <div id="map3" style="width: 75%; height: 500px; border: 1px solid #ccc; display: none;"></div>
                        <div id="map4" style="width: 75%; height: 500px; border: 1px solid #ccc; display: none;"></div>

                    </div>

                    <!-- Centered Save Button -->
                 

                </form>
            `);

                            // Append customer data into the sortable list
                            // Initialize arrays for customers based on their positions
                            const customersWithZeroPosition = [];
                            const customersWithNonZeroPosition = [];

                            // Iterate through the sorted customers
                            response.sorted_customers.forEach(function(customer, index) {
                                if (customer.position === 0) {
                                    // Push customers with position 0 to the zero position array
                                    customersWithZeroPosition.push(customer);
                                } else {
                                    // Push customers with non-zero positions to the non-zero position array
                                    customersWithNonZeroPosition.push(customer);
                                }
                            });

                            // Sort customers with non-zero positions based on their 'position' property
                            customersWithNonZeroPosition.sort((a, b) => a.position - b
                            .position);

                            // Append customers with non-zero positions first, then those with position zero
                            // Append customers with non-zero position first
                            customersWithNonZeroPosition.forEach(function(customer, index) {
                                let isRoute = customer.is_routes_map == 1 ? 'checked' :
                                    ''; // Set checkbox checked if is_routes_map is 1
                            $('#sortableCustomerList1').append(`
 <li data-index="${index}" data-jobid="${customer.job_id}" data-id="${customer.user_id}" data-technicianId="${response.technician_location.user_id}" data-lat="${customer.latitude}" data-lng="${customer.longitude}" data-isroute="${customer.is_routes_map}" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s;">
    
    <div style="display: flex; flex-direction: column;" class="route_cst2">
        <!-- Display the job_onmap_reaching_timing -->
        <small class="job-title-h6" style="">Driving Timing: ${customer.job_onmap_reaching_timing || '0'}.min</small>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- Left Side (Checkbox and Name) -->
            <div style="flex-grow: 1; display: flex; align-items: center;">
                <input type="checkbox" class="route-checkbox" ${isRoute} data-index="${index}" style="margin-right: 8px; transform: scale(1.2);"> 
                <span class="customer-position" style="font-weight: bold; color: #007BFF; margin-right: 5px;">${customer.position}.</span> 
                <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer.name}</b>
            </div>

            <!-- Right Side (Duration) -->
            <div style="flex-shrink: 0;">
                <small style="font-weight: bold; color: #333;">${customer.duration}</small>
            </div>
        </div>

        <!-- Full Address -->
        <small style="margin-top: 5px; color: #555;">${customer.full_address}</small>
    </div>
    <div  class="suggested-window">
  WINDOW SUGGESTED 1 HRS </div>
</li>

 
`);

                            });
                            $('#centerSave').hide();


                            // Append customers with position zero after
                            customersWithZeroPosition.forEach(function(customer) {
                                let isRoute = customer.is_routes_map == 1 ? 'checked' :
                                    ''; // Set checkbox checked if is_routes_map is 1
                               $('#sortableCustomerList1').append(`
    <li data-id="${customer.user_id}" data-jobid="${customer.job_id}" data-technicianId="${response.technician_location.user_id}" data-lat="${customer.latitude}" data-lng="${customer.longitude}" data-isroute="${customer.is_routes_map}" style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">

        
        <div style="display: flex; flex-direction: column;" class="route_cst2">
                            <small class="job-title-h6" style="">Driving Timing: ${customer.job_onmap_reaching_timing || '0'}.min</small>

            <!-- Left Side (Position and Name) -->
            <div style="display: flex; align-items: center;">
                <span class="customer-position" style="font-weight: bold; font-size: 1.1em; color: #007BFF; margin-right: 5px;">${customer.position}.</span> 
                <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer.name}</b>
            </div>

            <!-- Right Side (Duration) -->
            <div>
                <small style="font-weight: bold; color: #555;">${customer.duration}</small>
            </div>
        </div>

        <!-- Full Address -->
        <small style="margin-top: 5px; color: #777;">${customer.full_address}</small>
    </li>
`);

                            });



                          const customersWithZeroPosition1 = [];
const customersWithNonZeroPosition1 = [];

// Iterate through the sorted customers
response.sorted_customers2.forEach(function(customer1) {
    if (customer1.position === 0) {
        // Push customers with position 0 to the zero position array
        customersWithZeroPosition1.push(customer1);
    } else {
        // Push customers with non-zero positions to the non-zero position array
        customersWithNonZeroPosition1.push(customer1);
    }
});

// Sort customers with non-zero positions based on their 'position' property
customersWithNonZeroPosition1.sort((a, b) => a.position - b.position);

// Append customers with non-zero positions first
customersWithNonZeroPosition1.forEach(function(customer1) {
    // Parse the 'number' property as an integer
    const positionDisplay = parseInt(customer1.number, 10);

    // Only append customers with a valid number
    if (!isNaN(positionDisplay)) { // Check if it's a valid number
    $('#sortableCustomerList2').append(`
    <li data-id="${customer1.user_id}" 
    data-jobid="${customer1.job_id}" 
    data-technicianId="${response.technician_location.user_id}" 
    data-lat="${customer1.latitude}" 
    data-lng="${customer1.longitude}" 
    data-isroute="${customer1.is_routes_map}" 
    style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s, box-shadow 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
    

    <div style="display: flex; flex-direction: column;" class="route_cst2">
        <small class="job-title-h6" style="">Driving Timing: ${customer1.job_onmap_reaching_timing || '0'}.min</small>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- Left Side (Checkbox and Name) -->
            <div style="flex-grow: 1; display: flex; align-items: center;">
            <span class="customer-position" style="font-weight: bold; font-size: 1.1em; color: #4CAF50; margin-right: 5px;">${positionDisplay}.</span> 
            <span class="job-title-h5" style="font-weight: bold; font-size: 1.1em; color: #333;">${customer1.name}</span>
        </div>

        <!-- Right Side (Duration) -->
        <div style="text-align: right;">
            <small style="font-size: 0.9em; color: #555;">${customer1.duration}</small>
        </div>
   

    <!-- Full Address -->
    </div>

     <small style="display: block; margin-top: 4px; color: #777;">${customer1.full_address}</small>

    </div>
    <!-- Suggested Window -->
    <div class="suggested-window">WINDOW SUGGESTED 1 HRS</div>
</li>

`);


    }
});

// Optionally, handle customers with zero positions if needed
customersWithZeroPosition1.forEach(function(customer1) {
        const positionDisplay = parseInt(customer1.number, 10);

  $('#sortableCustomerList2').append(`
   <li style="padding: 12px; border: 1px solid #ddd; margin-bottom: 8px; border-radius: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s, box-shadow 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">

    <div style="display: flex; flex-direction: column;" class="route_cst2">
        <small class="job-title-h6" style="">Driving Timing: ${customer1.job_onmap_reaching_timing || '0'}.min</small>

        <!-- Left Side (Position and Name) -->
        <div style="display: flex; align-items: center;">
                    <div style="flex-grow: 1; display: flex; align-items: center;">

            <span class="customer-position" style="font-weight: bold; font-size: 1.1em; color: #007BFF; margin-right: 5px;">${positionDisplay}.</span> 
            <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer1.name}</b>
        </div>

        <!-- Right Side (Duration) -->
          <div >
            <small style="font-weight: bold; color: #555;">${customer1.duration}</small>
        </div>

    </div>
    <small style="display: block; margin-top: 4px; color: #777;">${customer1.full_address}</small>

    <!-- Full Address -->

    <!-- Suggested Window -->
 
    <div class="suggested-window">WINDOW SUGGESTED 1 HRS</div>
</li>

`);

});







 


                            // Checkbox event listener to update data-isroute and check/uncheck checkbox
                            $(document).on('change', '.route-checkbox', function() {
                                var $li = $(this).closest(
                                'li'); // Get the parent list item
                                if ($(this).is(':checked')) {
                                    $li.attr('data-isroute',
                                    '1'); // Set data-isroute to 1 when selected
                                } else {
                                    $li.attr('data-isroute',
                                    '0'); // Set data-isroute to 0 when unselected
                                }
                            });

                            // Initialize checkbox based on data-isroute attribute
                            $('.route-checkbox').each(function() {
                                var $li = $(this).closest(
                                'li'); // Get the parent list item
                                var isRoute = $li.attr(
                                'data-isroute'); // Get the current value of data-isroute
                                $(this).prop('checked', isRoute ==
                                '1'); // Set checkbox checked if data-isroute is 1, unchecked if 0
                            });


                          customersWithNonZeroPosition.forEach(function(customer, index) {
  $('#sortableCustomerList').append(`
    <li data-id="${customer.user_id}" data-jobid="${customer.job_id}" data-technicianId="${response.technician_location.user_id}" data-lat="${customer.latitude}" data-lng="${customer.longitude}" data-isroute="${customer.is_routes_map}" 
        style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s, box-shadow 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- Left Side (Position and Name) -->
            <div style="display: flex; align-items: center;">
                <span class="customer-position" style="font-weight: bold; font-size: 1.1em; color: #4CAF50; margin-right: 8px;">${customer.position}.</span> 
                <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer.name}</b>
            </div>

            <!-- Right Side (Duration) -->
            <div style="text-align: right;">
                <small style="font-weight: bold; color: #555;">${customer.duration}</small>
            </div>
        </div>

        <!-- Full Address -->
        <small style="display: block; margin-top: 4px; color: #777;">${customer.full_address}</small>
    </li>
`);

});

// Append customers with position zero after
customersWithZeroPosition.forEach(function(customer) {
$('#sortableCustomerList').append(`
    <li data-id="${customer.user_id}" data-jobid="${customer.job_id}" data-technicianId="${response.technician_location.user_id}" data-lat="${customer.latitude}" data-lng="${customer.longitude}" data-isroute="${customer.is_routes_map}" 
        style="padding: 12px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s, box-shadow 0.3s; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <!-- Left Side (Position and Name) -->
            <div style="display: flex; align-items: center;">
                <span class="customer-position" style="font-weight: bold; font-size: 1.1em; color: #4CAF50; margin-right: 8px;">${customer.position}.</span> 
                <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer.name}</b>
            </div>

            <!-- Right Side (Duration) -->
            <div style="text-align: right;">
                <small style="font-weight: bold; color: #555;">${customer.duration}</small>
            </div>
        </div>

        <!-- Full Address -->
        <small style="display: block; margin-top: 4px; color: #777;">${customer.full_address}</small>
    </li>
`);

});




                            // Make the customer list sortable
                            $("#sortableCustomerList1").sortable({
                                start: function(event, ui) {
                                    // Disable draggable during sorting to avoid conflicts
                                    $(".day").droppable("disable");
                                },
                                stop: function(event, ui) {
                                    // Re-enable draggable after sorting is complete
                                    $(".day").droppable("enable");

                                    // Enable the save button after sorting
                                    $('#centerSave').prop('disabled', false);
                                    $('#saveRoute').prop('disabled', false);

                                    // Update position numbers dynamically after sorting
                                    $('#sortableCustomerList1 li').each(function(
                                        index) {
                                        $(this).find('.customer-position')
                                            .text(index + 1);
                                    });
                                }
                            });


                            // Initialize the map with technician and customer locations
                            initMap(response.technician_location, response.sorted_customers);

                            initMap1(response.technician_location, response.sorted_customers2);

                            const map2 = new google.maps.Map(document.getElementById('map2'), {
                                zoom: 12,
                                center: {
                                    lat: parseFloat(response.technician_location
                                        .latitude),
                                    lng: parseFloat(response.technician_location
                                        .longitude)
                                }
                            });

                            // Array to store waypoints for the route
                            let waypoints = [];

                            // Add technician's marker
                            const technicianMarker = new google.maps.Marker({
                                position: {
                                    lat: parseFloat(response.technician_location
                                        .latitude),
                                    lng: parseFloat(response.technician_location
                                        .longitude)
                                },
                                map: map2,
                                label: 'T', // Label as 'T' for technician
                                title: response.technician_location.name
                            });

                            const technicianInfoWindow = new google.maps.InfoWindow({
                                content: `<strong>${response.technician_location.name}</strong><br>${response.technician_location.full_address}`
                            });

                            // Open info window on technician marker click
                            technicianMarker.addListener('click', function() {
                                technicianInfoWindow.open(map2, technicianMarker);
                            });

                            // Add customer markers and store their positions based on the defined order
                            response.sorted_customers1.sort((a, b) => a.position - b.position)
                                .forEach((customer, index) => {
                                    const customerMarker = new google.maps.Marker({
                                        position: {
                                            lat: parseFloat(customer.latitude),
                                            lng: parseFloat(customer.longitude)
                                        },
                                        map: map2,
                                        label: `${index + 1}`, // Label as customer position
                                        title: customer.name
                                    });

                                    const customerInfoWindow = new google.maps.InfoWindow({
                                        content: `<strong>${customer.name}</strong><br>${customer.full_address}`
                                    });

                                    // Open info window on customer marker click
                                    customerMarker.addListener('click', function() {
                                        customerInfoWindow.open(map2,
                                            customerMarker);
                                    });

                                    // Add customer position to waypoints
                                    waypoints.push({
                                        location: new google.maps.LatLng(customer
                                            .latitude, customer.longitude),
                                        stopover: true
                                    });
                                });

                            // Directions Service to create the route
                            const directionsService = new google.maps.DirectionsService();
                            const directionsRenderer = new google.maps.DirectionsRenderer({
                                polylineOptions: {
                                    strokeColor: 'blue', // Set the stroke color to blue
                                    strokeWeight: 5,
                                },
                                suppressMarkers: true // Suppress default markers
                            });

                            directionsRenderer.setMap(map2);

                            // Create the route
                            const origin = new google.maps.LatLng(response.technician_location
                                .latitude, response.technician_location.longitude);
                            const destination = new google.maps.LatLng(response
                                .sorted_customers1[response.sorted_customers1.length - 1]
                                .latitude, response.sorted_customers1[response
                                    .sorted_customers1.length - 1].longitude);

                            // Make sure to set the waypoints based on customer position order
                            directionsService.route({
                                origin: origin,
                                destination: destination, // Set the last customer as destination
                                waypoints: waypoints, // Include all waypoints
                                optimizeWaypoints: false, // Ensure the order remains as specified
                                travelMode: google.maps.TravelMode
                                    .DRIVING // Set travel mode to driving
                            }, (response, status) => {
                                if (status === 'OK') {
                                    directionsRenderer.setDirections(response);
                                } else {
                                    console.error('Directions request failed due to ' +
                                        status);
                                }
                            });


                            // Button Click Events
                           $('#defaultRoute').addClass('btn-active');

                                            // Button Click Events
                                            $('#defaultRoute').on('click', function() {
                                                // Show map1 and hide other elements
                                                $('#map').show(); // Show map
                                                $('#map2').hide(); // Hide map2
                                                $('#map3').hide();
                                                $('#map4').hide();
                                                $('#largeCheckboxjob').hide(); // Hide largeCheckboxjob
                                                $('#largeCheckboxjoblabel')
                                                    .hide(); // Hide largeCheckboxjoblabel

                                                // Show and hide sortable lists as needed
                                                $('#sortableCustomerList')
                                                    .show(); // Show sortableCustomerList
                                                $('#sortableCustomerList1')
                                                    .hide(); // Hide sortableCustomerList1
                                                $('#sortableCustomerList2')
                                                    .hide(); // Ensure sortableCustomerList2 is hidden
                                                $('#sortableCustomerList3')
                                                    .hide();
                                                // Toggle active class for buttons
                                                $('#defaultRoute').addClass('btn-active');
                                                $('#saveRoute').removeClass('btn-active');
                                                $('#centerSave').hide();

                                            });

                                            // Save Route Button Click Event
                                            $('#saveRoute').on('click', function() {
                                                // Hide map1 and show map2
                                                $('#map').hide(); // Hide map
                                                $('#map2').show(); // Show map2
                                                 $('#map3').hide();
                                                 $('#map4').hide();
                                                $('#largeCheckboxjob').show(); // Show largeCheckboxjob
                                                $('#largeCheckboxjoblabel')
                                                    .show(); // Show largeCheckboxjoblabel

                                                // Show and hide sortable lists as needed
                                                $('#sortableCustomerList')
                                                    .hide(); // Hide sortableCustomerList
                                                    $('#sortableCustomerList3')
                                                    .hide();
                                                $('#sortableCustomerList1')
                                                    .show(); // Show sortableCustomerList1
                                                $('#sortableCustomerList2')
                                                    .hide(); // Ensure sortableCustomerList2 is hidden
                                                $('#centerSave').show();


                                                // Toggle active class for buttons
                                                $('#saveRoute').addClass('btn-active');
                                                $('#defaultRoute').removeClass('btn-active');
                                            });

                                            // Center Save Button Click Event
                                            $('#centerSave').on('click', function() {
                                                if (!$(this).is(':disabled')) {
                                                    // Submit the form if button is not disabled
                                                    $('#customerRouteForm').submit();
                                                }
                                            });

                                            // Large Checkbox Change Event
                                            $('#largeCheckboxjob').on('change', function() {
                                                if ($(this).is(':checked')) {
                                                    // Checkbox is checked - Hide maps and show sortableCustomerList2
                                                    $('#map').hide(); // Hide map
                                                    $('#map2').hide(); // Hide map2
                                                    $('#map3').show(); // Hide map3
                                                     $('#map4').hide();
                                                     $('#sortableCustomerList3')
                                                    .hide();

                                                    $('#largeCheckboxjob').show();
                                                    $('#largeCheckboxjoblabel').show();

                                                    // Show sortableCustomerList2 and hide others
                                                    $('#sortableCustomerList2').show();
                                                    $('#sortableCustomerList1').hide();
                                                    $('#sortableCustomerList').hide();
                                                    $('#centerSave').hide();


                                                    // Toggle active class for buttons
                                                    $('#defaultRoute').removeClass('btn-active');
                                                    $('#saveRoute').removeClass('btn-active');
                                                } else {
                                                    // Checkbox is unchecked - Trigger saveRoute click event
                                                    $('#saveRoute').trigger('click');
                                                }
                                            });

                                    
                        } else {
                            alert('Location data not found.');
                        }
                    },
                    error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if (response && response.error ===
                            "No jobs found for this technician on the selected date.") {
                            $('#map').empty();
                            $('.mapbestroute').empty().append(
                                '<div class="no-jobs-message">No best route found.</div>');
                        } else {
                            console.error('Error fetching location:', error);
                        }
                    }
                });
                      $(document).on('change', '#priorityDropdown', function() {
    var selectedPriority = $(this).val();
    $('#map4').empty();
    $('#sortableCustomerList3').empty();
    
    $.ajax({
        url: "{{ route('getLocation.bestroot') }}", 
        type: 'GET',
        data: {
            id: tech_id,       
            date: date,       
            priority: selectedPriority 
        },
     success: function(response) {
    // Clear previous content in the list before appending new data
    $('#sortableCustomerList3').empty();

    $('#map4').show();
    $('#sortableCustomerList3').show();
    $('#map').hide();
    $('#map2').hide();
    $('#map3').hide();
    
    // Show and hide sortable lists as needed
    $('#sortableCustomerList').hide();
    $('#sortableCustomerList1').hide();
    $('#sortableCustomerList2').hide();
    
    // Toggle active class for buttons
    $('#defaultRoute').addClass('btn-active');
    $('#saveRoute').removeClass('btn-active');
    $('#centerSave').hide();

    const customersWithZeroPosition2 = [];
    const customersWithNonZeroPosition2 = [];

    // Check if the sorted_customers array exists and has elements
    if (response.sorted_customers3 && Array.isArray(response.sorted_customers3)) {
        response.sorted_customers3.forEach(function(customer2) {
            if (customer2.position === 0) {
                customersWithZeroPosition2.push(customer2);
            } else {
                customersWithNonZeroPosition2.push(customer2);
            }
        });

        // Sort customers with non-zero positions based on their 'position' property
        customersWithNonZeroPosition2.sort((a, b) => a.position - b.position);

        // Append customers with non-zero positions first
        customersWithNonZeroPosition2.forEach(function(customer2) {
            const positionDisplay = parseInt(customer2.number, 10);
            if (!isNaN(positionDisplay) && customer2.full_address) { // Ensure valid number and full address
              $('#sortableCustomerList3').append(`
   <li style="padding: 12px; border: 1px solid #ddd; margin-bottom: 8px; border-radius: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.2s;">

    <div style="display: flex; flex-direction: column;" class="route_cst2">
           <small class="job-title-h6">Driving Timing: ${customer2.job_onmap_reaching_timing || '0'}.min</small>

               <div style="display: flex; justify-content: space-between; align-items: center;">

        <!-- Left Side (Position and Name) -->
            <div style="flex-grow: 1; display: flex; align-items: center;">
            <span class="customer-position" style="font-weight: bold; color: #007BFF; margin-right: 5px;">${positionDisplay}.</span>
            <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer2.name}</b>
        </div>

        <!-- Right Side (Duration) -->
            <div style="flex-shrink: 0;">
            <small style="font-weight: bold; color: #333;">${customer2.duration}</small>
        </div>
    </div>
    
    <small style="margin-top: 5px; color: #555;">${customer2.full_address}</small>
  </div>
    <!-- Suggested Window -->
    <div  class="suggested-window">
  WINDOW SUGGESTED 1 HRS </div>
</li>

`);

            }
        });

        // Append customers with zero positions
        customersWithZeroPosition2.forEach(function(customer3) {
          $('#sortableCustomerList3').append(`
  <li style="padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s;">
        <div style="display: flex; flex-direction: column;" class="route_cst2">

    
    <small class="job-title-h6">Driving Timing: ${customer3.job_onmap_reaching_timing || '0'}.min</small>

        <div style="display: flex; align-items: center;">
        <!-- Left Side (Position and Name) -->
        <div style="flex-grow: 1; display: flex; align-items: center;">
            <span style="font-weight: bold; font-size: 1.1em; color: #007BFF; margin-right: 5px;">${customer3.position}.</span> 
            <b class="job-title-h5" style="font-size: 1.1em; color: #333;">${customer3.name}</b>
        </div>
        
        <!-- Right Side (Duration) -->
        <div>
            <small style="font-weight: bold; color: #555;">${customer3.duration}</small>
        </div>
    </div>
    
    <small style="margin-top: 5px; color: #777;">${customer3.full_address}</small>
    
    <!-- Suggested Window -->
    <small class="suggested-window" style="display: block; margin-top: 5px; color:black;">WINDOW SUGGESTED 1 HRS</small>
</li>

`);

        });

        // Check if there are no customers and append "No Job found" only if not already appended
        if (customersWithZeroPosition2.length === 0 && customersWithNonZeroPosition2.length === 0) {
            if ($('#sortableCustomerList3 li:contains("No Job found")').length === 0) {
                $('#sortableCustomerList3').append(`
                    <li style="padding: 8px; border: 1px solid #ddd; margin-bottom: 5px;"> 
                        <b>No Job found</b>
                    </li>
                `);
            }
        }

        // Ensure technician_location exists before passing to initMap2
        if (response.technician_location) {
            initMap2(response.technician_location, response.sorted_customers3);
        } else {
            console.error('Technician location is undefined:', response);
        }

    } else {
        console.error('Sorted customers array is undefined or not an array:', response);
        // If response is not valid, show "No Job found" only if not already appended
        if ($('#sortableCustomerList3 li:contains("No Job found")').length === 0) {
            $('#sortableCustomerList3').append(`
                <li style="padding: 8px; border: 1px solid #ddd; margin-bottom: 5px;"> 
                    <b>No Job found</b>
                </li>
            `);
        }
    }
},
error: function(xhr, status, error) {
    console.error('Error:', error);
    // Optionally show "No Job found" if there's an error, but only append it if not already appended
    if ($('#sortableCustomerList3 li:contains("No Job found")').length === 0) {
        $('#sortableCustomerList3').append(`
            <li style="padding: 8px; border: 1px solid #ddd; margin-bottom: 5px;"> 
                <b>No Job found</b>
            </li>
        `);
    }
}

    });
});

 

               

               



     


               $(document).on('submit', '#customerRouteForm', function(event1) {
               event1.preventDefault(); // Prevent default form submission

    // Gather customer data from the list
    const customerData = [];
    $('#sortableCustomerList1 li').each(function(index) {
        const id = $(this).data('id');
        const technicianId = $(this).data('technicianid');
        const jobid = $(this).data('jobid');
        
        // Fetch isroute directly from the checkbox
        const isroute = $(this).find('.route-checkbox').is(':checked') ? 1 : 0;

        const position = index + 1; // Adjust the position to start from 1

        customerData.push({
            user_id: id, // 'user_id' for backend matching
            position: position,
            technicianId: technicianId,
            jobid: jobid,
            isroute: isroute, // Use dynamically fetched isroute value
        });
    });

                    // Send customer data to the server
                    $.ajax({
                        url: "{{ route('getLocation.bestroot.saveRoute') }}", // Your Laravel save route
                        type: 'POST',
                        data: {
                            date: date,
                            customers: customerData, // Send customer data
                            _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
                        },
                        success: function(response) {
                            // Initialize the map
                            const technician = response.updated_customers[0].technician;
                            const mapOptions = {
                                center: {
                                    lat: parseFloat(technician.latitude),
                                    lng: parseFloat(technician.longitude)
                                },
                                zoom: 12
                            };
                            const map = new google.maps.Map(document.getElementById(
                                'map2'), mapOptions);

                            // 1. Add Technician Marker
                            const technicianMarker = new google.maps.Marker({
                                position: {
                                    lat: parseFloat(technician.latitude),
                                    lng: parseFloat(technician.longitude)
                                },
                                map: map,
                                title: technician.name,
                                label: 'T' // Technician label
                            });

                            // Add info window for technician
                            const technicianInfoWindow = new google.maps.InfoWindow({
                                content: `<h4>${technician.name}</h4><p>${technician.full_address}</p>`
                            });

                            technicianMarker.addListener('click', function() {
                                technicianInfoWindow.open(map,
                                technicianMarker);
                            });

                            // Array to hold customer positions for route
                            const customerPositions = [];
                            let validIndex = 1; // Initialize a valid index for labeling

                            // 2. Add Customer Markers and store positions only if isroute = 1
                            response.updated_customers.forEach((customerData) => {
                                const customer = customerData.customer;
                                //li update timming calculation between two cutomer
        // Assuming you have the customer object from your response
const $customerLi = $('#sortableCustomerList1 li[data-id="' + customer.user_id + '"]');

if ($customerLi.length) {
    // Update the checkbox state
    const isChecked = customer.isroute == "1" ? 'checked' : '';
    $customerLi.find('.route-checkbox').prop('checked', isChecked);
    
    // Update the job_onmap_reaching_timing display
    $customerLi.find('.job-title-h5').first().text(`Driving Timing: ${customer.job_onmap_reaching_timing || '0'}.M`); // Update the first job-title-h5

    // Update the name if needed (Optional)
    $customerLi.find('.job-title-h5').last().text(customer.name); // Update the last job-title-h5 if needed

    // Update the duration if needed (Optional)
    $customerLi.find('small').last().text(customer.duration); // Update the last small element for duration
}


                                // Check if isroute is 1
                                if (customer.isroute === "1") {
                                    // Create marker for each customer
                                    const customerMarker = new google.maps
                                        .Marker({
                                            position: {
                                                lat: parseFloat(customer
                                                    .latitude),
                                                lng: parseFloat(customer
                                                    .longitude)
                                            },
                                            map: map,
                                            title: customer.name,
                                            label: validIndex
                                            .toString() // Label based on the valid index
                                        });

                                    // Store customer position for the route
                                    customerPositions.push({
                                        lat: parseFloat(customer
                                            .latitude),
                                        lng: parseFloat(customer
                                            .longitude)
                                    });

                                    // Add info window for customer
                                    const customerInfoWindow = new google.maps
                                        .InfoWindow({
                                            content: `<h4>${customer.name}</h4><p>${customer.full_address}</p>`
                                        });

                                    customerMarker.addListener('click',
                                        function() {
                                            customerInfoWindow.open(map,
                                                customerMarker);
                                        });

                                    // Increment valid index for the next valid customer
                                    validIndex++;
                                }
                            });

                            // 3. Create a DirectionsService and DirectionsRenderer if there are valid customer positions
                            if (customerPositions.length > 0) {
                                const directionsService = new google.maps
                                    .DirectionsService();
                                const directionsRenderer = new google.maps
                                    .DirectionsRenderer({
                                        map: map,
                                        polylineOptions: {
                                            strokeColor: 'blue', // Set the stroke color to blue
                                            strokeWeight: 5,
                                        },
                                        suppressMarkers: true, // Prevent automatic markers from being added
                                    });

                                // Create a request for the directions
                                const request = {
                                    origin: {
                                        lat: parseFloat(technician.latitude),
                                        lng: parseFloat(technician.longitude)
                                    },
                                    destination: {
                                        lat: parseFloat(customerPositions[
                                                customerPositions.length - 1]
                                            .lat),
                                        lng: parseFloat(customerPositions[
                                                customerPositions.length - 1]
                                            .lng)
                                    }, // Last customer
                                    waypoints: customerPositions.map((pos) => ({
                                        location: new google.maps
                                            .LatLng(pos.lat, pos.lng),
                                        stopover: true,
                                    })), // Include all customers as waypoints
                                    travelMode: google.maps.TravelMode.DRIVING,
                                };

                                // Route the directions
                                directionsService.route(request, function(result,
                                    status) {
                                    if (status === google.maps.DirectionsStatus
                                        .OK) {
                                        directionsRenderer.setDirections(
                                        result);
                                    } else {
                                        console.error(
                                            'Directions request failed due to ' +
                                            status);
                                    }
                                });
                            }

                            // Optionally, adjust the map bounds to include all markers
                            const bounds = new google.maps.LatLngBounds();
                            bounds.extend(technicianMarker.getPosition());
                            customerPositions.forEach(pos => bounds.extend(new google
                                .maps.LatLng(pos.lat, pos.lng)));
                            map.fitBounds(bounds);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saving position:', error);
                            alert('Failed to save the position.');
                        }
                    });
                 
                });







            });
            // Helper function to format the start and end time with the interval adjustment
            function formatDateRange(startDate, endDate, interval) {
                var startDateTime = moment(
                    startDate); // Assuming moment.js is available
                var endDateTime = moment(endDate);

                // Add the interval if provided
                if (interval) {
                    startDateTime.add(interval, 'hours');
                    endDateTime.add(interval, 'hours');
                }

                // Format the dates
                return startDateTime.format('MMM D YYYY h:mm A') + ' - ' +
                    endDateTime.format('h:mm A');
            }
        });

     

        $(document).ready(function() {



            



            $(document).on("click", ".tech_profile", function(event) {
                // Check if the clicked element is an image
                if ($(event.target).is("img")) {
                    return; // Exit if the clicked element is an image
                }
                event.preventDefault();

                var profileLink = $(this).closest(".tech_profile");
                var popupContainer = profileLink.next(".popupContainer");

                $(".popupContainer").not(popupContainer).fadeOut();

                var topPosition = profileLink.offset().top + profileLink.outerHeight();
                var leftPosition = profileLink.offset().left;

                popupContainer.css({
                    top: topPosition + "px",
                });

                popupContainer.fadeIn();
            });

            // Click event handler for message-popup links
            $(document).on("click", ".message-popup", function(event) {
                event.preventDefault(); // Prevent default link behavior

                var messagePopup = $(this);
                var smscontainer = messagePopup.closest(".tech-header").find(".smscontainer");

                // Hide all other open smscontainers and settingcontainers
                $(".smscontainer").not(smscontainer).fadeOut();
                $(".settingcontainer").fadeOut();

                // Set position of the smscontainer to the right of popupContainer
                smscontainer.css({
                    top: messagePopup.offset().top - 10 + "px",
                    left: messagePopup.offset().left + $(".popupContainer").outerWidth() - 60 +
                        "px", // Adjust 10 pixels for spacing
                });

                smscontainer.fadeToggle();
            });

            $('#sendSmsButton').on('click', function(e) {
                e.preventDefault();

                var formData = new FormData($('#sendSmsForm')[0]);

                $.ajax({
                    url: '{{ route('send_sms_schedule') }}', // Your route
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            // Clear the textarea
                            $('#sendSmsForm textarea').val('');

                            // Hide the smscontainer after SMS is sent successfully
                            var smscontainer = $('#sendSmsForm').closest('.smscontainer');
                            smscontainer.fadeOut();
                        } else {
                            // Handle the error case (optional)
                            console.log('SMS sending failed');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // Log any errors
                    }
                });
            });

            // Click event handler for setting-popup links
            $(document).on("click", ".setting-popup", function(event) {
                event.preventDefault(); // Prevent default link behavior

                var settingPopup = $(this);
                var settingcontainer = settingPopup.closest(".tech-header").find(".settingcontainer");

                // Hide all other open settingcontainers and smscontainers
                $(".settingcontainer").not(settingcontainer).fadeOut();
                $(".smscontainer").fadeOut();

                // Set position of the settingcontainer to the right of popupContainer (next to the message container)
                settingcontainer.css({
                    top: settingPopup.offset().top - 50 + "px",
                    left: settingPopup.offset().left + $(".popupContainer").outerWidth() + $(
                        ".smscontainer").outerWidth() - 315 + "px", // Adjust for both containers
                });

                settingcontainer.fadeToggle();
            });

            // Click event listener for the document to close popups when clicking outside
            $(document).click(function(event) {
                var target = $(event.target);

                if (
                    !target.closest(".popupContainer").length &&
                    !target.closest(".tech_profile").length &&
                    !target.closest(".smscontainer").length &&
                    !target.closest(".settingcontainer").length
                ) {
                    $(".popupContainer, .smscontainer, .settingcontainer").fadeOut();
                }
            });
        });

        $(function() {
            $(".day").sortable({
                connectWith: ".day",
                cursor: "move",
                helper: "clone",
                items: "> .dragDiv",
                stop: function(event, ui) {
                    var $item = ui.item;
                    var eventLabel = $item.text();
                    var newDay = $item.parent().attr("id");


                    // Here's where am ajax call will go

                }
            }).disableSelection();
        });

        $(document).ready(function() {

            var isDragging = false;
            var isResizing = false;

            // Prevent tooltip from showing while dragging
            $(document).on('mouseenter', '.stretchJob', function() {
                if (!isDragging) {
                    var template = $(this).find('.template');
                    if (!this._tippy) {
                        tippy(this, {
                            content: template.html(),
                            allowHTML: true,
                        });
                    }
                }
            });

            $(document).on('click', '.clickPoint1', function(e) {
                if (!isResizing) {
                    e.stopPropagation();
                    var popupDiv = $(this).find('.popupDiv1');

                    // Hide any previously displayed popupDiv elements
                    $('.popupDiv1').not(popupDiv).hide();

                    // Calculate the position of the popupDiv based on the clicked point
                    var mouseX = e.pageX - 180;
                    var mouseY = e.pageY - 100;

                    // Get the dimensions of the popupDiv and the window
                    var popupWidth = popupDiv.outerWidth();
                    var popupHeight = popupDiv.outerHeight();
                    var windowWidth = $(window).width();
                    var windowHeight = $(window).height();

                    // Calculate the position for the popupDiv, ensuring it stays within the window
                    var topPosition = mouseY;
                    var leftPosition = mouseX;

                    // Adjust the position if the popupDiv overflows the window
                    if (topPosition + popupHeight > windowHeight) {
                        topPosition = windowHeight - popupHeight - 10; // Add a margin of 10px
                    }
                    if (leftPosition + popupWidth > windowWidth) {
                        leftPosition = windowWidth - popupWidth - 10; // Add a margin of 10px
                    }

                    // Set the position and show the popupDiv
                    popupDiv.css({
                        position: 'absolute',
                        top: topPosition + 'px',
                        left: leftPosition + 'px',
                        zIndex: 1000 // Ensure the popupDiv is above other elements
                    }).toggle();

                    // Add keydown event listener to hide popupDiv when Esc is pressed
                    $(document).on('keydown', function(e) {
                        if (e.key === "Escape") { // Check if the pressed key is "Esc"
                            popupDiv.hide();
                        }
                    });
                    // Hide the popup div when clicking outside of it
                    $(document).on('click', function(e) {
                        popupDiv.hide();
                    });

                }
            });

            $('.eventSchedule').on('click', function() {
                var id = $(this).attr('data-id');
                $('#event_technician_id').val(id);
            });

            $('.event_start_time').hide();
            $('.event_end_time').hide();
            $('.f_start').hide();
            $('.s_to').hide();

            $(document).on('change', '.event_type', function() {
                var event_type = $(this).val();
                if (event_type == 'full') {
                    $('.event_start_date').show();
                    $('.event_end_date').show();
                    $('.event_start_time').hide();
                    $('.event_end_time').hide();
                    $('.f_start').hide();
                    $('.s_to').hide();
                } else {
                    $('.event_start_date').show();
                    $('.event_start_time').show();
                    $('.event_end_date').hide();
                    $('.event_end_time').show();
                    $('.f_start').show();
                    $('.s_to').show();
                }
            });

            $('#addEvent').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // 'this' refers to the form DOM element

                // Make an AJAX request to submit the form data
                $.ajax({
                    url: $(this).attr('action'), // Get the form action attribute
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        // Handle success response here

                        if (data.success === true) {
                            // If success is true, close the current modal
                            $('#event').modal('hide');
                            // Display a success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'The event has been added successfully.'
                            }).then(function() {
                                // Reset form fields
                                $('#addEvent')[0].reset();
                                location.reload();

                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error submitting form data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Operation failed. Please try again.' + error
                        });
                    }
                });
            });

            $(document).on('change', '.technician_check', function() {
                var isChecked = $(this).prop('checked');
                var id = $(this).data('id'); // Retrieve the value of the data-id attribute

                if (isChecked) {
                    // Show elements with class tech-header and day that match the id
                    $('.tech-header[data-tech-id="' + id + '"]').show();
                    $('.clickPoint1[data-technician-id="' + id + '"]').show();
                } else {
                    // Hide elements with class tech-header and day that match the id
                    $('.tech-header[data-tech-id="' + id + '"]').hide();
                    $('.clickPoint1[data-technician-id="' + id + '"]').hide();
                }
            });

            // Function to initialize draggable elements
            function initializeDraggable() {
                $('.day .dragDiv').draggable({
                    helper: 'clone',
                    cursor: 'move',
                    start: function(event, ui) {
                        if (isResizing) {
                            return false; // Prevent dragging if resizing
                        }
                        isDragging = true;
                    },
                    stop: function(event, ui) {
                        isDragging = false;
                    }
                });
            }

            // Function to revert drag operation
            function revertDrag(ui) {
                ui.helper.animate(ui.originalPosition, "slow");
            }

            // Function to initialize droppable elements
            function initializeDroppable() {
                $('.day').droppable({

                    tolerance: 'pointer',
                    drop: function(event, ui) {
                        console.log('yash');
                        var jobId = ui.draggable.attr('id');
                        var duration = ui.draggable.attr('data-duration');
                        var newTechnicianId = $(this).data('technician-id');
                        var techName = ui.draggable.attr('data-technician-name');
                        var timezone = ui.draggable.attr('data-timezone-name');
                        var date = $(this).data('date');
                        var time = $(this).data('slot-time');
                        let name;
                        let zoneName;

                        var height_slot = duration ? (duration / 30) * 40 : 0;

                        // Temporarily move the job to the new position
                        var originalContainer = ui.draggable.parent();
                        var newContainer = $(event.target);
                        var originalJobCount = originalContainer.children('.dts').length;
                        var newJobCount = newContainer.children('.dts').length + 1;
                        var newJobWidth = 100 / newJobCount;
                        var originalJobWidth = 100 / (originalJobCount - 1);

                        // Remove the draggable element from its original container
                        ui.draggable.remove();

                        // Set the width of existing jobs in the new container
                        newContainer.children('.dts').each(function() {
                            $(this).css('width', newJobWidth + 'px');
                        });

                        // Append the new job with the calculated width
                        var newJobElement = $('<div>', {
                            id: jobId,
                            class: 'dts dragDiv stretchJob border width_job_' + newTechnicianId,
                            css: {
                                height: height_slot + 'px',
                                position: 'relative',
                                width: newJobWidth + 'px'
                            },
                            'data-duration': duration,
                            'data-technician-name': techName,
                            'data-timezone-name': timezone,
                            html: ui.draggable.html()
                        });

                        newContainer.append(newJobElement);

                        // Update the width of the original container if any jobs remain
                        if (originalJobCount > 1) {
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });
                        }

                        // Make the new job element draggable
                        newJobElement.draggable({
                            helper: 'clone',
                            cursor: 'move',
                            start: function(event, ui) {
                                if (isResizing) {
                                    return false;
                                }
                                isDragging = true;
                            },
                            stop: function(event, ui) {
                                isDragging = false;
                            }
                        });

                        // Ask for confirmation to move the job
                        $.ajax({
                            url: "{{ route('get.techName') }}",
                            type: 'GET',
                            data: {
                                techId: newTechnicianId,
                            },
                            success: function(response) {
                                name = response.name;
                                zoneName = response.time_zone.timezone_name;

                                Swal.fire({
                                    title: `Do you want to move job from ${techName} to ${name}?`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        if (timezone == zoneName) {
                                            updateJobTechnician(jobId, duration,
                                                date, time, newTechnicianId, ui,
                                                name, zoneName, newJobElement,
                                                originalContainer,
                                                originalJobCount);
                                        } else {
                                            Swal.fire({
                                                title: `Do you want to change the Job from ${timezone} to ${zoneName}?`,
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No',
                                                reverseButtons: true
                                            }).then((innerResult) => {
                                                if (innerResult
                                                    .isConfirmed) {
                                                    updateJobTechnician(
                                                        jobId, duration,
                                                        date, time,
                                                        newTechnicianId,
                                                        ui, name,
                                                        zoneName,
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                    );
                                                } else {
                                                    revertTempMove(
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                    );
                                                }
                                            });
                                        }
                                    } else {
                                        revertTempMove(newJobElement,
                                            originalContainer, originalJobCount);
                                    }
                                });
                            },
                            error: function(error) {
                                revertTempMove(newJobElement, originalContainer,
                                    originalJobCount);
                                console.error(error);
                            }
                        });

                        function updateJobTechnician(jobId, duration, date, time, newTechnicianId, ui,
                            name, zoneName, newJobElement, originalContainer, originalJobCount) {
                            $.ajax({
                                url: '{{ route('updateJobTechnician') }}',
                                method: 'POST',
                                data: {
                                    job_id: jobId,
                                    duration: duration,
                                    date: date,
                                    time: time,
                                    technician_id: newTechnicianId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    //console.log('Job updated successfully:', response);
                                    if (response.success) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'The job has been moved successfully',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        revertTempMove(newJobElement, originalContainer,
                                            originalJobCount);
                                        console.error('Error:', response.error);
                                    }
                                },
                                error: function(error) {
                                    revertTempMove(newJobElement, originalContainer,
                                        originalJobCount);
                                    console.error(error);
                                }
                            });
                        }

                        function revertTempMove(newJobElement, originalContainer, originalJobCount) {
                            // Remove the temporary new job element from the new container
                            newJobElement.remove();

                            // Append the draggable job back to the original container
                            originalContainer.append(ui.draggable);

                            // Reinitialize draggable functionality for the element
                            ui.draggable.draggable({
                                helper: 'clone',
                                cursor: 'move',
                                start: function(event, ui) {
                                    if (isResizing) {
                                        return false;
                                    }
                                    isDragging = true;
                                },
                                stop: function(event, ui) {
                                    isDragging = false;
                                }
                            });

                            // Calculate the correct number of jobs in the original container
                            var currentJobCount = originalContainer.children('.dts').length;

                            // Update the width of all jobs in the original container
                            var originalJobWidth = 100 / currentJobCount;
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });

                            // Update the width of all jobs in the new container
                            var newJobWidth = 100 / newContainer.children('.dts').length;
                            newContainer.children('.dts').each(function() {
                                $(this).css('width', newJobWidth + 'px');
                            });
                        }

                    }
                });
            }

            // Function to initialize resizable elements
            function initializeResizable() {
                interact('.stretchJob').resizable({
                        edges: {
                            left: false,
                            right: false,
                            bottom: true,
                            top: false
                        }
                    })
                    .on('resizestart', function(event) {
                        // Disable dragging while resizing
                        isResizing = true;

                        // Set original height if not already set
                        if (!event.target.dataset.originalHeight) {
                            event.target.dataset.originalHeight = event.target.style.height;
                        }
                    })
                    .on('resizemove', function(event) {
                        let target = event.target;
                        let originalHeight = parseFloat(target.dataset.originalHeight) || parseFloat(target
                            .style.height) || 0;
                        let heightChange = event.rect.height - originalHeight;

                        // Update the height directly with the cursor movement
                        let newHeight = originalHeight + heightChange;

                        // Set a minimum height to prevent collapsing too much
                        let minHeight = 40; // Equivalent to 30 minutes
                        if (newHeight < minHeight) {
                            newHeight = minHeight;
                        }

                        // Update the element's height
                        target.style.height = newHeight + 'px';

                        // Calculate and update the new duration
                        let heightPer30Min = 40; // height for 30 minutes
                        let newDuration = Math.round(newHeight / heightPer30Min) * 30;
                        target.dataset.duration = newDuration;
                    })
                    .on('resizeend', function(event) {
                        // Re-enable dragging after resizing
                        isResizing = false;

                        // Get the updated duration
                        let newDuration = parseInt(event.target.dataset.duration);

                        // Get the job ID
                        let jobId = event.target.id; // Assuming the element's ID is the job ID

                        // AJAX request to update duration in database
                        updateDurationInDatabase(jobId, newDuration, event.target);
                    });
            }

            function updateDurationInDatabase(jobId, newDuration, target) {
                Swal.fire({
                    title: 'Do you want to change time?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX POST request to your Laravel endpoint
                        $.ajax({
                            url: "{{ route('schedule.update_job_duration') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if using Laravel CSRF protection
                            },
                            data: {
                                duration: newDuration,
                                jobId: jobId
                            },
                            success: function(response) {
                                // Handle success if needed
                                Swal.fire('Success',
                                        'The duration has been updated successfully', 'success')
                                    .then(() => {});
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating duration:', error);
                                // Handle error if needed
                                Swal.fire('Error',
                                    'Failed to update duration. Please try again.', 'error');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Reset the height to the original height
                        let originalHeight = parseFloat(target.dataset.originalHeight);
                        target.style.height = originalHeight + 'px';
                    }
                });
            }

            // Function to initialize all necessary components
            function initializeComponents() {
                initializeDraggable();
                initializeDroppable();
                initializeResizable();
            }

            // Call the initialization function once at the start
            initializeComponents();

            // Fetch new schedule data and reinitialize components

            $(document).on('click', '#preDate1, #tomDate1', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                var date = $(this).data('previous-date') || $(this).data('tomorrow-date');
                fetchSchedule(date);
            });

            // Event listener for showing the map
            $('#mapSection1').hide();

            $(document).on('click', 'a[href="#navMap1"]', function(e) {
                e.preventDefault();
                $('#scheduleSection1').hide();
                $('.cbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#mapSection1').show();
                initMap('mapScreen1', '#scheduleSection1');
            });

            // Event listener for hiding the map
            $(document).on('click', 'a[href="#navCalendar1"]', function(e) {
                e.preventDefault();
                $('#mapSection1').hide();
                $('.mbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection1').show();
            });

            function fetchSchedule(date) {
                $.ajax({
                    url: "{{ route('schedule.demoScheduleupdate') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#newdemodata').empty().html(response.tbody);
                        initializeComponents();
                        initializeDatepicker('#selectDates1', fetchSchedule);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }


            var openInfoWindowpop = null;
            var maps = {};

            function initMap(mapElementId, scheduleSectionId) {

                if (maps[mapElementId]) {
                    destroyMap(mapElementId); // Destroy the existing map instance
                }

                maps[mapElementId] = new google.maps.Map(document.getElementById(mapElementId), {
                    zoom: 5,
                    center: {
                        lat: 39.8283,
                        lng: -98.5795
                    }
                });

                var selectedDate = $(scheduleSectionId).data('map-date');
                if (!selectedDate) {
                    selectedDate = new Date().toISOString().split('T')[0];
                    $(scheduleSectionId).data('map-date', selectedDate);
                    console.log("No date provided. Using current date:", selectedDate);
                }

                fetchJobData(mapElementId, selectedDate);
            }


            function fetchJobData(mapElementId, date) {
                $.ajax({
                    url: '{{ route('schedule.getJobsByDate') }}',
                    method: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        if (response.data) {
                            setMarkers(mapElementId, response.data);
                        } else {
                            console.error('Error: No job data returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function setMarkers(mapElementId, markersData) {
                clearMarkers(mapElementId);

                const markers = markersData.filter(marker => marker.latitude && marker.longitude);
                var bounds = new google.maps.LatLngBounds();

                markers.forEach(marker => {
                    var markerInstance = new google.maps.Marker({
                        position: {
                            lat: parseFloat(marker.latitude),
                            lng: parseFloat(marker.longitude)
                        },
                        map: maps[mapElementId],
                        title: marker.name
                    });

                    markerInstance.addListener('click', function() {
                        fetchMarkerDetails(markerInstance, marker.job_id);
                    });

                    bounds.extend(markerInstance.position);
                });

                if (markers.length > 0) {
                    maps[mapElementId].fitBounds(bounds);
                } else {
                    console.log("No markers to set on map.");
                }
            }

            function fetchMarkerDetails(markerInstance, jobId) {
                $.ajax({
                    url: '{{ route('schedule.getMarkerDetails') }}',
                    method: 'GET',
                    data: {
                        id: jobId
                    },
                    success: function(response) {
                        if (response.content) {
                            if (openInfoWindowpop) {
                                openInfoWindowpop.close();
                            }
                            openInfoWindowpop = openInfoWindow(markerInstance, response.content);
                        } else {
                            console.error('Error: No content returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function openInfoWindow(marker, content) {
                var infoWindow = new google.maps.InfoWindow({
                    content: content
                });
                infoWindow.open(maps[marker.map.getDiv().id], marker);
                return infoWindow;
            }

            function clearMarkers(mapElementId) {
                if (maps[mapElementId].markers) {
                    maps[mapElementId].markers.forEach(marker => marker.setMap(null));
                }
                maps[mapElementId].markers = [];
            }

            window.onload = function() {
                initMap('mapScreen1', '#scheduleSection1');
            };

            function destroyMap(mapElementId) {
                if (maps[mapElementId]) {
                    // Clear any existing markers or overlays if applicable
                    clearMarkers(mapElementId);

                    // Clear event listeners associated with the map
                    google.maps.event.clearInstanceListeners(maps[mapElementId]);

                    // Set the map instance to null, effectively "destroying" it
                    maps[mapElementId] = null;
                }
            }

            function initializeDatepicker(selector, fetchFunction) {
                $(selector).datepicker({
                    format: 'yyyy-mm-dd', // Specify the format
                    autoclose: true, // Close the datepicker when a date is selected
                    todayHighlight: true // Highlight today's date
                }).on('changeDate', function(selected) {
                    var selectedDate = new Date(selected.date);
                    var date = selectedDate.getFullYear() + '-' +
                        (selectedDate.getMonth() + 1).toString().padStart(2, '0') + '-' +
                        selectedDate.getDate().toString().padStart(2, '0');
                    fetchFunction(date);
                });
            }
            initializeDatepicker('#selectDates1', fetchSchedule);


        });
    </script>
   
    <script>
      


        function initMap(technicianLocation, sortedCustomers) {
            // Create a new map centered at the technician's location
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
            });

            // Create a marker for the technician's location
            const technicianMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                map: map,
                title: technicianLocation.name,
                label: 'T', // Label for technician
            });

            // Add click event listener for technician marker
            google.maps.event.addListener(technicianMarker, 'click', function() {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<h4>${technicianLocation.name}</h4><p> ${technicianLocation.full_address}</p>`, // Show full address
                });
                infoWindow.open(map, technicianMarker);
            });

            // Prepare waypoints for the route (customers)
            const waypoints = sortedCustomers.map((customer) => ({
                location: new google.maps.LatLng(parseFloat(customer.latitude), parseFloat(customer.longitude)),
                stopover: true,
            }));

            // Create DirectionsService and DirectionsRenderer
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                polylineOptions: {
                    strokeColor: 'blue', // Set the stroke color to blue
                    strokeWeight: 5,
                },
                suppressMarkers: true, // Prevent automatic markers from being added
            });

            // Create a request for the directions
            const request = {
                origin: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                destination: {
                    lat: parseFloat(sortedCustomers[sortedCustomers.length - 1].latitude),
                    lng: parseFloat(sortedCustomers[sortedCustomers.length - 1].longitude)
                }, // last customer
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            // Route the directions
            directionsService.route(request, function(result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Directions request failed due to ' + status);
                }
            });

            // Create markers for each customer
            sortedCustomers.forEach((customer, index) => {
                const customerMarker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(customer.latitude),
                        lng: parseFloat(customer.longitude)
                    },
                    map: map,
                    title: `${customer.name} - ${customer.full_address}`,
                    label: `${index + 1}`, // Numbering starts from 1
                });

                // Add click event listener for customer markers
                google.maps.event.addListener(customerMarker, 'click', function() {
                    const infoWindow = new google.maps.InfoWindow({
                        content: `<h4>${customer.name}</h4><p>${customer.full_address}</p>`,
                    });
                    infoWindow.open(map, customerMarker);
                });
            });

            // Optionally, adjust the map bounds to include all markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(technicianMarker.getPosition());
            sortedCustomers.forEach(customer => {
                bounds.extend(new google.maps.LatLng(customer.latitude, customer.longitude));
            });
            map.fitBounds(bounds);
        }

        
        function initMap1(technicianLocation, sortedCustomersMap3) {
            // Create a new map centered at the technician's location
            const map = new google.maps.Map(document.getElementById('map3'), {
                zoom: 12,
                center: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
            });

            // Create a marker for the technician's location
            const technicianMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                map: map,
                title: technicianLocation.name,
                label: 'T', // Label for technician
            });

            // Add click event listener for technician marker
            google.maps.event.addListener(technicianMarker, 'click', function() {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<h4>${technicianLocation.name}</h4><p> ${technicianLocation.full_address}</p>`, // Show full address
                });
                infoWindow.open(map, technicianMarker);
            });

            // Prepare waypoints for the route (customers)
            const waypoints = sortedCustomersMap3.map((customer) => ({
                location: new google.maps.LatLng(parseFloat(customer.latitude), parseFloat(customer.longitude)),
                stopover: true,
            }));

            // Create DirectionsService and DirectionsRenderer
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                polylineOptions: {
                    strokeColor: 'blue', // Set the stroke color to blue
                    strokeWeight: 5,
                },
                suppressMarkers: true, // Prevent automatic markers from being added
            });

            // Create a request for the directions
            const request = {
                origin: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                destination: {
                    lat: parseFloat(sortedCustomersMap3[sortedCustomersMap3.length - 1].latitude),
                    lng: parseFloat(sortedCustomersMap3[sortedCustomersMap3.length - 1].longitude)
                }, // last customer
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            // Route the directions
            directionsService.route(request, function(result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Directions request failed due to ' + status);
                }
            });

            // Create markers for each customer
            sortedCustomersMap3.forEach((customer, index) => {
                const customerMarker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(customer.latitude),
                        lng: parseFloat(customer.longitude)
                    },
                    map: map,
                    title: `${customer.name} - ${customer.full_address}`,
                    label: `${index + 1}`, // Numbering starts from 1
                });

                // Add click event listener for customer markers
                google.maps.event.addListener(customerMarker, 'click', function() {
                    const infoWindow = new google.maps.InfoWindow({
                        content: `<h4>${customer.name}</h4><p>${customer.full_address}</p>`,
                    });
                    infoWindow.open(map, customerMarker);
                });
            });

            // Optionally, adjust the map bounds to include all markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(technicianMarker.getPosition());
            sortedCustomersMap3.forEach(customer => {
                bounds.extend(new google.maps.LatLng(customer.latitude, customer.longitude));
            });
            map.fitBounds(bounds);
        }


             
        function initMap2(technicianLocation, sortedCustomersMap4) {
            // Create a new map centered at the technician's location
            const map = new google.maps.Map(document.getElementById('map4'), {
                zoom: 12,
                center: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
            });

            // Create a marker for the technician's location
            const technicianMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                map: map,
                title: technicianLocation.name,
                label: 'T', // Label for technician
            });

            // Add click event listener for technician marker
            google.maps.event.addListener(technicianMarker, 'click', function() {
                const infoWindow = new google.maps.InfoWindow({
                    content: `<h4>${technicianLocation.name}</h4><p> ${technicianLocation.full_address}</p>`, // Show full address
                });
                infoWindow.open(map, technicianMarker);
            });

            // Prepare waypoints for the route (customers)
            const waypoints = sortedCustomersMap4.map((customer) => ({
                location: new google.maps.LatLng(parseFloat(customer.latitude), parseFloat(customer.longitude)),
                stopover: true,
            }));

            // Create DirectionsService and DirectionsRenderer
            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                polylineOptions: {
                    strokeColor: 'blue', // Set the stroke color to blue
                    strokeWeight: 5,
                },
                suppressMarkers: true, // Prevent automatic markers from being added
            });

            // Create a request for the directions
            const request = {
                origin: {
                    lat: parseFloat(technicianLocation.latitude),
                    lng: parseFloat(technicianLocation.longitude)
                },
                destination: {
                    lat: parseFloat(sortedCustomersMap4[sortedCustomersMap4.length - 1].latitude),
                    lng: parseFloat(sortedCustomersMap4[sortedCustomersMap4.length - 1].longitude)
                }, // last customer
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
            };

            // Route the directions
            directionsService.route(request, function(result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Directions request failed due to ' + status);
                }
            });

            // Create markers for each customer
            sortedCustomersMap4.forEach((customer, index) => {
                const customerMarker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(customer.latitude),
                        lng: parseFloat(customer.longitude)
                    },
                    map: map,
                    title: `${customer.name} - ${customer.full_address}`,
                    label: `${index + 1}`, // Numbering starts from 1
                });

                // Add click event listener for customer markers
                google.maps.event.addListener(customerMarker, 'click', function() {
                    const infoWindow = new google.maps.InfoWindow({
                        content: `<h4>${customer.name}</h4><p>${customer.full_address}</p>`,
                    });
                    infoWindow.open(map, customerMarker);
                });
            });

            // Optionally, adjust the map bounds to include all markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(technicianMarker.getPosition());
            sortedCustomersMap4.forEach(customer => {
                bounds.extend(new google.maps.LatLng(customer.latitude, customer.longitude));
            });
            map.fitBounds(bounds);
        }


        //map2 function
    </script>




    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo"></script>
@endsection
