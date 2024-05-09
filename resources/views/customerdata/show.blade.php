@extends('home')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-9 align-self-center">
            <h4 class="page-title">{{ $username->name ?? '' }}</h4>
        </div>
        <div class="col-3 align-self-center justify-content-md-end"><a href="" id="newCustomerBtn"  class="btn btn-info"><i class="fas fa-calendar-check"></i> Add Ticket</a> <a href="https://dispatchannel.com/portal/customers-data" class="btn btn-success mx-2"><i class="ri-user-add-line"></i> Customer Data</a></div>

    </div>

</div>

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
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    </div>
    @endif



    <div class="row">
	
		
        <div class="col-md-9">
		
			@foreach($users->Jobdata as $job)
			
            <div class="mb-4">

                <div class="card" style="border: 1px solid #D8D8D8;">
                    <form action="{{ route('customerdata.update') }}" method="post"
                        id="updateForm{{ $job->job_code ?? '' }}">
                        @csrf
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ticket_number" class="control-label bold col-form-label ">Ticket Number</label>
                                    <input type="text" name="ticket_number" class="form-control" id="ticket_number"
                                        value="{{ $job->job_code ?? '' }}" >
                                    <input type="hidden" name="job_id" value="{{ $job->id }}" >
                                </div>
                                <div class="col-md-6  "><label for="tcc"
                                        class="control-label bold col-form-label required-field">
                                        TCC</label>
                                    <input type="text" name="tcc" class="form-control" id="tcc"
                                        value="{{ $users->tcc }}">
                                </div>
                             </div>
							
							<div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="schedule_date" class="control-label bold col-form-label ">Schedule
                                        Date</label>
                                    <input type="text" name="schedule_date" class="form-control" id="schedule_date"
                                        value="{{ $job->schedule->start_date_time ? \Carbon\Carbon::parse($job->schedule->start_date_time)->format('D, M j \'y h:i a') . ' - ' . \Carbon\Carbon::parse($job->schedule->end_date_time)->format('h:i a') . ' ' . ($job->schedule->start_date_time ? \Carbon\Carbon::parse($job->schedule->start_date_time)->getTimezone()->getName() : '') : '' }}"
                                        >
                                </div>
                                <div class="col-md-6">
                                    <label for="technician"
                                        class="control-label bold col-form-label ">Technician</label>
                                    <input type="text" name="technician" class="form-control" id="technician"
                                        value="{{ $job->technician->name ?? '' }}" >
                                </div>
                            </div>
							
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="service_name" class="control-label bold col-form-label ">Service
                                        Name</label>
                                    <input type="text" name="service_name" class="form-control" id="service_name"
                                        value="{{ $job->Customerservice->service_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="amount" class="control-label bold col-form-label ">Service
                                        Amount</label>
                                    <input type="text" name="amount" class="form-control" id="amount"
                                        value="{{ $job->Customerservice->amount }}">
                                </div>
                            </div>
							
                            <div class="row mb-3">
                                
                                <div class="col-md-12">
                                    <label for="notes" class="control-label bold col-form-label ">Notes</label>
                                    <textarea rows="2" name="notes" class="form-control"
                                        id="notes">{{ $job->Customernote->notes }}</textarea>
                                </div>
								<div class="col-md-4">
                                    <label for="notes_by" class="control-label bold col-form-label ">By</label>
                                    <input type="text" name="notes_by" class="form-control" id="notes_by"
                                        value="{{ $job->Customernote->notes_by }}">
                                </div>
                            </div>
							
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary me-2"
                                        id="submitButton{{ $job->id }}">Update</button>
                                    <a href="{{ route('customersdata.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </div>
							
                        </div>
                    </form>
                </div>

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
								<input type="text" name="no_of_visits" class="form-control"
									value="2">
							</div>
							<div class="col-md-12 mb-2">
								<label class="control-label bold col-form-label">Job Completed</label>
								<input type="text" name="job_completed" class="form-control"
									value="1">
							</div>
							<div class="col-md-12 mb-2">
								<label class="control-label bold col-form-label">Issue Resolved</label>
								<input type="text" name="issue_resolved" class="form-control"
									value="Yes">
							</div>
							<div class="col-md-12 mb-2">
								<label class="control-label bold col-form-label">Ticket IDs</label>
                                <div class="mb-2">ABC1234, XYZ1234</div>
                            </div>
							<div class="col-md-12 mb-2">
								<label class="control-label bold col-form-label">TCC</label>
                                <div class="mb-2">12AABBCC, 24ADV</div>
                            </div>
						</div>
						
                         
                    </div>
                </div>
            </div>
        </div>
		
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
                    <form id="customerForm">
                        <div class="row mb-1">
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="job_code">Ticket Number</label>
								 <input type="text" name="job_code" class="form-control" id="job_code" value="">
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
                                <input type="text" name="time_on_job" class="form-control" id="time_on_job"
                                    value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="total_time">Total Time</label>
                                <input type="text" name="total_time" class="form-control" id="total_time"
                                    value="">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <label class="control-label bold col-form-label" for="schedule_date">Schedule
                                    Date</label>
                                <input type="date" name="schedule_date" class="form-control" id="schedule_date">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label bold col-form-label" for="technician">Technician</label>
                                <input type="text" name="technician" class="form-control" id="technician" value="">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-8">
                                <label class="control-label bold col-form-label" for="service_name">Service
                                    Name</label>
                                <input type="text" name="service_name" class="form-control" id="service_name" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="control-label bold col-form-label" for="amount">Service Amount</label>
                                <input type="text" name="amount" class="form-control" id="amount" value="">
                            </div>
                        </div>
						<div class="row mb-1">
							<div class="col-md-9">
							</div>
                            <div class="col-md-3">
                                <a href="#.">Add More Services</a>
                             </div>
                        </div>
                        <div class="row mb-1">
							<div class="col-md-8">
                                <label class="control-label bold col-form-label" for="notes">Notes</label>
                                <textarea rows="1" name="notes" class="form-control" id="notes"></textarea>
                            </div>
							<div class="col-md-4">
                                <label class="control-label bold col-form-label" for="notes_by">By</label>
                                <input type="text" name="notes_by" class="form-control" id="notes_by" value="">
                            </div>
                        </div>
						<div class="row mb-1">
							<div class="col-md-10">
							</div>
                            <div class="col-md-2">
                                <a href="#.">Add More Notes</a>
                             </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> <button
                                type="button" class="btn btn-primary">Save changes</button>
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
