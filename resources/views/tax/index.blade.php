@extends('home')
@section('content')
    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb pt-0">
        <div class="row">
            <div class="col-5 align-self-center">
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
            <div class="col-7 align-self-center">
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
        <div class="card card-border shadow">
            <div class="col-12">
                <div class="">
                    <div class="card-body">
                        <table id="default_order" class="table table-striped table-bordered display text-nowrap"
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
                                            <button type="button" class="btn btn-primary edit-btn"
                                                data-state-id="{{ $state->state_id }}" data-bs-toggle="modal"
                                                data-bs-target="#samedata-modal2">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="exampleModalLabel1">Edit Tax Rate</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="taxrateformupdate">
                            <!-- Form content will be loaded here dynamically -->
                        </div>
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
            $('#default_order').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "pageLength": 25,
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
