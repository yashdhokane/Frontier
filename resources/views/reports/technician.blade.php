@extends('home')

@section('content')
<style>
.dt-search {
    margin-left: 436px !important;
}
</style>
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Technician Report</h4>
                </div>

                <div class="col-6 align-self-center">

                    <div class="row">
                        <div class="col-12">
                            <!-- Dropdown Selection -->
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-2">
                                    <select id="dataFilter" class="form-control">
                                        <option value="">Select All</option>
                                        <option value="date_range">Select Dates</option>
                                        <option value="month">Select Month</option>
                                        <option value="year">Select Year</option>
                                    </select>
                                </div>

                                <!-- Date Range Picker -->
                                <div class="col-md-3 mb-2" id="dateRangePicker" style="display: none;">
                                    <input type="date" id="fromDate" class="form-control" placeholder="From Date">
                                </div>
                                <div class="col-md-3 mb-2" id="toDatePicker" style="display: none;">
                                    <input type="date" id="toDate" class="form-control" placeholder="To Date">
                                </div>

                                <!-- Month Dropdown -->
                                <div class="col-md-3 mb-2" id="monthPicker" style="display: none;">
                                    <select id="selectMonth" class="form-control">
                                        <option value="">Select Month</option>
                                        @foreach (range(1, 12) as $month)
                                            <option value="{{ $month }}">
                                                {{ date('F', mktime(0, 0, 0, $month, 1)) }} 2024
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Year Dropdown -->
                                <div class="col-md-3 mb-2" id="yearPicker" style="display: none;">
                                    <select id="selectYear" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach (range(2023, now()->year) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 text-right mb-2">
                                    <button id="submitButton" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <style>
            /* Ensure the table container has horizontal overflow enabled */
            .dataTables_wrapper {
                overflow-x: auto !important;
            }

            /* Optional: Set a max width for your table container */
            .dataTables_wrapper table {
                width: 100% !important;
            }
        </style>
        <div class="container-fluid">
            <div class="row">



                <div class="container">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h5 class="card-title uppercase">Jobs completed by technician</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="tech1">
                                                <table
                                                    class="table table-hover table-striped customize-table mb-0 v-middle overflow-auto"
                                                    id="technician">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Technician Name</th>
                                                            <th class="border-bottom border-top">Job Revenue</th>
                                                            <th class="border-bottom border-top">Job Count</th>
                                                            <th class="border-bottom border-top">Avg. Job Size</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">${{ number_format($totalJobRevenue, 2) }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $totalJobCount }}</th>
                                                            @if ($totalJobCount > 0)
 <td>${{ number_format(intval($totalJobRevenue / $totalJobCount), 2) }}</td>                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($technicians as $technician)
                                                            <tr>
                                                                <td>{{ $technician->technician_name }}</td>
                <td>${{ number_format($technician->total_gross, 2) }}</td>
                                                                <td>{{ $technician->job_count }}</td>
                                                                @if ($technician->job_count > 0)
                                                                                       <td>${{ number_format(intval($technician->total_gross / $technician->job_count), 2) }}</td>

                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h5 class="card-title uppercase">Time tracking (completed jobs)</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="tech2">
                                       <table class="table table-hover table-striped customize-table mb-0 v-middle overflow-auto" id="technician1">
    <thead class="table-dark">
        <tr>
            <th class="border-bottom border-top">Technician Name</th>
            <th class="border-bottom border-top">Total On Job Hrs</th>
            <th class="border-bottom border-top">Total Travel Hrs</th>
            <th class="border-bottom border-top">Total Hrs Per Job</th>
        </tr>
        <tr class="table-success border-success">
            <th class="border-bottom border-top">Total</th>
            <th class="border-bottom border-top">{{ number_format($totalJobHours / 60, 2) }}</th>
            <th class="border-bottom border-top">{{ number_format($totalDrivingHours / 60, 2) }}</th>
            @if ($totalDrivingHours > 0)
                <td>{{ number_format(intval($totalJobHours / $totalDrivingHours), 2) }}</td>
            @else
                <td>N/A</td>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($technicians as $technician)
            <tr>
                <td>{{ $technician->technician_name }}</td>
                <td>{{ number_format($technician->total_job_hours / 60, 2) }}</td>
                <td>{{ number_format($technician->total_driving_hours / 60, 2) }}</td>
                @if ($technician->total_driving_hours > 0)
                    <td>{{ number_format(intval($technician->total_job_hours / $technician->total_driving_hours), 2) }}</td>
                @else
                    <td>N/A</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>





            </div>
        </div>
    </div>
    </div>
@section('script')
    <script>
        $(document).ready(function() {

            var tableIds = [
                'technician', 'technician1'
            ];

            // Function to initialize DataTables
            function initializeDataTable(tableId) {
                if ($.fn.DataTable.isDataTable('#' + tableId)) {
                    $('#' + tableId).DataTable().destroy();
                }

                $('#' + tableId).DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "pageLength": 25
                });
            }
            // Initially hide all pickers
            $('#dateRangePicker, #toDatePicker, #monthPicker, #yearPicker').hide();

            // Listen for changes on the dropdown
            $('#dataFilter').change(function() {
                var selectedValue = $(this).val();

                // Hide all picker fields first
                $('#dateRangePicker, #toDatePicker, #monthPicker, #yearPicker').hide();
                $('#fromDate').val('');
                $('#toDate').val('');
                $('#selectMonth').val('');
                $('#selectYear').val('');

                // Show the appropriate picker based on selection
                if (selectedValue === 'date_range') {
                    $('#dateRangePicker, #toDatePicker').show(); // Show date range pickers
                } else if (selectedValue === 'month') {
                    $('#monthPicker').show(); // Show month picker
                } else if (selectedValue === 'year') {
                    $('#yearPicker').show(); // Show year picker
                }
            });

            // Handle the form submission to fetch data
            $('#submitButton').click(function() {
                var selectedValue = $('#dataFilter').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();
                var selectedMonth = $('#selectMonth').val();
                var selectedYear = $('#selectYear').val();

                // Prepare the data to send in the request
                var requestData = {
                    filter_type: selectedValue,
                    from_date: fromDate,
                    to_date: toDate,
                    month: selectedMonth,
                    year: selectedYear
                };

                // Make the AJAX request to fetch the filtered data
                $.ajax({
                    url: "{{ route('technician.report.sub.ajax') }}",
                    type: 'GET',
                    data: requestData,
                    success: function(response) {

                        // Clear the previous table contents inside the divs
                        $('#tech1').empty();
                        $('#tech2').empty();

                        // Append the new HTML content (tables) into the divs
                        $('#tech1').html(response
                            .technician_table_html); // Insert the table HTML for tech1
                        $('#tech2').html(response
                            .technician1_table_html); // Insert the table HTML for tech2

                        $.each(tableIds, function(index, tableId) {
                            initializeDataTable(tableId); // Reinitialize DataTable
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });

            });
        });
    </script>


    <script>
   $(document).ready(function() {
    // Initialize DataTables for all IDs
    var tableIds = [
        'technician', 'technician1'
    ];

    $.each(tableIds, function(index, tableId) {
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            $('#' + tableId).DataTable().destroy();
        }

        $('#' + tableId).DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
            dom: 'Bfrtip', // This adds the button container to show export buttons
            buttons: [
                'excel', // Excel export button
                'pdf' // PDF export button
            ]
            // Add more options as needed
        });
    });
});

    </script>
@stop


@stop
