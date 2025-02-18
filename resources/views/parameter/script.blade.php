<script>
    $(document).ready(function () {

        let table = $('#jobs-table').DataTable({
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf']
                }
            },
            paging: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false
        });

        // Check if the table is empty
        if (table.rows().count() === 0) {
            $('.dt-buttons').hide(); // Hide Excel/PDF buttons
            $('.dataTables_info').hide(); // Hide "Showing X of X entries"
        }
        new DataTable('#product-table', {
            layout: {
                topStart: {
                    buttons: ['excel', 'pdf']
                }
            },
            paging: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false
        });


        $('#product-filter-rows').hide();
        $('#product-table').hide();
        $('#product-table_wrapper').hide();

        $('#user-filter-rows').hide();
        $('#user-table').hide();
        $('#user-table_wrapper').hide();

        $('#start-date-div').hide();
        $('#end-date-div').hide();

        $(document).on('change','#date-type-select', function () {
            var type = $(this).val();
            if (type === 'custom') {
            $('#start-date-div').show();
            $('#end-date-div').show();
            }else{
            $('#start-date-div').hide();
            $('#end-date-div').hide();
            }
        });

        function loadSavedFilters(type) {
            $.ajax({
                url: "{{ route('get.saved.filters') }}", // Update with your actual route
                method: "GET",
                data: { type: type }, // Send selected type
                success: function (response) {
                    let savedFiltersDropdown = $("#saved-filters-dropdown");
                    savedFiltersDropdown.empty().append('<option value="">Select Parameters</option>');

                    $.each(response.filters, function (index, filter) {
                        savedFiltersDropdown.append(
                            `<option value="${filter.p_id}" data-name="${filter.p_name}" data-type="${filter.p_type}" data-filter='${JSON.stringify(filter)}'>
                                ${filter.p_name}
                            </option>`
                        );
                    });

                },
                error: function () {
                    console.error("Failed to load saved filters.");
                }
            });
        }

       // Load filters based on the default selected value
       loadSavedFilters($(".select-type-values .btn-info").data("value")); 

        // Handle button clicks
        $('.select-type-values a').on('click', function (e) {
            e.preventDefault();
            
            // Get selected type from the clicked button
            var type = $(this).data('value');

            // Update filters
            loadSavedFilters(type);

            // Toggle active button style
            $('.select-type-values a').removeClass('btn-info').addClass('btn-light-info text-info');
            $(this).addClass('btn-info').removeClass('btn-light-info text-info');

            // Show/hide relevant sections
            if (type === 'jobs') {
                $('#job-filter-rows, #jobs-table, #jobs-table_wrapper').show();
                $('#product-filter-rows, #product-table, #product-table_wrapper').hide();
            } else if (type === 'products') {
                $('#job-filter-rows, #jobs-table, #jobs-table_wrapper').hide();
                $('#product-filter-rows, #product-table, #product-table_wrapper').show();
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
                success: function(response) {
                        let table = $('#jobs-table').DataTable();
                    table.destroy(); // Destroy the existing DataTable instance
                    table.clear().draw();

                    $('#jobs-table tbody').html(response.html); // Replace tbody with new data

                    $('#jobs-table').DataTable({ // Reinitialize DataTable
                        layout: {
                            topStart: {
                                buttons: ['excel', 'pdf']
                            }
                        },
                        paging: false,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false
                    });

                    if (response.html.trim() === "") {
                        $('.dt-buttons').hide();
                        $('.dataTables_info').hide();
                        table.rows.add([]).draw();
                    } else {
                        $('.dt-buttons').show();
                        $('.dataTables_info').show();
                        $('#jobs-table tbody').html(response.html);
                        table.rows.add($('#jobs-table tbody tr')).draw();
                    }
                    
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

            // Show/Hide Monthly Dates Dropdown
            document.getElementById("monthly-from-container").style.display = (frequency === "monthly") ? "block" : "none";
        });



    });
</script>

