@extends('home')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">{{ $user->userdata->name ?? '' }}</h4>
        </div>
        <div class="col-7 align-self-center"></div>
    </div>
</div>

<div class="container-fluid">
    @if (Session::has('success'))
    <div class="alert_wrap">
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
            <strong>Done.</strong> {{ Session::get('success') }} <button type="button" class="btn-close"
                data-bs-dismiss="alert" aria-label="Close"></button>
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
        @foreach($user as $job)
        <div class="col-md-9">
            <div class="mb-4">

                <div class="card" style="border: 1px solid #D8D8D8;">
                    <form action="{{ route('customerdata.update') }}" method="post" id="updateForm{{ $job->data_id }}">
                        @csrf
                        <div class=" card-body">

                            <div class=" row">
                                <div class="col-md-6 label" id="ticket-number-label">
                                    <!-- <div class="mb-2"><strong>Ticket Number:</strong> {{ $job->job_code ?? '' }}</div> -->
                                </div>
                                <div class="col-md-6 input-box" style="display: none;" id="ticket-number-input-box">
                                    <input type="text" name="job_code" class="form-control" id="input-box"
                                        placeholder="Ticket Number">
                                    <!-- <input type="hidden" name="job_id" class="form-control" value="{{ $job->id }}" -->
                                    id="input-box">
                                </div>
                                <div class="col-md-6 label">
                                    <div class="mb-2"><strong>TCC:</strong> AFGV4</div>
                                </div>
                                <div class="col-md-6 input-box" style="display: none;" id="tcc-input-box">
                                    <input type="text" name="tcc" class="form-control" id="input-box" placeholder="TCC">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 label">
                                    <div class="mb-2"><strong>Schedule date:</strong>
                                        {{-- @if($job->schedule->start_date_time && $job->schedule->end_date_time)
                                        {{ \Carbon\Carbon::parse($job->schedule->start_date_time)->format('D, M j \'y
                                        h:i
                                        a') }}
                                        -
                                        {{ \Carbon\Carbon::parse($job->schedule->end_date_time)->format('h:i a') }}
                                        {{ $job->schedule->start_date_time ?
                                        \Carbon\Carbon::parse($job->schedule->start_date_time)->getTimezone()->getName()
                                        :
                                        '' }}
                                        @endif --}}
                                        {{ $job->Customerdata->schedule_date ?? '' }}
                                    </div>
                                </div>
                                <div class="col-md-6 input-box" style="display: none;" id="schedule-date-input-box">
                                    <input type="text" name="schedule_date" class="form-control" id="input-box"
                                        placeholder="Schedule date">
                                </div>
                                <div class="col-md-6 label">
                                    <div class="mb-2"><strong>Technician:</strong> {{ $job->technician->name ?? '' }}
                                    </div>
                                </div>

                                <div class="col-md-6 input-box" style="display: none;" id="technician-input-box">
                                    <input type="text" name="technician" class="form-control" id="input-box"
                                        placeholder="Technician">
                                </div>
                            </div>

                            <div class="row mt-4 label">
                                <h5 class="card-title uppercase">SERVICES</h5>
                            </div>
                            <div class="row label">
                                <div class="col-md-8">
                                    <div class="mb-2 ft13">{{ $job->Customerservice->service_name ?? '' }}</div>
                                </div>
                                <div class="col-md-4">{{ isset($job->Customerservice->amount) ? '$' .
                                    $job->Customerservice->amount : '$0' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 input-box" style="display: none;" id="technician-input-box">
                                    <input type="text" name="service_name" class="form-control" id="input-box"
                                        placeholder="service name">
                                    <input type="text" name="amount" class="form-control" id="input-box"
                                        placeholder="amount">
                                </div>
                            </div>

                            <div class="row mt-4 label">
                                <h5 class="card-title uppercase">NOTES</h5>
                            </div>
                            <div class="row label">
                                <div class="col-md-2">{{ $job->Customernote->notes_by ?? '' }}</div>
                                <div class="col-md-10">
                                    <div class="mb-2 ft13">{{ $job->Customernote->notes ?? '' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 input-box" style="display: none;" id="technician-input-box">
                                    <input type="text" name="notes_by" class="form-control" id="input-box"
                                        placeholder="notes by">
                                    <input type="text" name="notes" class="form-control" id="input-box"
                                        placeholder="notes">
                                </div>
                            </div>
                            <div class="col-md-8 input-box" style="display: none;" id="">
                                <button type="submit" class="btn btn-primary mt-2"
                                    id="submitButton{{ $job->id }}">Update</button>
                                <button type="button" class="btn btn-secondary mt-2 cancel-button"
                                    id="cancelButton{{ $job->id }}">Cancel</button>
                            </div>
                    </form>
                </div>

            </div>

        </div>
    </div>


    @endforeach
    <div class="col-md-3">
        <div class="mb-4">
            <div class="card" style="border: 1px solid #D8D8D8;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2"><strong>No of visits:</strong> {{$visitcount ?? '0'}}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2"><strong>Job Completed:</strong>{{$jobcompletecount ?? '0'}}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2"><strong>Issue resolved:</strong> Yes</div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2"><strong>Ticket IDs:</strong>@php
                                $jobCodes = $Job->pluck('job_code')->filter()->toArray(); // Filter out empty values

                                @endphp

                                {{ implode(',', $jobCodes) ?: ' ' }}

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2"><strong>TCC:</strong> AFGV4, AFGV5</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@stop

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to toggle visibility of label and input box
    function toggleInputFields() {
        // Hide all labels and show all input boxes
        var labels = document.querySelectorAll('.label');
        var inputBoxes = document.querySelectorAll('.input-box');

        labels.forEach(function(label) {
            label.style.display = 'none';
        });

        inputBoxes.forEach(function(inputBox) {
            inputBox.style.display = 'block';
        });
    }

    // Call the function when any label is clicked
    var labels = document.querySelectorAll('.label');
    labels.forEach(function(label) {
        label.addEventListener('click', function() {
            toggleInputFields();
        });
    });

    function cancelUpdate() {
        var labels = document.querySelectorAll('.label');
        var inputBoxes = document.querySelectorAll('.input-box');

        labels.forEach(function(label) {
            label.style.display = 'block';
        });

        inputBoxes.forEach(function(inputBox) {
            inputBox.style.display = 'none';
        });
    }

    // Call the function when any label is clicked
    var labels = document.querySelectorAll('.label');
    labels.forEach(function(label) {
        label.addEventListener('click', function() {
            toggleInputFields();
        });
    });

    // Add event listener to cancel button
    var cancelButtons = document.querySelectorAll('.cancel-button');
    cancelButtons.forEach(function(cancelButton) {
        cancelButton.addEventListener('click', function() {
            cancelUpdate();
        });
    });
});
</script>
@stop