@extends('home')

@section('content')
    <style>
        #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
            padding-top: 35px;
        }

        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 130px;
        }
    </style>

    <div id="main-wrapper">
        <div class="page-wrapper">
            <!-- -------------------------------------------------------------- -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Payments</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Payments</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex no-block justify-content-end align-items-center">
                            <div class="me-2">
                                <div class="lastmonth"></div>
                            </div>
                            <div class="">
                                <small>LAST MONTH</small>
                                <h4 class="text-info mb-0 font-medium">$58,256</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <!-- -------------------------------------------------------------- -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
            <!-- -------------------------------------------------------------- -->
            <!-- Container fluid  -->
            <!-- -------------------------------------------------------------- -->
            <div class="container-fluid">
                <!-- -------------------------------------------------------------- -->
                <!-- Start Page Content -->
                <!-- -------------------------------------------------------------- -->
                <!-- basic table -->
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">


                                <div class="row">
                                    <div class="col-sm-3">
                                        <!-- Date filtering input -->
                                        <label><strong>Month & Year</strong></label>
                                        <select id="month-filter" class="form-control">
                                            <option value="">All</option>
                                            @php
                                                // Get the current month and year
                                                $currentMonth = new DateTime();
                                                // Format the current month and year
                                                $currentMonthFormatted = $currentMonth->format('F Y');
                                                // Output the option tag for the current month
                                                echo "<option value=\"" . strtolower($currentMonthFormatted) . "\">" . $currentMonthFormatted . '</option>';

                                                // Generate options for the previous 11 months
                                                for ($i = 0; $i < 12; $i++) {
                                                    // Modify date to get previous months
                                                    $monthYear = $currentMonth->modify('-1 month')->format('F Y');
                                                    // Output the option tag for the previous months
                                                    echo "<option value=\"" . strtolower($monthYear) . "\">" . $monthYear . '</option>';
                                                }
                                            @endphp
                                        </select>
                                    </div>

                                    <div class="col-sm-3">
                                        <!-- Filter by other column (example: Manufacturer) -->
                                        <label class="text-nowrap"><strong>Manufacturers</strong></label>
                                        <select id="manufacturer-filter" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($manufacturer as $item)
                                                <option value="{{ $item->manufacturer_name }}">
                                                    {{ $item->manufacturer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-3 mb-2">
                                        <!-- Filter by other column (example: Technicians) -->
                                        <label class="text-nowrap"><strong>Technicians</strong></label>
                                        <select id="technician-filter" class="form-control">
                                            <option value="">All</option>
                                            @foreach ($tech as $item)
                                                <option value="{{ $item->name }}">
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-3 mb-2">
                                        <!-- Filter by other column (example: Payment Status) -->
                                        <label class="text-nowrap"><strong>Status</strong></label>
                                        <select id="payment-status-filter" class="form-control">
                                            <option value="">All</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                            <option value="refund">Refund</option>
                                            <option value="cancel">Cancel</option>
                                        </select>
                                    </div>



                                </div>

                                <div class="table-responsive">

                                    <table id="file_export" class="table table-bordered">
                                        <thead>
                                            <!-- start row -->
                                            <tr>
                                                <th>ID</th>
                                                <th>Manufacturer</th>
                                                <th>Job Details</th>
                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Inv. Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            <!-- end row -->
                                        </thead>
                                        <tbody>
                                            <!-- start row -->
                                            @foreach ($payment as $index => $item)
                                                <tr>
                                                    <td><a
                                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number }}</a>
                                                    </td>
                                                    <td>{{ $item->jobDetails->manufacturername->manufacturer_name ?? null}}</td>
                                                    <td>{{ $item->JobModel->job_code }}<br>{{ $item->JobModel->job_title }}</td>
                                                    <td>{{ $item->user->name ?? null}}</td>
                                                    <td>{{ $item->JobModel->technician->name }}</td>
                                                    <td>{{ $convertDateToTimezone($item->issue_date) }}</td>
                                                    <td>${{ $item->total }}</td>
                                                    <td style="text-transform: capitalize;">{{ $item->status }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    href="{{ url('invoice-detail/' . $item->id) }}"><i
                                                                        data-feather="eye" class="feather-sm me-2"></i>
                                                                    View</a>

                                                                <!-- Comments option -->
                                                                <a class="dropdown-item add-comment"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#commentModal{{ $index }}">
                                                                    <i data-feather="message-circle"
                                                                        class="feather-sm me-2"></i> Comments
                                                                </a>
                                                                @if ($item->status != 'paid')
                                                                    <a class="dropdown-item"
                                                                        href="{{ url('update/payment/' . $item->id) }}"><i
                                                                            data-feather="edit-2"
                                                                            class="feather-sm me-2"></i>
                                                                        Mark Complete</a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <!-- Modal for adding comment -->
                                                <div class="modal fade" id="commentModal{{ $index }}" tabindex="-1"
                                                    aria-labelledby="commentModalLabel{{ $index }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="commentModalLabel{{ $index }}">Add Comment
                                                                </h5>
                                                                {{-- <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button> --}}
                                                            </div>
                                                            <!-- Comment form -->
                                                            <form action="{{ url('store/comment/' . $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="comment">Comment:</label>
                                                                        <textarea class="form-control" id="comment" name="payment_note" rows="3">
                                                                            
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">

                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <!-- start row -->
                                            <tr>
                                                <th>ID</th>
                                                <th>Manufacturer</th>
                                                <th>Job Details</th>
                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Inv. Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            <!-- end row -->
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- -------------------------------------------------------------- -->
                <!-- End PAge Content -->
                <!-- -------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------- -->
                <!-- Right sidebar -->
                <!-- -------------------------------------------------------------- -->
                <!-- .right-sidebar -->
                <!-- -------------------------------------------------------------- -->
                <!-- End Right sidebar -->
                <!-- -------------------------------------------------------------- -->
            </div>

        </div>
    </div>



    <!-- start - This is for export functionality only -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>

    <script>
        //=============================================//
        //    File export                              //
        //=============================================//
        $(document).ready(function() {

            $('#file_export').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            });

            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass(
                'btn btn-cyan text-white me-1'
            );

            // Initialize DataTable
            var table = $('#file_export').DataTable();

            // Month filtering
            $('#month-filter').on('change', function() {
                var selectedMonth = $(this).val();
                if (selectedMonth) {
                    var Month = moment(selectedMonth, 'MMMM YYYY').startOf('month').format(
                        'MM'); // First day of the selected month
                    var Year = moment(selectedMonth, 'MMMM YYYY').startOf('month').format(
                        'YYYY'); //  selected year

                    // Perform filtering on the table to include all dates within the range between start date and end date
                    table.column(5).search('^' + Month + '-' + '\\d{2}-' + Year + '$', true,
                        false).draw();
                } else {
                    // If no month is selected, clear the filter
                    table.column(5).search('').draw();
                }
            });



            // Manufacturer filtering
            $('#manufacturer-filter').on('change', function() {
                var manufacturer = $(this).val();
                table.column(1).search(manufacturer).draw();
            });

            // technician status filtering
            $('#technician-filter').on('change', function() {
                var selectedStatus = $(this).val();
                if (selectedStatus) {
                    table.column(4).search('^' + selectedStatus + '$', true, false).draw();
                } else {
                    // If no status is selected, clear the filter
                    table.column(4).search('').draw();
                }
            });

            // Payment status filtering
            $('#payment-status-filter').on('change', function() {
                var selectedStatus = $(this).val();
                if (selectedStatus) {
                    table.column(7).search('^' + selectedStatus + '$', true, false).draw();
                } else {
                    // If no status is selected, clear the filter
                    table.column(7).search('').draw();
                }
            });

        });
    </script>
    <script>
        // Add click event listener to the "Comments" dropdown item
        document.querySelectorAll('.add-comment').forEach(item => {
            item.addEventListener('click', event => {
                // Show the corresponding modal for adding comments
                // The modal ID is extracted from the href attribute of the dropdown item
                const modalId = item.getAttribute('data-bs-target');
                const modal = new bootstrap.Modal(document.querySelector(modalId));
                modal.show();
            });
        });
    </script>
@endsection
