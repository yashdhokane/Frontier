<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table id="zero_config" class="table table-bordered text-nowrap" data-paging="true"
                                data-paging-size="7">

                                <div class="col-md-12 row" style="margin-bottom:7px;">
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column align-items-baseline">
                                            <!-- Date filtering input -->


                                            <label><b>Month:</b></label>
                                            <select id="month-filter" class="form-control mx-2">
                                                <option value="">All</option>
                                                @php
                                                    // Get the current month and year
                                                    $currentMonth = new DateTime();
                                                    // Format the current month and year
                                                    $currentMonthFormatted = $currentMonth->format('F Y');
                                                    // Output the option tag for the current month
                                                    echo "<option value=\"" .
                                                        strtolower($currentMonthFormatted) .
                                                        "\">" .
                                                        $currentMonthFormatted .
                                                        '</option>';

                                                    // Generate options for the previous 11 months
                                                    for ($i = 0; $i < 12; $i++) {
                                                        // Modify date to get previous months
                                                        $monthYear = $currentMonth->modify('-1 month')->format('F Y');
                                                        // Output the option tag for the previous months
                                                        echo "<option value=\"" .
                                                            strtolower($monthYear) .
                                                            "\">" .
                                                            $monthYear .
                                                            '
                                                </option>';
                                                    }
                                                @endphp
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex flex-column align-items-baseline">
                                            <!-- Filter by other column (example: Manufacturer) -->
                                            <label class="text-nowrap"><b>Technician </b></label>
                                            <select id="technician-filter" class="form-control mx-2">
                                                <option value="">All</option>
                                                @foreach ($technicianrole as $item)
                                                    <option value="{{ $item->name }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>


                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Technician</th>
                                        <th>Event</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $a = 0;
                                    @endphp
                                    @foreach ($event as $item)
                                        <tr>
                                            <td>{{ $a++ }}</td>
                                            <td>{{ $item->technician->name ?? null }} </td>
                                            <td>{{ $item->event_name ?? null }} </td>
                                            <td>{{ $item->event_description ?? null }} </td>
                                            <td>{{ $item->event_location ?? null }} </td>
                                            <td>{{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('m-d-Y h:i:a') : null }}
                                            </td>
                                            <td>{{ $item->end_date_time ? \Carbon\Carbon::parse($item->end_date_time)->format('m-d-Y h:i:a') : null }}
                                            </td>
                                            <td><a href="{{ url('event/delete/' . $item->id) }}"><i
                                                        class="fa fa-trash text-danger"></i></a></td>


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
@section('script')
    {{-- <script src="{{ asset('public/admin/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}

    <script>
        $('#zero_config').DataTable();
    </script>

    <script>
        $(document).ready(function() {

            $('#file_export').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            });

            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
                'btn btn-cyan text-white me-1'
            );

            // Initialize DataTable
            var table = $('#zero_config').DataTable();

            // Month filtering
            $('#month-filter').on('change', function() {
                var selectedMonth = $(this).val();
                if (selectedMonth) {
                    var Month = moment(selectedMonth, 'MMMM YYYY').format('MM'); // Extract the month
                    var Year = moment(selectedMonth, 'MMMM YYYY').format('YYYY'); // Extract the year
                    // Perform filtering on the table to include all dates within the selected month and year
                    table.column(5).search('^' + Month + '-' + '\\d{2}-' + Year, true, false).draw();
                } else {
                    // If no month is selected, clear the filter
                    table.column(5).search('').draw();
                }
            });


            $('#technician-filter').on('change', function() {
                var technician = $(this).val();
                table.column(1).search(technician)
                    .draw(); // Assuming technician names are in column index 3
            });




        });
    </script>
@endsection
@endsection