<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-6 align-self-center">
                <h4 class="page-title">Events</h4>
            </div>
            <div class="col-6 text-end">

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        @if (Session::has('success'))
            <div class="alert_wrap">
                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                    {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-custom">
                            <table id="zero_config" class="table table-hover table-striped text-nowrap" data-paging="true"
                                data-paging-size="7">

                                <div class="col-md-12 row" style="margin-bottom:7px;">
                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
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

                                    <div class="col-md-4">
                                        <div class="d-flex flex-column align-items-baseline">
                                            <!-- Filter by other column (example: Manufacturer) -->
                                            <label class="text-nowrap"><b>Event Type </b></label>
                                            <select id="eventType-filter" class="form-control mx-2">
                                                <option value="">All</option>
                                                <option value="full">Full</option>
                                                <option value="partial">Partial</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>


                                <thead>
                                    <tr>
                                        <th>Technician</th>
                                        <th>Event</th>
                                        <th>Event Type</th>
                                        <th>Dates</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($event as $item)
                                        <tr>
                                            <td>{{ $item->technician->name ?? null }} </td>
                                            <td>{{ $item->event_name ?? null }} </td>
                                            <td class="ucfirst">{{ $item->event_type ?? null }} </td>
                                            <td>
                                                @if ($item->event_type == 'full')
                                                    {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('m-d-Y') : null }}
                                                    to
                                                    {{ $item->end_date_time ? \Carbon\Carbon::parse($item->end_date_time)->format('m-d-Y') : null }}
                                                @else
                                                    {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('m-d-Y ') : null }}
                                                    ,
                                                    {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('h:i:a') : null }}
                                                    to
                                                    {{ $item->end_date_time ? \Carbon\Carbon::parse($item->end_date_time)->format('h:i:a') : null }}
                                                @endif
                                            </td>
                                            <td class="action footable-last-visible" style="display: table-cell;">
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-light-primary text-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#eventView"><i data-feather="eye"
                                                                class="feather-sm me-2"></i> View</a>
                                                        <a class="dropdown-item"
                                                            href="{{ url('event/delete/' . $item->id) }}"><i
                                                                data-feather="trash" class="feather-sm me-2"></i> Delete</a>

                                                    </div>
                                                </div>

                                            </td>


                                        </tr>

                                        <div class="modal fade" id="eventView" tabindex="-1"
                                            aria-labelledby="commentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content px-3">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="commentModalLabel">
                                                            {{ $item->event_name ?? null }}</h5>
                                                    </div>
                                                    @if ($item->event_description)
                                                        <p class="ps-3"> {{ $item->event_description ?? null }}</p>
                                                    @endif

                                                    <h6 class="mt-2 ps-3">Date & Location</h6>
                                                    <div class="ps-3">
                                                        @if ($item->event_type == 'full')
                                                            {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('m-d-Y') : null }}
                                                            to
                                                            {{ $item->end_date_time ? \Carbon\Carbon::parse($item->end_date_time)->format('m-d-Y') : null }}
                                                        @else
                                                            {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('m-d-Y ') : null }}
                                                            ,
                                                            {{ $item->start_date_time ? \Carbon\Carbon::parse($item->start_date_time)->format('h:i:a') : null }}
                                                            to
                                                            {{ $item->end_date_time ? \Carbon\Carbon::parse($item->end_date_time)->format('h:i:a') : null }}
                                                        @endif
                                                        @if ($item->event_location)
                                                            <br /> {{ $item->event_location ?? null }}
                                                        @endif
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>


                                        </div>
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
    <script>
        $('#zero_config').DataTable();
    </script>

    <script>
        $(document).ready(function() {

            $('#file_export').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25,
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
                    table.column(3).search('^' + Month + '-' + '\\d{2}-' + Year, true, false).draw();
                } else {
                    // If no month is selected, clear the filter
                    table.column(3).search('').draw();
                }
            });


            $('#technician-filter').on('change', function() {
                var technician = $(this).val();
                table.column(0).search(technician)
                    .draw(); // Assuming technician names are in column index 3
            });

            $('#eventType-filter').on('change', function() {
                var type = $(this).val();
                table.column(2).search(type)
                    .draw(); // Assuming technician names are in column index 3
            });




        });
    </script>
@endsection
@endsection