<script>
$(document).ready(function () {
    let isUpdatingDropdown = false; // Flag to prevent infinite loop
    // Event listener for dropdown change
    $('#saved-filters-dropdown').on('change', function () {
        const filterId = $(this).val(); // Get the selected filter ID
        const filterName = $(this).find(':selected').data('name');

        if (filterId) {
            $('#frontier_loader').show(); // Show loader first
             // Remove any previously added hidden input
            $('#hidden-filter-id').remove();
            // Append a new hidden input field with the selected filter ID
            $(this).after(`<input type="hidden" id="hidden-filter-id" name="filter_p_id" value="${filterId}" data-name="${filterName}">`);

            setTimeout(() => {
                fetchFilterDetails(filterId); // Fetch details after 500ms
            }, 500);

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
                    setTimeout(() => {
                        $('#frontier_loader').hide(); // Hide loader before applying values
                        applyFilterValues(response.data); // Apply fetched values to form fields
                    }, 500);
                    
                } else {
                    $('#frontier_loader').hide(); // Hide loader if no data
                }
            },
            error: function (error) {
                console.error('Error fetching filter details:', error);
                $('#frontier_loader').hide(); // Hide loader on error
            }
        });
    }

    // Function to apply filter values to fields
    // function applyFilterValues(data) {
    //    let fieldsToUpdate = {}; // Initialize empty object

    //     // Check the type and assign the corresponding fields
    //     if (data.type === "jobs") {
    //         fieldsToUpdate = {
    //             type: '#type-select',
    //             dateType: '#date-type-select',
    //             title: '#title-filter',
    //             priority: '#priority-filter',
    //             services: '#services-filter',
    //             products: '#products-filter',
    //             is_published: '#IsPublished-filter',
    //             is_confirmed: '#IsConfirmed-filter',
    //             job_status: '#job-status-filter',
    //             show_on_schedule: '#showOnSchedule-filter',
    //             start_date: '#start-date-filter',
    //             end_date: '#end-date-filter',
    //             technician: '#technician-select',
    //             customer: '#customer-select',
    //             warranty: '#warranty-filter',
    //             flag: '#flag-filter',
    //             manufacturer: '#manufacturer-filter',
    //             appliances: '#appliances-filter',
    //             customer_tags: '#customer-tags-filter'
    //         };
    //     } else if (data.type === "products") {
    //         fieldsToUpdate = {
    //             type: '#type-select',
    //             product_filter_stock: '#stock-filter',
    //             product_filter_category: '#category-filter',
    //             product_filter_manufacturer: '#manufacturer-filter',
    //             product_filter_suppliers: '#supppliers-filter',
    //             product_filter_status: '#status-filter'
    //         };
    //     }

    //     let delay = 100; // Initial delay

    //     // Iterate over fields and set values with a delay
    //     $.each(fieldsToUpdate, function (field, selector) {
    //         if (data[field] !== null && data[field] !== undefined) {
    //             setTimeout(() => {
    //                 $(selector).val(data[field]).trigger('change');
    //             }, delay);
    //             delay += 500; // Increase delay for each field
    //         }
    //     });

    //     // Handle multi-select fields with delay
    //     ['title', 'priority', 'services', 'products', 'job_status', 'technician', 'customer_tags', 'appliances'].forEach((field) => {
    //         setTimeout(() => {
    //             if (data[field]) {
    //                 let valuesArray = JSON.parse(data[field]);
    //                 $(fieldsToUpdate[field]).val(valuesArray).trigger('change');
    //             } else {
    //                 $(fieldsToUpdate[field]).val(null).trigger('change');
    //             }
    //         }, delay);
    //         delay += 500;
    //     });

    //     // Special handling for Select2 with AJAX (Customer dropdown) with delay
    //     if (data.customer) {
    //         setTimeout(() => {
    //             let customerId = data.customer;
    //             let $customerSelect = $('#customer-select');

    //             // Check if the option already exists
    //             if ($customerSelect.find(`option[value="${customerId}"]`).length === 0) {
    //                 // Fetch the customer name from the server
    //                 $.ajax({
    //                     url: '{{ route("search-customers-param") }}',
    //                     data: { customerId: customerId },
    //                     dataType: 'json',
    //                     success: function (response) {
    //                         let customer = response; // Assuming only one result
    //                         let newOption = new Option(customer.name, customer.id, true, true);
    //                         $customerSelect.append(newOption).val(customer.id).trigger('change');
    //                     }
    //                 });
    //             } else {
    //                 $customerSelect.val(customerId).trigger('change');
    //             }
    //         }, delay);
    //     }
    // }


    // previous above
