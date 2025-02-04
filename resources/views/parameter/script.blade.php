<script>
    $(document).ready(function () {

        $('#product-filter-rows').hide();
        $('#product-table').hide();

          // On change for types 
        $('#type-select').on('change', function () {
            var type = $(this).val();

            if (type == 'jobs') {
                $('#job-filter-rows , #zero_configParam').show();
                $('#product-filter-rows , #product-table').hide();
            } else {
                $('#job-filter-rows , #zero_configParam').hide();
                $('#product-filter-rows , #product-table').show();
            }

        });

        function fetchFilteredData() {
            // Get all filter values
            let filters = {};
            $('.filter-input').each(function () {
                filters[$(this).attr('id')] = $(this).val();
            });

            // Send AJAX request
            $.ajax({
                url: '{{ route("jobsParam.filter") }}', // Update with your route
                method: 'GET', // Or POST if required
                data: filters,
                success: function (response) {
                    // Replace the table body with the filtered data
                    $('#zero_configParam tbody').html(response.html);
                },
                error: function () {
                    alert('Failed to fetch filtered data.');
                }
            });
        }

        // Trigger fetchFilteredData when a filter changes
        $('.filter-input').on('change', fetchFilteredData);
  
        // for products filter 

        function fetchFilteredProductData() {
            // Get all filter values
            let filters = {};
            $('.filter-products-inputs').each(function () {
                filters[$(this).attr('id')] = $(this).val();
            });

            // Send AJAX request
            $.ajax({
                url: '{{ route("jobsProductParam.filter") }}', // Update with your route
                method: 'GET', // Or POST if required
                data: filters,
                success: function (response) {
                    // Replace the table body with the filtered data
                    $('#product-table tbody').html(response.html);
                },
                error: function () {
                    alert('Failed to fetch filtered data.');
                }
            });
        }

        // Trigger fetchFilteredData when a filter changes
        $('.filter-products-inputs').on('change', fetchFilteredProductData);

          
        $('#customer-select').select2({
            placeholder: 'Search customer...',
            allowClear: true, // Allow clearing the selection
            data: [
                { id: '', text: 'All' }, // Add the "All" option directly in the data array
            ],
            ajax: {
                url: '{{route("search-customers-param")}}', // Replace with your endpoint
                dataType: 'json',
                delay: 250, // Delay in ms
                data: function (params) {
                    return {
                        query: params.term, // Search query
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(customer => ({
                            id: customer.id,
                            text: customer.name,
                        })),
                    };
                },
                cache: true,
            },
        });

        // Trigger a change event to select the "All" option initially
        $('#customer-select').on('keyup', function () {
            $(this).trigger('change');
        });

        document.getElementById("frequency-filter").addEventListener("change", function() {
            let frequency = this.value;
            
            // Show/Hide Weekly Days Dropdown
            document.getElementById("weekly-from-container").style.display = (frequency === "weekly") ? "block" : "none";
            document.getElementById("weekly-to-container").style.display = (frequency === "weekly") ? "block" : "none";

            // Show/Hide Monthly Dates Dropdown
            document.getElementById("monthly-from-container").style.display = (frequency === "monthly") ? "block" : "none";
            document.getElementById("monthly-to-container").style.display = (frequency === "monthly") ? "block" : "none";
        });



    });
</script>

