@extends('home')
@section('content')
    <!-- -------------------------------------------------------------- -->
    <style>
        .small text-muted {
            display: none !imortant;
            visibility: hidden !imortant;
        }
    </style>
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-4 align-self-center">
                <h4 class="page-title">Tax</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tax Rate</li>
                        </ol>
                    </nav>
                </div>
            </div> 
            <div class="col-8 text-end px-4">
              @include('header-top-nav.settings-nav') 
            </div>
        </div>
    </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="container-fluid pt-2">
            <!-- Add this section where you want to display messages -->
            @if (session('success'))
                <div id="successAlert" class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="errorAlert" class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="row">

                <div class="col-9 card card-border shadow">
                    <div class="">
                        <div class="card-body">
                            <table id="default_order"
                                class="table table-hover table-striped search-table v-middle display text-nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>States</th>
                                        <th>Tax Rates</th>
                                        <th>Last Modified</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($states as $state)
                                        <tr>
                                            <td>{{ $state->state_name ?? null }}</td>
                                            <td>{{ $state->state_tax ?? null }}</td>
                                            <td>{{ $convertDateToTimezone($state->created_at) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-secondary edit-btn"
                                                    data-state-id="{{ $state->state_id }}" data-bs-toggle="modal"
                                                    data-bs-target="#samedata-modal2">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            {{-- <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-start">
                                Showing {{ $states->firstItem() }} to {{ $states->lastItem() }} of {{ $states->total()
                                }}
                                entries
                            </div>
                            <div class="text-end">
                                {{ $states->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div> --}}

                        </div>
                    </div>
                </div>

                <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="exampleModalLabel1">Edit Tax Rate</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="taxrateformupdate">
                                <!-- Form content will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="col-md-12 ">
                        <div class="card-body card card-border shadow">
                            <h4 class="card-title">Tax</h4>
                            <p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod tempor incididunt minim veniam</p>
                            <p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                            <a href="#" class="card-link">Need more help?</a>
                        </div>
                    </div>

                </div>

            </div>


        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- footer -->

    @section('script')
        <script>
            $(document).ready(function() {
                if ($.fn.DataTable.isDataTable('#default_order')) {
                    $('#default_order').DataTable().destroy();
                }

                var table = $('#default_order').DataTable({
                    "dom": '<"top"f>rt<"bottom d-flex justify-content-between mt-4"lp><"clear">',
                    "paging": true,
                    "info": false,
                    "pageLength": 50, // Set default pagination length to 50
                     "language": {
                            "search": "",
                            "searchPlaceholder": "search"
                        }
                });
                $('.edit-btn').click(function() {
                    var stateId = $(this).data('state-id');

                    // Make an AJAX request to fetch the form content
                    $.ajax({
                        url: '{{ route('get.edit.form') }}', // Correct route name
                        method: 'GET',
                        data: {
                            id: stateId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Update the modal body with the fetched form content
                            $('#taxrateformupdate').html(response);
                        },
                        error: function(error) {
                            console.error('Error fetching form content:', error);
                        }
                    });
                });
            });
        </script>
        <script>
            // Function to hide the alert after 5 seconds
            function hideAlerts() {
                document.getElementById('successAlert').style.display = 'none';
                document.getElementById('errorAlert').style.display = 'none';
            }

            // Set timeout to hide alerts after 5 seconds
            setTimeout(hideAlerts, 5000);
        </script>
    @endsection
@endsection