function applyFilterValues(data) {

    var filterId = data.p_id;
    var data = data.parameters;

    let fieldsToUpdate = {};
    
    if (data.type === "jobs") {
        fieldsToUpdate = {
            type: '#type-select',
            dateType: '#date-type-select',
            title: '#title-filter',
            services: '#services-filter',
            products: '#products-filter',
            is_published: '#IsPublished-filter',
            is_confirmed: '#IsConfirmed-filter',
            job_status: '#job-status-filter',
            show_on_schedule: '#showOnSchedule-filter',
            technician: '#technician-select',
            customer: '#customer-select',
            appliances: '#appliances-filter',
            warranty: '#warranty-filter',
            priority: '#priority-filter',
            customer_tags: '#customer-tags-filter',
            flag: '#flag-filter',
            frequency: '#frequency-filter',
            start_date: '#start-date-filter',
            end_date: '#end-date-filter'
        };
    } else if (data.type === "products") {
        fieldsToUpdate = {
            type: '#type-select',
            product_filter_stock: '#stock-filter',
            product_filter_category: '#category-filter',
            product_filter_manufacturer: '#manufacturer-filter',
            product_filter_suppliers: '#suppliers-filter',
            product_filter_status: '#status-filter'
        };
    }

    let delay = 500; // Initial delay

    $.each(fieldsToUpdate, function (field, selector) {
        if (data[field] !== null && data[field] !== undefined) {
            setTimeout(() => {
                let $element = $(selector);

                // Apply glow effect and blue border
                $element.addClass('highlight-field').focus();

                if ($element.hasClass('select2-hidden-accessible')) {
                    // If it's a Select2 dropdown
                    $element.select2('open');
                }

                setTimeout(() => {
                    // Set the value and trigger change
                    $element.val(data[field]).trigger('change');

                    // Close Select2 dropdown after selection
                    if ($element.hasClass('select2-hidden-accessible')) {
                        $element.select2('close');
                    }

                    // Remove glow effect after selection
                    setTimeout(() => {
                        $element.removeClass('highlight-field');
                    }, 1000); // Longer shadow removal

                }, 800); // Simulate user pause before selection

            }, delay);

            delay += Math.floor(Math.random() * 1200) + 1000; // Random delay (1000ms-2200ms)
        }
    });

    // Handle multi-select fields (JSON values)
    ['title', 'priority', 'services', 'products', 'job_status', 'technician', 'customer_tags', 'appliances'].forEach((field) => {
        setTimeout(() => {
            if (data[field]) {
                let valuesArray = JSON.parse(data[field]);
                $(fieldsToUpdate[field]).val(valuesArray).trigger('change');
            } else {
                $(fieldsToUpdate[field]).val(null).trigger('change');
            }
        }, delay);
        delay += Math.floor(Math.random() * 1200) + 1000; // Random delay (1000ms-2200ms)
    });

    // Handle Select2 with AJAX for Customer
    if (data.customer) {
        setTimeout(() => {
            let customerId = data.customer;
            let $customerSelect = $('#customer-select');

            if ($customerSelect.find(`option[value="${customerId}"]`).length === 0) {
                $.ajax({
                    url: '{{ route("search-customers-param") }}',
                    data: { customerId: customerId },
                    dataType: 'json',
                    success: function (response) {
                        let customer = response;
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




});
</script>

<script>
    $(document).ready(function () {
        let title, priority; // Ensure these are declared globally if used elsewhere

        // Open modal when clicking save filters button
        $(document).on('click', '#save-filters-modal', function () {
            let pId = $('#hidden-filter-id').val(); 
            let pName = $('#hidden-filter-id').data('name'); 

            $('#filter-id-update').val(pId); 
            $('#filter-name').val(pName); 
            $('#filterNameModal').modal('show'); // Show the modal
        });

        $(document).on('click', '#close-showmodel', function () {
            $('#filterNameModal').modal('hide'); // Hide the modal
        });

        $("#submit-filters").click(function () {
            let filterData = {};
            let type = $('#type-select').val(); 
            let name = $('#filter-name').val(); 
            let filterId = $('#filter-id-update').val();

            // Add type and filter name
            if (name !== "" && name !== null) {
                filterData['filter_name'] = name;
            }
            if (type !== "" && type !== null) {
                filterData['type'] = type;
            }
            if (filterId) {
                filterData['filter_id'] = filterId; // Send the filter ID for update
            }

            if (type == "jobs") {
                filterData['dateType'] = $('#date-type-select').val();
                filterData['title'] = $('#title-filter').val();
                filterData['priority'] = $('#priority-filter').val();
                filterData['services'] = $('#services-filter').val();
                filterData['products'] = $('#products-filter').val();
                filterData['is_published'] = $('#IsPublished-filter').val();
                filterData['is_confirmed'] = $('#IsConfirmed-filter').val();
                filterData['job_status'] = $('#job-status-filter').val();
                filterData['show_on_schedule'] = $('#showOnSchedule-filter').val();
                filterData['start_date'] = $('#start-date-filter').val();
                filterData['end_date'] = $('#end-date-filter').val();
                filterData['technician'] = $('#technician-select').val();
                filterData['customer'] = $('#customer-select').val();
                filterData['warranty'] = $('#warranty-filter').val();
                filterData['flag'] = $('#flag-filter').val();
                filterData['manufacturer'] = $('#manufacturer-job-filter').val();
                filterData['appliances'] = $('#appliances-filter').val();
                filterData['customer_tags'] = $('#customer-tags-filter').val();
            } else {
                filterData['product_filter_stock'] = $('#stock-filter').val();
                filterData['product_filter_category'] = $('#category-filter').val();
                filterData['product_filter_manufacturer'] = $('#manufacturer-filter').val();
                filterData['product_filter_suppliers'] = $('#supppliers-filter').val();
                filterData['product_filter_status'] = $('#status-filter').val();
            }

            // Send collected data to the backend
            $.ajax({
                url: "{{ route('save.filters.job.parameter') }}",
                method: "POST",
                data: filterData,
                headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
                success: function (response) {

                    $('#filterNameModal').modal('hide');

                    // Reset all filters
                    $('#date-type-select, #title-filter, #priority-filter, #services-filter, #products-filter, #IsPublished-filter, #IsConfirmed-filter, #job-status-filter, #showOnSchedule-filter, #start-date-filter, #end-date-filter, #technician-select, #warranty-filter, #flag-filter, #manufacturer-job-filter, #appliances-filter, #customer-tags-filter, #stock-filter, #category-filter, #manufacturer-filter, #supppliers-filter, #status-filter')
                        .val(null).trigger('change');

                    $('#filter-name').val('');
                   
                },
                error: function () {
                    alert("Failed to save filters.");
                }
            });

        });

       

        // Listen to input changes to trigger AJAX filtering
   $("#command-search").on("click", function () {
        let keyword = $("#command-text").val().trim(); // Get input text

        if (keyword.length > 0) {
                fetchFilteredJobs(keyword);
            $('#frontier_loader').show();
            setTimeout(function() {
                $('#frontier_loader').hide();
            }, 1000);
        }
    });

    function fetchFilteredJobs(keyword) {
        $.ajax({
            url: "{{ route('filterjobsbyCommand') }}",
            type: "POST",
            data: {
                keyword: keyword, // Send as a string
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                let table = $('#jobs-table').DataTable();
                table.destroy();
                table.clear().draw();

                $('#jobs-table tbody').html(response.html);

                $('#jobs-table').DataTable({
                    layout: {
                        topStart: { buttons: ['excel', 'pdf'] }
                    },
                    paging: false,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false
                });

                if (response.html.trim() === "") {
                    $('.dt-buttons').hide();
                    $('.dataTables_info').hide();
                    table.rows.add([]).draw();
                } else {
                    $('.dt-buttons').show();
                    $('.dataTables_info').show();
                    table.rows.add($('#jobs-table tbody tr')).draw();
                }
            },
            error: function (xhr) {
                console.error("Error fetching jobs:", xhr.responseText);
            }
        });
    }

       
    });
</script>
<script>

    $(document).ready(function () {
        let baseSuggestions = ["open jobs", "last", "previous", "next", "upcoming", "closed jobs", "jobs", "job confirmed", "job published", "job on schedule"];

        // Generate "1 days" to "100 days" suggestions dynamically
        for (let i = 1; i <= 100; i++) {
            baseSuggestions.push(`${i} days`);
        }

        $("#command-text").autocomplete({
            source: function (request, response) {
                let userInput = request.term.toLowerCase();
                let words = userInput.split(" "); // Split input into words
                let lastWord = words.pop(); // Get the last word being typed

                let filteredSuggestions = baseSuggestions.filter(item =>
                    item.toLowerCase().startsWith(lastWord)
                );

                response(filteredSuggestions);
            },
            minLength: 1,
            focus: function (event, ui) {
                return false; // Prevent automatic selection while navigating
            },
            select: function (event, ui) {
                let currentText = $("#command-text").val();
                let words = currentText.split(" "); // Get already typed words
                words.pop(); // Remove last incomplete word
                words.push(ui.item.value); // Add selected suggestion
                $("#command-text").val(words.join(" ") + " "); // Add space for next word

                return false;
            }
        });

        // Styling the autocomplete dropdown
        $(".ui-autocomplete").css({
            "background-color": "white",
            "color": "black",
            "border": "1px solid #ccc",
            "border-radius": "4px",
            "font-size": "14px",
            "max-height": "200px",
            "overflow-y": "auto",
            "padding-inline": "20px",
            "width": "auto"
        });
    });

</script>


