@extends('home')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-9 align-self-center">
            <h4 class="page-title">{{ $username->name ?? '' }}</h4>
        </div>
        <div class="col-3 align-self-center justify-content-md-end"><a href="" id="newCustomerBtn"
                class="btn btn-info"><i class="fas fa-calendar-check"></i> Add Ticket</a> <a
                href="https://dispatchannel.com/portal/customers-data" class="btn btn-success mx-2"><i
                    class="ri-user-add-line"></i> Customer Data</a></div>

    </div>

</div>
<style>
    /* CSS for appended image section */
    .add_more {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .add_more img {
        height: 70px;
        width: auto;
        margin-right: 10px;
    }
</style>
<div class="container-fluid">

    @if (Session::has('success'))
    <div class="alert_wrap">
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
            {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if (Session::has('error'))
    <div class="alert_wrap">
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show">
            {{ Session::get('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    </div>
    @endif



    <div class="row">
        @php $index=0; @endphp
        <div class="col-md-9">
            @if (!empty($users->Jobdata))

            @foreach($users->Jobdata as $job)
            @php $index++; @endphp
            <div class="mb-4">

                <div class="card" style="border: 1px solid #D8D8D8;">
                    <form action="{{ route('customerdata.update') }}" method="post" id="updateForm{{ $index ?? '' }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ticket_number" class="control-label bold col-form-label ">Ticket
                                        Number</label>
                                    <input type="text" name="ticket_number" class="form-control" id="ticket_number"
                                        value="{{ $job->ticket_number ?? '' }}">
                                    <input type="hidden" name="job_id" value="{{ $job->job_id ?? '' }}">
                                </div>
                                <div class="col-md-6  "><label for="tcc"
                                        class="control-label bold col-form-label required-field">
                                        TCC</label>
                                    <input type="text" name="tcc" class="form-control" id="tcc"
                                        value="{{ $job->tcc ?? '' }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="schedule_date" class="control-label bold col-form-label ">Schedule
                                        Date</label>
                                    <input type="text" name="schedule_date" class="form-control" id="schedule_date"
                                        value="{{ $job->schedule_date ?? ''}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="technician"
                                        class="control-label bold col-form-label ">Technician</label>
                                    <input type="text" name="technician" class="form-control" id="technician"
                                        value="{{ $job->technician ?? '' }}">
                                </div>
                            </div>
                            <div class="container mt-5">
                                <div id="file-input-containery{{ $index }}">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label class="control-label bold col-form-label" for="Attachments">Files &
                                                Attachments</label>
                                            <div style="display: flex;">
                                                <input type="file" class="form-control" name="files[]" placeholder="" />
                                                <button style="margin-left:2px;" type="button"
                                                    class="btn btn-primary add-rowy{{ $index }}">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="add_morey{{ $index }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Include jQuery library -->

                            <input type="hidden" name="no_of_visits" id="hidden_no_of_visits"
                                value="{{ $users->no_of_visits ?? '' }}">
                            <input type="hidden" name="job_completed" id="hidden_job_completed"
                                value="{{ $users->job_completed ?? '' }}">
                            <input type="hidden" name="issue_resolved" id="hidden_issue_resolved"
                                value="{{ $users->issue_resolved ?? '' }}">
                            <div id="additional-services-container{{ $index }}">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="service_name" class="control-label bold col-form-label">Service
                                            Name</label>
                                        <input type="text" name="service_name" class="form-control" id="service_name"
                                            value="{{ $job->Customerservice->service_name ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="amount" class="control-label bold col-form-label">Service
                                            Amount</label>
                                        <input type="text" name="amount" class="form-control" id="amount"
                                            value="{{ $job->Customerservice->amount ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-9">
                                </div>
                                <div class="col-md-3">
                                    <a href="#." class="add-more-services{{ $index }}" data-index="{{ $index }}">Add
                                        More
                                        Services</a>
                                </div>
                            </div>

                            <div id="additional-notes-container{{ $index }}">
                                <div class="row mb-3">

                                    <div class="col-md-8">
                                        <label for="notes" class="control-label bold col-form-label ">Notes</label>
                                        <textarea rows="2" name="notes" class="form-control"
                                            id="notes">{{ $job->Customernote->notes ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="notes_by" class="control-label bold col-form-label ">Notes
                                            By</label>
                                        <input type="text" name="notes_by" class="form-control" id="notes_by"
                                            value="{{ $job->Customernote->notes_by ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-9">
                                </div>
                                <div class="col-md-3">
                                    <a href="#" class="add-more-notes{{ $index }}" data-index="{{ $index }}">Add More
                                        Notes</a>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary me-2"
                                        id="submitButton{{ $index }}">Update</button>
                                    <a href="{{ route('customersdata.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    // Your JavaScript code using jQuery
                         // Define the dispatcher_id variable
                            $(document).on('click', '.add-rowy{{ $index}}', function() {
                                var newFileInputGroup = `
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label class="control-label bold col-form-label" for="Attachments">Files & Attachments</label>
                                            <div style="display: flex;">
                                                <input type="file" class="form-control" name="files[]" placeholder="" />
                                                <button style="margin-left:2px;" type="button" class="btn btn-danger remove-row">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="add_morey"></div>
                                        </div>
                                    </div>`;
                                $('#file-input-containery{{ $index}}').append(newFileInputGroup);
                            });


                            $(document).on('click', '.add-more-services{{ $index }}', function(e) {
                            e.preventDefault();

                            var newServiceInputGroup = `
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="service_name_{{ $index }}" class="control-label bold col-form-label">Service Name</label>
                                    <input type="text" name="service_name[]" class="form-control" id="service_name_{{ $index }}" value="">
                                </div>
                                <div class="col-md-4">
                                    <label for="amount_{{ $index }}" class="control-label bold col-form-label">Service Amount</label>
                                    <input type="text" name="amount[]" class="form-control" id="amount_{{ $index }}" value="">
                                </div>
                            </div>`;
                            $('#additional-services-container{{ $index }}').append(newServiceInputGroup);
                            });

                            $(document).on('click', '.add-more-notes{{ $index }}', function(e) {
                            e.preventDefault();
                            // var index = $(this).data('index');

                            var newNotesInputGroup = `
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="notes_{{ $index }}" class="control-label bold col-form-label">Notes</label>
                                    <textarea rows="2" name="notes[]" class="form-control" id="notes_{{ $index }}"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="notes_by_{{ $index }}" class="control-label bold col-form-label">Notes By</label>
                                    <input type="text" name="notes_by[]" class="form-control" id="notes_by_{{ $index }}">
                                </div>
                            </div>`;

                            $('#additional-notes-container{{ $index }}' ).append(newNotesInputGroup);
                            });
                </script>
            </div>

            @endforeach

        </div>


        <div class="col-md-3">
            <div class="mb-4">
                <div class="card" style="border: 1px solid #D8D8D8;">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label class="control-label bold col-form-label">No of Visits</label>
                                <input type="text" name="no_of_visits" id="no_of_visits" class="form-control"
                                    value="{{ $users->no_of_visits ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="control-label bold col-form-label">Job Completed</label>
                                <input type="text" name="job_completed" id="job_completed" class="form-control"
                                    value="{{ $users->job_completed ?? ''}}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="control-label bold col-form-label">Issue Resolved</label>
                                <input type="text" name="issue_resolved" id="issue_resolved" class="form-control"
                                    value="{{ $users->issue_resolved ?? '' }}">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="control-label bold col-form-label">Ticket IDs</label>
                                <div class="mb-2">{{ htmlspecialchars(implode(', ', $ticketNumbers)) }}</div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="control-label bold col-form-label">TCC</label>
                                <div class="mb-2">{{ htmlspecialchars(implode(', ', $tccValues)) }}</div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="mb-12 d-flex flex-column ">

            <div class="card shadow-sm" style="width: auto; border: 1px solid #D8D8D8; border-radius: 4px;">

                <div class="card-body ">

                    <p class="mb-0">No ticket found.</p>

                </div>

            </div>

        </div>
        @endif
    </div>

    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title uppercase" id="customerModalLabel">Add Ticket</h4>

                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Your form content goes here -->
                    <!-- For example, you can include a form for adding new customer data -->
                    <form id="customerForm" action="{{ route('customerdata.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="job_code">Ticket Number</label>
                                <input type="text" name="ticket_number" class="form-control" id="job_code" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="tcc">TCC</label>
                                <input type="text" name="tcc" class="form-control" id="tcc" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="status">Status</label>
                                <input type="text" name="status" class="form-control" id="status" value="">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="time_travel">Time Travel</label>
                                <input type="text" name="time_travel" class="form-control" id="time_travel" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="time_on_job">Time on Job</label>
                                <input type="text" name="time_on_job" class="form-control" id="time_on_job" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="total_time">Total Time</label>
                                <input type="text" name="total_time" class="form-control" id="total_time" value="">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <label class="control-label bold col-form-label" for="schedule_date">Schedule
                                    Date</label>
                                <input type="text" name="schedule_date" class="form-control" id="schedule_date">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label bold col-form-label" for="technician">Technician</label>
                                <input type="text" name="technician" class="form-control" id="technician" value="">
                            </div>
                        </div>
                        <div class="container mt-5">
                            <div id="file-input-container">
                                <div class="row mb-1">
                                    <div class="col-md-6">
                                        <label class="control-label bold col-form-label" for="Attachments">Files &
                                            Attachments</label>
                                        <div style="display: flex;">
                                            <input type="file" class="form-control" name="files[]" placeholder="" />
                                            <button style="margin-left:2px;" type="button"
                                                class="btn btn-primary add-row">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="add_more"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-8">
                                <label class="control-label bold col-form-label" for="service_name">Service
                                    Name</label>
                                <input type="text" name="service_name[]" class="form-control" id="service_name"
                                    value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="amount">Service Amount</label>
                                <input type="text" name="amount[]" class="form-control" id="amount" value="">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-9">
                            </div>
                            <div class="col-md-3">
                                <a href="#." id="addMoreServices">Add More Services</a>
                            </div>
                        </div>
                        <div id="serviceTemplate" style="display: none;">
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <label class="control-label bold col-form-label" for="service_name">Service
                                        Name</label>
                                    <input type="text" name="service_name[]" class="form-control" id="service_name"
                                        value="">
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold col-form-label" for="amount">Service
                                        Amount</label>
                                    <input type="text" name="amount[]" class="form-control" id="amount" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-8">
                                <label class="control-label bold col-form-label" for="notes">Notes</label>
                                <textarea rows="1" name="notes[]" class="form-control" id="notes"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="notes_by">Notes By</label>
                                <input type="text" name="notes_by[]" class="form-control" id="notes_by" value="">
                                <input type="hidden" name="user_id" class="form-control" id="user_id"
                                    value="{{ $username->id }}">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-10">
                            </div>
                            <div class="col-md-2">
                                <a href="#." id="addMoreNotes">Add More Notes</a>
                            </div>
                        </div>
                        <div id="noteTemplate" style="display: none;">
                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <label class="control-label bold col-form-label" for="notes">Notes</label>
                                    <textarea rows="1" name="notes[]" class="form-control" id="notes"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold col-form-label" for="notes_by">Notes By</label>
                                    <input type="text" name="notes_by[]" class="form-control" id="notes_by" value="">
                                </div>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


</div>
</div>
@stop

@section('script')
<script>
    $(document).ready(function() {
        var src1; // Variable for the first file input
        var src2; // Variable for the second file input
        var blob1;
        var blob2;

        // Event handler for the first file input
        $("#image").on('change', function(e) {
            src1 = URL.createObjectURL(e.target.files[0]);
            let file = e.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(e) {
                blob1 = e.target.result;
            };
        });

        // Event handler for the second file input
        $("#images").on('change', function(e) {
            src2 = URL.createObjectURL(e.target.files[0]);
            let file = e.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(e) {
                blob2 = e.target.result;
            };
        });



        // Click event for adding a new row for the first file input
      $(document).on('click', '.add-row', function() {
    var newFileInputGroup = `
    <div class="row mb-1">
        <div class="col-md-6">
            <label class="control-label bold col-form-label" for="Attachments">Files & Attachments</label>
            <div style="display: flex;">
                <input type="file" class="form-control" name="files[]" placeholder="" />
                <button style="margin-left:2px;" type="button" class="btn btn-danger remove-row">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="add_more"></div>
        </div>
    </div>`;
    $('#file-input-container').append(newFileInputGroup);
    });

    // Handle remove-row button click
    $(document).on('click', '.remove-row', function() {
    $(this).closest('.row').remove();
    });

        $("#addMoreServices").click(function(e) {
            e.preventDefault();
            $("#serviceTemplate").show();
            $("#addMoreServices").hide();
        });

        // Add More Notes
        $("#addMoreNotes").click(function(e) {
            e.preventDefault();
            $("#noteTemplate").show();
            $("#addMoreNotes").hide();
        });
        // Handle click event of the button
        $('#newCustomerBtn').click(function(e) {
            e.preventDefault(); // Prevent default action of the anchor tag

            // Show the modal
            $('#customerModal').modal('show');
        });

        function closeModal() {
            $('#customerModal').modal('hide');
        }

        // Close the modal when clicking the close button
        $('#customerModal').on('click', '[data-dismiss="modal"]', function() {
            closeModal();
        });

        // Close the modal when clicking outside the modal
        $(document).on('click', function(event) {
            if ($(event.target).hasClass('modal')) {
                closeModal();
            }
        });

        function updateHiddenInput(inputId, hiddenInputId) {
            const inputValue = document.getElementById(inputId).value;
            document.getElementById(hiddenInputId).value = inputValue;
        }

        // Event listeners for input changes
        document.getElementById('no_of_visits').addEventListener('input', function() {
            updateHiddenInput('no_of_visits', 'hidden_no_of_visits');
        });

        document.getElementById('job_completed').addEventListener('input', function() {
            updateHiddenInput('job_completed', 'hidden_job_completed');
        });

        document.getElementById('issue_resolved').addEventListener('input', function() {
            updateHiddenInput('issue_resolved', 'hidden_issue_resolved');
        });
    });

</script>
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     // Function to toggle visibility of label and input box
    //     function toggleInputFields() {
    //         // Hide all labels and show all input boxes
    //         var labels = document.querySelectorAll('.label');
    //         var inputBoxes = document.querySelectorAll('.input-box');

    //         labels.forEach(function(label) {
    //             label.style.display = 'none';
    //         });

    //         inputBoxes.forEach(function(inputBox) {
    //             inputBox.style.display = 'block';
    //         });
    //     }

    //     // Call the function when any label is clicked
    //     var labels = document.querySelectorAll('.label');
    //     labels.forEach(function(label) {
    //         label.addEventListener('click', function() {
    //             toggleInputFields();
    //         });
    //     });

    //     function cancelUpdate() {
    //     var labels = document.querySelectorAll('.label');
    //     var inputBoxes = document.querySelectorAll('.input-box');

    //     labels.forEach(function(label) {
    //     label.style.display = 'block';
    //     });

    //     inputBoxes.forEach(function(inputBox) {
    //     inputBox.style.display = 'none';
    //     });
    //     }

    //     // Call the function when any label is clicked
    //     var labels = document.querySelectorAll('.label');
    //     labels.forEach(function(label) {
    //     label.addEventListener('click', function() {
    //     toggleInputFields();
    //     });
    //     });

    //     // Add event listener to cancel button
    //     var cancelButtons = document.querySelectorAll('.cancel-button');
    //     cancelButtons.forEach(function(cancelButton) {
    //     cancelButton.addEventListener('click', function() {
    //     cancelUpdate();
    //     });
    //     });
    // });

</script>
@stop