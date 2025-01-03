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
                        $('#map1').empty();

                        $('.mapbestroute').empty();


                        $('#map4').show();
                        $('#sortableCustomerList3').empty();
                        $('#map1').empty();
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
                                            <div id="map1" style="width: 75%; height: 500px; border: 1px solid #ccc;"></div>
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
                        customersWithNonZeroPosition1.sort((a, b) => a.position - b
                            .position);

                        // Append customers with non-zero positions first
                        customersWithNonZeroPosition1.forEach(function(customer1) {
                            // Parse the 'number' property as an integer
                            const positionDisplay = parseInt(customer1.number, 10);

                            // Only append customers with a valid number
                            if (!isNaN(
                                    positionDisplay
                                )) { // Check if it's a valid number
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
                                'data-isroute'
                            ); // Get the current value of data-isroute
                            $(this).prop('checked', isRoute ==
                                '1'
                            ); // Set checkbox checked if data-isroute is 1, unchecked if 0
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
                            $('#map1').show(); // Show map
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
                            $('#map1').hide(); // Hide map
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
                                $('#map1').hide(); // Hide map
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
                        $('#map1').empty();
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
                        $('#map1').hide();
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
                        if (response.sorted_customers3 && Array.isArray(response
                                .sorted_customers3)) {
                            response.sorted_customers3.forEach(function(customer2) {
                                if (customer2.position === 0) {
                                    customersWithZeroPosition2.push(
                                        customer2);
                                } else {
                                    customersWithNonZeroPosition2.push(
                                        customer2);
                                }
                            });

                            // Sort customers with non-zero positions based on their 'position' property
                            customersWithNonZeroPosition2.sort((a, b) => a
                                .position - b.position);

                            // Append customers with non-zero positions first
                            customersWithNonZeroPosition2.forEach(function(
                                customer2) {
                                const positionDisplay = parseInt(customer2
                                    .number, 10);
                                if (!isNaN(positionDisplay) && customer2
                                    .full_address
                                ) { // Ensure valid number and full address
                                    $('#sortableCustomerList3').append(` <li style="padding: 12px; border: 1px solid #ddd; margin-bottom: 8px; border-radius: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.2s;">

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
                                $('#sortableCustomerList3').append(` <li style="padding: 10px; border: 1px solid #ddd; margin-bottom: 5px; border-radius: 8px; cursor: move; background-color: #f9f9f9; transition: background-color 0.3s;">
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
                            if (customersWithZeroPosition2.length === 0 &&
                                customersWithNonZeroPosition2.length === 0) {
                                if ($(
                                        '#sortableCustomerList3 li:contains("No Job found")'
                                    )
                                    .length === 0) {
                                    $('#sortableCustomerList3').append(`
                                            <li style="padding: 8px; border: 1px solid #ddd; margin-bottom: 5px;"> 
                                                <b>No Job found</b>
                                            </li>
                                    `);
                                }
                            }

                            // Ensure technician_location exists before passing to initMap2
                            if (response.technician_location) {
                                initMap2(response.technician_location, response
                                    .sorted_customers3);
                            } else {
                                console.error('Technician location is undefined:',
                                    response);
                            }

                        } else {
                            console.error(
                                'Sorted customers array is undefined or not an array:',
                                response);
                            // If response is not valid, show "No Job found" only if not already appended
                            if ($(
                                    '#sortableCustomerList3 li:contains("No Job found")'
                                )
                                .length === 0) {
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
                        if ($('#sortableCustomerList3 li:contains("No Job found")')
                            .length === 0) {
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
                    const isroute = $(this).find('.route-checkbox').is(':checked') ? 1 :
                        0;

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
                            const $customerLi = $(
                                '#sortableCustomerList1 li[data-id="' +
                                customer.user_id + '"]');

                            if ($customerLi.length) {
                                // Update the checkbox state
                                const isChecked = customer.isroute == "1" ?
                                    'checked' : '';
                                $customerLi.find('.route-checkbox').prop(
                                    'checked', isChecked);

                                // Update the job_onmap_reaching_timing display
                                $customerLi.find('.job-title-h5').first()
                                    .text(
                                        `Driving Timing: ${customer.job_onmap_reaching_timing || '0'}.M`
                                    ); // Update the first job-title-h5

                                // Update the name if needed (Optional)
                                $customerLi.find('.job-title-h5').last()
                                    .text(customer
                                        .name
                                    ); // Update the last job-title-h5 if needed

                                // Update the duration if needed (Optional)
                                $customerLi.find('small').last().text(
                                    customer.duration
                                ); // Update the last small element for duration
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
</script>