<script>

    $(document).ready(function () {
        // Event listener for dropdown change
         $('#saved-filters-dropdown').on('change', function () {
            const filterId = $(this).val(); // Get the selected filter ID

            if (filterId) {
                fetchFilterDetails(filterId); // Fetch details for the selected filter
            } else {
                alert('Please select a valid filter.');
            }
        });

    // Function to fetch saved filter details
    function fetchFilterDetails(filterId) {
        $.ajax({
            url: "{{ route('get-filter-data-parameter-agains-user-id') }}", // API route
            method: 'POST',
            data: {
                filter_id: filterId,
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for security
            },
            success: function (response) {
                if (response && response.data) {

                    applyFilterValues(response.data); // Apply fetched values to form fields
                } else {
                    // alert('No data found for the selected filter.');
                }
            },
            error: function (error) {
                console.error('Error fetching filter details:', error);
                // alert('Failed to fetch filter details. Please try again.');
            }
        });
    }

    // Function to apply filter values to fields
   function applyFilterValues(data) {
        const fieldsToUpdate = {
            type: '#type-select',
            technician: '#technician-select',
            customer: '#customer-select',
            job_title: '#title-filter',
            priority: '#priority-filter',
            warranty: '#warranty-filter',
            services: '#services-filter',
            products: '#products-filter',
            is_published: '#IsPublished-filter',
            is_confirmed: '#IsConfirmed-filter',
            job_status: '#job-status-filter',
            show_on_schedule: '#showOnSchedule-filter',
            flag: '#flag-filter',
            manufacturer: '#manufacturer-filter',
            appliances: '#appliances-filter',
            customer_tags: '#customer-tags-filter',
        
            start_date: '#start-date-filter',
            end_date: '#end-date-filter',
            product_filter_stock: '#stock-filter',
            product_filter_category: '#category-filter',
            product_filter_manufacturer: '#manufacturer',
            product_filter_supppliers: '#supppliers-filter',
            product_filter_status: '#status-filter'
        };

        let delay = 500; // Start with a base delay

        // Iterate over fields and set values with a delay
        $.each(fieldsToUpdate, function (field, selector) {
            if (data[field] !== null && data[field] !== undefined) {
                setTimeout(() => {
                    $(selector).val(data[field]).trigger('change');
                }, delay);
                delay += 500; // Increase delay for each field
            }
        });

        // Handle multi-select fields (services, products, customer_tags, appliances) with delay
        ['services', 'products', 'customer_tags', 'appliances'].forEach((field) => {
            setTimeout(() => {
                if (data[field]) {
                    let valuesArray = JSON.parse(data[field]);
                    $(fieldsToUpdate[field]).val(valuesArray).trigger('change');
                } else {
                    $(fieldsToUpdate[field]).val(null).trigger('change');
                }
            }, delay);
            delay += 500;
        });

        // Special handling for Select2 with AJAX (Customer dropdown) with delay
        if (data.customer) {
            setTimeout(() => {
                let customerId = data.customer;
                let $customerSelect = $('#customer-select');

                // Check if the option already exists
                if ($customerSelect.find(`option[value="${customerId}"]`).length === 0) {
                    // Fetch the customer name from the server
                    $.ajax({
                        url: '{{ route("search-customers-param") }}',
                        data: { customerId: customerId },
                        dataType: 'json',
                        success: function (response) {
                            let customer = response; // Assuming only one result
                            let newOption = new Option(customer.name, customer.id, true, true);
                            $customerSelect.append(newOption).val(customer.id).trigger('change');
                        }
                    });
                } else {
                    $customerSelect.val(customerId).trigger('change');
                }
            }, delay);
        }
    }






        // Event listener for saved filter selection
        $('#saved-filters-dropdown').on('change', function () {
            let selectedFilter = $(this).find(':selected').data('filter');
            $('#frontier_loader').show();
            
            setTimeout(function() {
                $('#frontier_loader').hide();
                if (selectedFilter) {
                    applyFilterValues(selectedFilter); // This runs after loader is hidden
                }
            }, 1000);
        });



    });


</script>
<script>
    $(document).ready(function () {
        let title, priority; // Ensure these are declared globally if used elsewhere

        // Open modal when clicking save filters button
        $(document).on('click', '#save-filters-modal', function () {
            $('#filterNameModal').modal('show'); // Show the modal
        });

        $(document).on('click', '#close-showmodel', function () {
            $('#filterNameModal').modal('hide'); // Hide the modal
        });

        // Submit filter when clicking submit button inside modal
        $(document).on('click', '#submit-filters', function () {
            const filterName = $('#filter-name').val().trim(); // Get filter name

            if (!filterName) {
                alert('Please enter a filter name');
                return;
            }

            // Gather all the values from the filter inputs
            const data = {
                filter_name: filterName, // Include filter name
                title: $('#title-filter').val(), // Job Title filter
                priority: $('#priority-filter').val(), // Priority filter
                services: $('#services-filter').val(), // Services filter
                products: $('#products-filter').val(), // Products filter
                is_published: $('#IsPublished-filter').val(), // Job Published filter
                is_confirmed: $('#IsConfirmed-filter').val(), // Job Confirmed filter
                job_status: $('#job-status-filter').val(), // Job Status filter
                show_on_schedule: $('#showOnSchedule-filter').val(), // Job on Schedule filter
                start_date: $('#start-date-filter').val(), // Start Date filter
                end_date: $('#end-date-filter').val(), // End Date filter
                technician: $('#technician-select').val(), // Technician filter
                customer: $('#customer-select').val(), // Customer filter
                warranty: $('#warranty-filter').val(), // Warranty filter
                flag: $('#flag-filter').val(), // Flag Customer filter
                manufacturer: $('#manufacturer-filter').val(), // Manufacturer filter
                appliances: $('#appliances-filter').val(), // Appliances filter
                customer_tags: $('#customer-tags-filter').val(), // Customer Tags filter
                type: $('#type-select').val(), // Selected type (Jobs or Products)
                product_filter_stock: $('#stock-filter').val(), // Product stock filter
                product_filter_category: $('#category-filter').val(), // Product category filter
                product_filter_manufacturer: $('#manufacturer-filter').val(), // Product manufacturer filter
                product_filter_supppliers: $('#supppliers-filter').val(), // Product suppliers filter
                product_filter_status: $('#status-filter').val(), // Product status filter
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
            };

            console.log('Data being sent to the server:', data); // Debugging log

            // Send AJAX request
            $.ajax({
                url: "{{ route('save.filters.job.parameter') }}", // Route for saving filters
                method: 'POST',
                data: data,
                success: function (response) {
                    alert(response.message); // Show success message
                    $('#filterNameModal').modal('hide'); // Hide the modal after success
                    location.reload(); // Reload the page
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>


