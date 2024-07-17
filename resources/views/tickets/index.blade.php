<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')

    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <style>
        .row-no-margin {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
    </style>
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row row-no-margin mt-4">
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-primary text-center">
                                        <h1 class="fw-light text-primary">{{ $totalCalls }}</h1>
                                        <h6 class="text-primary">Total Calls</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-warning text-center">
                                        <h1 class="fw-light text-warning">{{ $inProgress }}</h1>
                                        <h6 class="text-warning">In Progress</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-success text-center">
                                        <h1 class="fw-light text-success">{{ $opened }}</h1>
                                        <h6 class="text-success">Opened</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6 col-lg-3 col-xlg-3">
                                <div class="card card-hover">
                                    <div class="p-2 rounded bg-light-danger text-center">
                                        <h1 class="fw-light text-danger">{{ $complete }}</h1>
                                        <h6 class="text-danger">Closed</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create New Ticket</a> --}}
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive table-custom">
                            <table id="zero_config" class="table table-hover table-striped table-bordered text-nowrap"
                                data-paging="true" data-paging-size="7">
                                <div class="d-flex flex-wrap">
                                    <div class="col-md-12 row" style="margin-bottom:7px;">
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column align-items-baseline">
                                                <!-- Date filtering input -->


                                                <label><b>Month:</b></label>
                                                <select id="month-filter" class="form-control mx-2">
                                                    <option value="">All</option>
                                                    @php
                                                        $time_interval = Session::get('time_interval', 0);
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
                                                            $monthYear = $currentMonth
                                                                ->modify('-1 month')
                                                                ->format('F Y');
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
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column  align-items-baseline">
                                                <!-- Filter by other column (example: Manufacturer) -->
                                                <label class="text-nowrap"><b>Manufacturer:</b></label>
                                                <select id="manufacturer-filter" class="form-control mx-2">
                                                    <option value="">All</option>
                                                    @foreach ($manufacturer as $item)
                                                        <option value="{{ $item->manufacturer_name }}">
                                                            {{ $item->manufacturer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column  align-items-baseline">
                                                <!-- Filter by status -->
                                                <label class="text-nowrap"><b>Status:</b></label>
                                                <select id="status-filter" class="form-control mx-2">
                                                    <option value="">All</option>
                                                    <option value="open">Open</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="resolved">Resolved</option>
                                                    <option value="closed">Closed</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                    <thead>
                                        <tr>
                                            <th>Job ID</th>
                                            <th>Job Details</th>
                                            <th>Customer</th>
                                            <th>Technician</th>
                                            <th>Date & Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('tickets.show', $ticket->id) }}"
                                                        class="fw-bold link"><span
                                                            class="mb-1 badge bg-primary">{{ $ticket->id }}</span></a>
                                                </td>
                                                <td>
                                                    <div class="text-wrap2">
                                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                                            class="font-medium link">
                                                            {{ $ticket->job_title ?? null }}</a>
                                                        <span
                                                            class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                                                    </div>
                                                    <div style="font-size:12px;">
                                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                            {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                                        @endif
                                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                            {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                                        @endif
                                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
                                                            {{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
                                                        @endif
                                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->serial_number)
                                                            {{ $ticket->JobAppliances->Appliances->serial_number ?? null }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($ticket->user)
                                                        {{ $ticket->user->name }}
                                                    @else
                                                        Unassigned
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ticket->technician)
                                                        {{ $ticket->technician->name }}
                                                    @else
                                                        Unassigned
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                                                        <div class="font-medium link">
                                                            {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                                                        </div>
                                                    @else
                                                        <div></div>
                                                    @endif
                                                    <div style="font-size:12px;">
                                                        {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                        to
                                                        {{ $modifyDateTime($ticket->jobassignname->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span><a class="btn btn-success"
                                                            href="{{ route('tickets.show', $ticket->id) }}">View</a></span>
                                                    <span style="display:none;"><a class="btn btn-primary"
                                                            href="{{ route('tickets.edit', $ticket->id) }}">Edit</a></span>
                                                    <span style="display:none;">
                                                        <form method="POST"
                                                            action="{{ route('tickets.destroy', $ticket->id) }}"
                                                            style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </span>
                                                </td>
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
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#zero_config')) {
                $('#zero_config').DataTable().destroy();
            }

            $('#zero_config').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25,
            });

            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
                'btn btn-cyan text-white me-1'
            );

            // Initialize DataTable
            var table = $('#zero_config').DataTable();

            // Month filtering
            $('#month-filter').on('change', function() {
                var selectedMonth = $(this).val();
                console.log("Selected month:", selectedMonth);

                if (selectedMonth) {
                    var Month = moment(selectedMonth, 'MMMM YYYY').format('MM');
                    var Year = moment(selectedMonth, 'MMMM YYYY').format('YYYY');
                    console.log("Formatted Month:", Month);
                    console.log("Formatted Year:", Year);

                    if (Month && Year) {
                        // Adjust regex to match month and year only
                        var dateRegex = '\\d{2}-' + Month + '-' + Year;
                        console.log("Date Regex:", dateRegex);

                        table.column(4).search(dateRegex, true, false).draw();
                    } else {
                        console.error("Invalid Month or Year format.");
                    }
                } else {
                    table.column(4).search('').draw();
                }
            });

            // Manufacturer filtering
            $('#manufacturer-filter').on('change', function() {
                var manufacturer = $(this).val();
                table.column(1).search(manufacturer).draw();
            });
            $('#technician-filter').on('change', function() {
                var technician = $(this).val();
                table.column(3).search(technician)
                    .draw(); // Assuming technician names are in column index 3
            });


            $('#status-filter').on('change', function() {
                var status = $(this).val();
                table.columns(1).search(status).draw();
            });


        });
    </script>
@endsection
@endsection
