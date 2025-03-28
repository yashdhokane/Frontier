@extends('home')

@section('content')
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Payments & Invoices</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payments & Invoices</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">

                    <a href="#." id="filterButton" class="btn  btn-secondary mx-2">
                        <i class="ri-filter-line"></i> Filters
                    </a>
                    @include('header-top-nav.job-nav')



                </div>
            </div>
        </div>

    </div>
    @if (Session::has('success'))
        <div class="alert_wrap">
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid pt-2">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        <div class="">

            <div id="filterDiv" class="card card-body shadow" style="display: none;">
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
            </div>
            <!-- Add your table or content here -->

            <div class="col-12 card card-border card-body shadow">
         <div class="table-responsive ">
                            <table id="multi_control" class="table table-striped table-bordered display text-nowrap table-hover "
                                data-paging="true" data-paging-size="7">
                        <thead>
                            <!-- start row -->
                            <tr>
                                <th style="display:none;">No</th>  

                                <th>Invoice ID 
                                       </th>
                                  <th>Invoice Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                              
                                <th>Job Details</th>
                                <th>Customer</th>
                                <th>Technician</th>
                               
                                <th>Action</th>
                            </tr>
                            <!-- end row -->
                        </thead>
                        <tbody>
                            <!-- start row -->
                            @foreach ($payments as $index => $item)
                                <tr>
                                 <td style="display:none;">{{ $index + 1 }}</td>
                                    <td><a
                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                    </td>
                                       <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                                    <td>${{ $item->total ?? null }}</td>
                                    <td style="text-transform: capitalize;">{{ $item->status ?? null }}</td>

                                   <!-- <td>{{ $item->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}
                                    </td> -->

                                    <td>

                                <div class="text-wrap2 d-flex">
                                                        <div class=" text-truncate">
                                                            <a href="{{ route('tickets.show', $item->JobModel->id) }}"
                                                                class="font-medium link">
                                                                {{ $item->JobModel->job_title ?? null }}</a>
                                                        </div>
                                                        <span
                                                            class="badge bg-light-warning text-warning font-medium">{{$item->JobModel->status ?? null }}</span>
                                                    </div>
                                                    <div style="font-size:12px;">
                                                        @if ($item->JobModel->JobAppliances && $item->JobModel->JobAppliances->Appliances)
                                                            {{ $item->JobModel->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                                        @endif
                                                        @if ($item->JobModel->JobAppliances && $item->JobModel->JobAppliances->Appliances)
                                                            {{ $item->JobModel->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                                        @endif
                                                        @if ($item->JobModel->JobAppliances && $item->JobModel->JobAppliances->Appliances->model_number)
                                                            {{ $item->JobModel->JobAppliances->Appliances->model_number ?? null }}/
                                                        @endif
                                                        @if ($item->JobModel->JobAppliances && $item->JobModel->JobAppliances->Appliances->serial_number)
                                                            {{ $item->JobModel->JobAppliances->Appliances->serial_number ?? null }}
                                                        @endif
                                                    </div>

                                    </td>
                                    <td>{{ $item->user->name ?? null }}</td>
                                    <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                 
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-secondary text-white dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ url('invoice-detail/' . $item->id) }}"><i
                                                        data-feather="eye" class="feather-sm me-2"></i>
                                                    View</a>

                                                <!-- Comments option -->
                                                <a class="dropdown-item add-comment" href="javascript:void(0)"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#commentModal{{ $index }}">
                                                    <i data-feather="message-circle" class="feather-sm me-2"></i>
                                                    Comments
                                                </a>
                                                @if ($item->status != 'paid')
                                                    <a class="dropdown-item"
                                                        href="{{ url('update/payment/' . $item->id) }}"><i
                                                            data-feather="edit-2" class="feather-sm me-2"></i>
                                                        Mark Complete</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                                <!-- Modal for adding comment -->
                                <div class="modal fade" id="commentModal{{ $index }}" tabindex="-1"
                                    aria-labelledby="commentModalLabel{{ $index }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="commentModalLabel{{ $index }}">
                                                    Add Comment
                                                </h5>
                                                {{-- <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button> --}}
                                            </div>
                                            <!-- Comment form -->
                                            <form action="{{ url('store/comment/' . $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="comment">Comment:</label>
                                                        <textarea class="form-control" id="comment" name="payment_note" rows="3">

                                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
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






    <!-- start - This is for export functionality only -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        //=============================================//
        //    File export                              //
        //=============================================//
        $(document).ready(function() {

            if ($.fn.DataTable.isDataTable('#multi_control')) {
                $('#multi_control').DataTable().destroy();
            }

            var table = $('#multi_control').DataTable({
                "dom": '<"top"f>rt<"bottom d-flex justify-content-between mt-4"lp><"clear">',
                "paging": true,
                "info": false,
                "pageLength": 50, // Set default pagination length to 50

               

                "language": {
                     "search": "",
                        "searchPlaceholder": "search"
                }
            });

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
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterButton').addEventListener('click', function(event) {
                event.preventDefault();
                const filterDiv = document.getElementById('filterDiv');
                if (filterDiv.style.display === 'none') {
                    filterDiv.style.display = 'block';
                } else {
                    filterDiv.style.display = 'none';
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
