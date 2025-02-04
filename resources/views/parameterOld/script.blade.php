<script>
    document.getElementById('date-filter').addEventListener('change', function () {
        const filter = this.value;
        const today = new Date();
        const items = document.querySelectorAll('.schedule-item');

        items.forEach(item => {
            const startDate = new Date(item.dataset.startDate);

            let showItem = true;
            switch (filter) {
                case 'Last Month':
                    const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    showItem = startDate >= lastMonth && startDate <= today;
                    break;
                case 'Last 3 Months':
                    const threeMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 3, 1);
                    showItem = startDate >= threeMonthsAgo && startDate <= today;
                    break;
                case 'Last 6 Weeks':
                    const sixWeeksAgo = new Date(today.getTime() - (6 * 7 * 24 * 60 * 60 * 1000)); // 6 weeks in ms
                    showItem = startDate >= sixWeeksAgo && startDate <= today;
                    break;
                case 'Last Year':
                    const lastYear = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
                    showItem = startDate >= lastYear && startDate <= today;
                    break;
                default:
                    showItem = true; // Show all for "All" filter
            }

            item.style.display = showItem ? 'block' : 'none';
        });
    });

    $(document).ready(function() {
        $('#MessageModal').on('show.bs.modal', function (event) {
            // for passing job id 
            const button = $(event.relatedTarget); 
            const jobId = button.data('job-id'); 
            $('#job-id').val(jobId);
        });
        $(document).on('click', '#submitMessage' , function () {
            const message = $('#message').val();
            const jobId = $('#job-id').val();
            const errorContainer = $('#message-error'); // Target the error container if it exists

            // Clear any previous error messages
            if (errorContainer.length) {
                errorContainer.remove();
            }

            if (!message.trim()) {
                // Append error message
                $('#message').after('<div id="message-error" class="text-danger mt-2">Message cannot be empty.</div>');
                return;
            }

            $.ajax({
                url: '{{route('param.message')}}', // The route for submitting the message
                type: 'POST',
                data: {
                    message: message,
                    job_id: jobId, 
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function (response) {
                    $('#message').val(''); // Clear the textarea
                    $('.modal').modal('hide'); // Close the modal
                },
                error: function (xhr) {
                    console.log('An error occurred. Please try again.');
                }
            });
        });
        $('#VehicleModal').on('show.bs.modal', function (event) {
            // for passing tech id 
            const button = $(event.relatedTarget); 
            const techId = button.data('tech-id'); 
            $('#tech_id').val(techId);
        });
        $(document).on('click', '#vehicleMessagesubmit' , function () {
            const message = $('#vehicle-message').val();
            const techId = $('#tech_id').val();
            const errorContainer = $('#message-error'); // Target the error container if it exists

            // Clear any previous error messages
            if (errorContainer.length) {
                errorContainer.remove();
            }

            if (!message.trim()) {
                // Append error message
                $('#vehicle-message').after('<div id="message-error" class="text-danger mt-2">Message cannot be empty.</div>');
                return;
            }

            $.ajax({
                url: '{{route('param.vehicleMessage')}}', // The route for submitting the message
                type: 'POST',
                data: {
                    message: message,
                    tech_id: techId, 
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function (response) {
                    $('#message').val(''); // Clear the textarea
                    $('.modal').modal('hide'); // Close the modal
                },
                error: function (xhr) {
                    console.log('An error occurred. Please try again.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Filter products based on selected status
        $('#status-filter').change(function() {
            filterProducts();
        });

        // Search filter functionality
        $('#search-input').keyup(function() {
            filterProducts();
        });

   $('#reload-button').click(function() {
    // Show the loader
    $('#loader').removeClass('d-none');
    
    // Apply the blur effect to the body
    $('body').addClass('loading');
    
    // Clear the search input and reset the status filter
    $('#search-input').val('');
    $('#status-filter').val('');
    
    // Simulate a delay to show the loader (you can replace this with your actual filtering logic)
    setTimeout(function() {
        // Re-apply the product filter logic
        filterProducts();

        // Hide the loader and remove the blur effect
        $('#loader').addClass('d-none');
        $('body').removeClass('loading');
    }, 500);  // Adjust the delay time as necessary
});


        // Function to filter products based on status and search
        function filterProducts() {
            var status = $('#status-filter').val();  // Get selected status
            var searchQuery = $('#search-input').val().toLowerCase();  // Get search query

            $('.product-item').each(function() {
                var itemStatus = $(this).data('status');
                var itemName = $(this).data('name').toLowerCase();  // Get the product name for searching

                // Check if product matches status filter and search query
                if ((status === "" || status === itemStatus) && (itemName.indexOf(searchQuery) !== -1 || searchQuery === "")) {
                    $(this).show();  // Show product if it matches both criteria
                } else {
                    $(this).hide();  // Hide product if it doesn't match
                }
            });
        }
    });
</script>
<script>
$(document).ready(function() {
    // When an icon is clicked, trigger modal with the correct product name
    $('[data-bs-toggle="modal"]').on('click', function() {
        // Get the product name stored in the data attribute of the clicked icon
        var productName = $(this).data('product-name');
                var productName1 = productName + " purchase electrical appliances";

        // Set the product name dynamically in the modal
        $('#product-name').text(productName);
                $('#searchTrigger').data('product-name', productName1);

        $('#searchTrigger').text('Search for ' + productName);

        $('#searchTrigger').click();

        // Dynamically load the Google Custom Search Engine with the product name
        // Update the query parameter in the Google CSE configuration
        var customSearchQuery = "q=" + encodeURIComponent(productName);

        // Remove the previous CSE search results if any (reset the container)
        $('#google-cse-search-container').empty();
        
        // Load the Google Custom Search Engine with the new query
        var cseScript = document.createElement('script');
        cseScript.src = "https://cse.google.com/cse.js?cx=929815ee2d1cd4079";
        cseScript.onload = function() {
            var gcseElement = document.createElement('gcse:searchresults-only');
            gcseElement.setAttribute('query', customSearchQuery);
            document.getElementById('google-cse-search-container').appendChild(gcseElement);
        };

        // Append the script to the head to load the Google CSE
        document.head.appendChild(cseScript);
    });
});

</script>

