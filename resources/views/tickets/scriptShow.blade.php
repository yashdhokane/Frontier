@section('script')
    <!-- This page JavaScript -->
    <!-- --------------------------------------------------------------- -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/tinymce/tinymce.min.js"></script>
    <!--c3 charts -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/d3-3.5.6.js"></script>


    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/c3-0.4.9.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('approve_pending_job');

            checkbox.addEventListener('change', function() {
                var status = this.checked ? 'closed' : 'open';
                // Assuming you have an input field for status in your form
                document.getElementById('status').value = status;
            });
        });
    </script>

    <script>
        function confirmAndCheck() {
            var checkbox = document.getElementById('approve_pending_job');
            if (confirm('Do you confirm that the job is complete?')) {
                checkbox.checked = true;
                return true; // Allow the form to be submitted
            } else {
                return false; // Prevent the form from being submitted
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            // Function to check URL for mode=edit
            $('#save-close-btn').hide();
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('mode') === 'edit') {
                $('#save-close-btn').show(); // Show Save & Close button
            }

            var buttonAction = ''; // Variable to store button action

            // Detect which button was clicked
            $('button[type="submit"]').on('click', function() {
                buttonAction = $(this).data('action'); // Store the action of the clicked button
            });

            $('#editJobForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Collect form data
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'), // Form action URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Required to send FormData correctly
                    contentType: false, // Required to send FormData correctly

                    success: function(response) {
                        var job = response.job;
                        // Update job title
                        $('.title_update').empty().append(job.job_title);

                        // Update full date
                        var date = response.startDateTime;
                        $('.fulldate_update').empty().append('<i class="fa fa-calendar"></i> ' +
                            date);

                        // Update description_update
                        $('.description_update').empty().append(job.description);

                        // Update description_update
                        var duration = job.jobassignname.duration / 60;
                        $('.duration_update').empty().append(duration + ' Hours');

                        // Update priority_update
                        $('.priority_update').empty().append(job.priority);

                        // Update date_update
                        $('.date_update').empty().append(response.newDate);

                        // Update from_update
                        $('.from_update').empty().append(response.fromDate);

                        // Update to_update
                        $('.to_update').empty().append(response.toDate);

                        // Update warranty_update
                        $('.warranty_update').empty().append(job.warranty_type);

                        // Update warranty_ticket_update
                        $('.warranty_ticket_update').empty().append(job.warranty_ticket);

                        // Update appliance_update
                        $('.appliance_update').empty().append(job.job_appliances.appliances
                            .appliance.appliance_name);

                        // Update manufacturer_update
                        $('.manufacturer_update').empty().append(job.job_appliances.appliances
                            .manufacturer.manufacturer_name);

                        // Update model_update
                        $('.model_update').empty().append(job.job_appliances.appliances
                            .model_number);

                        // Update serial_update
                        $('.serial_update').empty().append(job.job_appliances.appliances
                            .serial_number);

                        // Update enr_date
                        $('.enr_date').empty().append(response.enr_date);

                         $('.update-job').hide();

                        // Check if "Save & Close" button was clicked
                        if (buttonAction === 'save-close') {
                            // Hide the .update-job element
                            $('.update-job').hide();
                            // Close the tab
                            window.close();
                        }

                        // Clear buttonAction after form submission
                        buttonAction = '';

                    },
                    error: function(xhr) {
                        // Handle error response (e.g., display error messages)
                        alert('Error updating job. Please try again.');
                    },
                    complete: function() {
                        // Re-enable the submit button
                        $('button[type="submit"]').prop('disabled', false);
                    }
                });
            });





            var urlParams = new URLSearchParams(window.location.search);
            var mode = urlParams.get('mode');

            if (mode === 'edit') {
                $('.update-job').show(); // Show the element
            } else {
                $('.update-job').hide(); // Hide the element (optional)
            }
            $(document).on('click', '.edit-job', function() {
                $('.update-job').toggle();
            });
            $(document).on('click', '#add_new_appl', function() {
                $('#show_new_appl').toggle();
            });
            $(document).on('click', '#add_appliance', function() {
                $('.appliancefield').show();
                $('#add_appliance').hide();
            });
            $(document).on('click', '#add_manufaturer', function() {
                $('.manufaturerfield').show();
                $('#add_manufaturer').hide();
            });

            $(document).on('click', '#addAppl', function() {
                var appliance = $('#new_appliance').val();
                $.ajax({
                    url: "{{ url('add/new/appliance') }}",
                    data: {
                        appliance: appliance,
                    },
                    method: 'get',
                    success: function(data) {
                        // Clear existing options
                        $('#appliances').empty();
                        // Check if data is not empty and has appliances array
                        if (data && data && data.length > 0) {
                            // Append new options
                            $.each(data, function(index, value) {
                                $('#appliances').append($('<option value="' + value
                                    .appliance_type_id + '">' + value
                                    .appliance_name + '</option>'));
                            });
                        }
                        $('.appliancefield').hide();
                        $('#add_appliance').show();
                        $('#resp_text').text('Appliance added successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            $(document).on('click', '#addManu', function() {
                var manufacturer = $('#new_manufacturer').val();
                $.ajax({
                    url: "{{ url('add/new/manufacturer') }}",
                    data: {
                        manufacturer: manufacturer,
                    },
                    method: 'get',
                    success: function(data) {
                        // Clear existing options
                        $('#manufacturer').empty();
                        // Check if data is not empty and has appliances array
                        if (data && data && data.length > 0) {
                            // Append new options
                            $.each(data, function(index, value) {
                                $('#manufacturer').append($('<option value="' + value
                                    .id + '">' + value.manufacturer_name +
                                    '</option>'));
                            });
                        }
                        $('.manufaturerfield').hide();
                        $('#add_manufaturer').show();
                        $('#resp_texts').text('Manufacturer added successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            $(document).on('change', '#check_job_type', function() {
                if ($(this).val() == 'in_warranty') {
                    $('#warranty_ticket').show();
                } else {
                    $('#warranty_ticket').hide();
                }
            });

            $(document).on('input', '#check_serial_number', function() {
                var serialNumber = $(this).val();
                var baseUrl = "{{ url('/') }}";
                if (serialNumber.length > 0) {
                    $.ajax({
                        url: '{{ route('check.serial.number') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            serial_number: serialNumber
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                var detailsHtml = '';
                                response.data.forEach(function(detail) {
                                    // Check if detail.user exists and is an array
                                    if (Array.isArray(detail.user) && detail.user
                                        .length > 0) {
                                        // Iterate over the users if there are multiple
                                        detail.user.forEach(function(user) {
                                            detailsHtml += `
                                                <div class="alert alert-success">
                                                    <strong>Serial Number matches with existing customer <a href="${baseUrl}/customers/show/${user.id}">${user.name}</a></strong>
                                                    <!-- Add more fields as necessary -->
                                                </div>
                                            `;
                                        });
                                    } else {
                                        // Handle case where detail.user is not an array or is empty
                                        detailsHtml += `
                                                <div class="alert alert-warning">
                                                    No user information available for appliance with serial number: ${detail.serial_number}
                                                </div>
                                            `;
                                    }
                                });
                                $('#serial_number_detail').html(detailsHtml);
                            } else {
                                $('#serial_number_detail').html(`
                                    <div class="alert alert-danger">
                                        ${response.message}
                                    </div>
                                `);
                            }
                        }
                    });
                } else {
                    $('#serial_number_detail').html('');
                }
            });

            $('#open_job_settings').hide();

            $(document).on('click', '#job_set_lnk', function(e) {
                $('#open_job_settings').toggle('fade');
            });

            $('#manufacturer_ids').select2();


            $('.addnotes').click(function() {
                $('.shownotes').toggle('fade', function() {
                    if ($(this).is(':visible')) { // Check if the element is visible after toggle
                        $('html, body').animate({
                            scrollTop: $(this).offset()
                                .top // Scroll to the top position of the element
                        }, 'fast');
                    }
                });
            });
            $('.addCustomerTags').click(function() {
                $('.showCustomerTags').toggle('fade');

            });
            $('.addJobTags').click(function() {
                $('.showJobTags').toggle('fade');

            });
            $('.addAttachment').click(function() {
                $('.showAttachment').toggle('fade');

            });
            $('.addSource').click(function() {
                $('.showSource').toggle('fade');

            });
        });
    </script>

    <script>
        $(function() {
            tinymce.init({
                selector: 'textarea#mymce'
            });
            $('#submitBtn').click(function() {
                // Check if the TinyMCE textarea is empty
                if (tinymce.activeEditor.getContent().trim() === '') {
                    // If textarea is empty, prevent form submission
                    alert('Please enter a Job note.');
                    return false;
                }
            });
            // ==============================================================
            // Our Visitor
            // ==============================================================

            var chart = c3.generate({
                bindto: '#visitor',
                data: {
                    columns: [
                        ['Open', 4],
                        ['Closed', 2],
                        ['In progress', 2],
                        ['Other', 0],
                    ],

                    type: 'donut',
                    tooltip: {
                        show: true,
                    },
                },
                donut: {
                    label: {
                        show: false,
                    },
                    title: 'Job',
                    width: 35,
                },

                legend: {
                    hide: true,
                    //or hide: 'data1'
                    //or hide: ['data1', 'data2']
                },
                color: {
                    pattern: ['#40c4ff', '#2961ff', '#ff821c', '#7e74fb'],
                },
            });
        });
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicianlocation->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicianlocation->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=13';

        document.getElementById('map').src = mapUrl;
        // var streetViewUrl =
        //   'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
        //   latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        //  document.getElementById('map').src = streetViewUrl;
    </script>
    <script>
        setTimeout(function() {
            $('#successMessage').fadeOut('fast');
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicians->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicians->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        // var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
        //     latitude + ',' + longitude + '&zoom=18';
        var streetViewUrl =
            'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
            latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        document.getElementById('map238').src = streetViewUrl;

        // document.getElementById('map238').src = mapUrl;
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {!! isset($technicians->addresscustomer->latitude) ? $technicians->addresscustomer->latitude : 'null' !!}; // Example latitude
        var longitude = {!! isset($technicians->addresscustomer->longitude) ? $technicians->addresscustomer->longitude : 'null' !!}; // Example longitude


        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=18';
        //  var streetViewUrl =
        //    'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
        //    latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        //document.getElementById('map').src = streetViewUrl;
        document.getElementById('map').src = mapUrl;

        // document.getElementById('map238').src = mapUrl;
    </script>
@endsection
