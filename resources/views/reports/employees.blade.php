@extends('home')

@section('content')

    
    <div class="page-wrapper" style="display:inline">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row" style="display: flex;">
                <div class="col-5">
                    <h4 class="page-title">Employee Report</h4>
                </div>

                <div class="col-7 align-self-center">

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
        <div class="container-fluid">
            <div class="row">




                <div class="container">

                    <div class="row">




                        <div class="col-md-12">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h5 class="card-title uppercase">Jobs created and dispatch technicians</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive" id="employeetablereport">
                                                <table
                                                    class="table table-hover table-striped datatable_new2 mb-0 v-middle w-100"
                                                    id="file_export11">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Employee</th>
                                                            <th class="border-bottom border-top">Job Created</th>
                                                            <th class="border-bottom border-top">Job updated</th>
                                                            <th class="border-bottom border-top">Job Closed</th>
                                                            <th class="border-bottom border-top">Job Revenue</th>
                                                            <th class="border-bottom border-top">Average size Job</th>
                                                            <th class="border-bottom border-top">Activity</th>
                                                            <th class="border-bottom border-top">Messages</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">${{ number_format($alltotalGross, 2) }}
                                                            </th>
                                                            @if ($job > 0)
                                                                <th class="border-bottom border-top">
                                                                    ${{ number_format(intval($alltotalGross / $job), 2) }}</th>
                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                            <th class="border-bottom border-top">{{ $totalActivity }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $totalChats }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                            <tr>
                                                                <td>{{ $employee->name ?? null }}</td>
                                                                <td>{{ $jobCountsByEmployee[$employee->id] }}</td>
                                                                <td>{{ $jobCountsUpdatedBy[$employee->id] }}</td>
                                                                <td>{{ $jobCountsClosedBy[$employee->id] }}</td>
                                                                <td>${{ number_format($grossTotalByEmployee[$employee->id], 2) }}</td>
                                                                @if ($jobCountsByEmployee[$employee->id] > 0)
                                                                   <td>${{ number_format(intval($grossTotalByEmployee[$employee->id] / $jobCountsByEmployee[$employee->id]), 2) }}</td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                                <td>{{ $activity[$employee->id] }}</td>
                                                                <td>{{ $chats[$employee->id] }}</td>
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



    <!-- jQuery -->
@section('script')
    <!-- jQuery -->
    <script>
        $(document).ready(function() {
            // When the dataFilter selection changes
            $('#dataFilter').change(function() {
                var selectedValue = $(this).val(); // Get the selected value

                // Hide all filter fields initially
                $('#dateRangePicker, #toDatePicker, #monthPicker, #yearPicker').hide();
                // Reset filter values
                $('#fromDate').val('');
                $('#toDate').val('');
                $('#selectMonth').val('');
                $('#selectYear').val('');

                // Show the relevant field based on selection
                if (selectedValue === 'date_range') {
                    $('#dateRangePicker').show();
                    $('#toDatePicker').show();
                } else if (selectedValue === 'month') {
                    $('#monthPicker').show();
                } else if (selectedValue === 'year') {
                    $('#yearPicker').show();
                }
            });

            // When the submit button is clicked (instead of using form submit)
            $('#submitButton').click(function(e) {
                e.preventDefault(); // Prevent any default action (no form submission)

                // Get filter values
                var dataFilter = $('#dataFilter').val();
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();
                var selectMonth = $('#selectMonth').val();
                var selectYear = $('#selectYear').val();

                // Make AJAX request to fetch updated employee report
                $.ajax({
                    url: "{{ route('employeereport.index') }}", // Route to your report function
                    type: "GET",
                    data: {
                        dataFilter: dataFilter,
                        fromDate: fromDate,
                        toDate: toDate,
                        selectMonth: selectMonth,
                        selectYear: selectYear
                    },
                    success: function(response) {
                        // Empty the div before appending new data
                        // $('#employeetablereport').empty(); // Clear the content of the div

                        // Append the new table structure with data to the div
                        $('#employeetablereport').html(response);

                        // Destroy the existing DataTable instance if it exists
                        // if ($.fn.dataTable.isDataTable('#file_export11')) {
                        //     $('#file_export11').DataTable().clear().destroy();
                        // }

                        // Reinitialize the DataTable with new content
                        $('#file_export11').DataTable({
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "autoWidth": false
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching report:", error);
                    }
                });
            });

            // Trigger the change event to show the default selected option's corresponding filter
            $('#dataFilter').trigger('change');
        });
    </script>


    <script>
        $(document).ready(function() {
            new DataTable('.datatable_new2', {
                layout: {
                    topStart: {
                        buttons: ['excel', 'pdf']
                    }
                }
            });
        });
    </script>

@stop
@stop
