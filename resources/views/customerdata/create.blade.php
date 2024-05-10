@extends('home')

@section('content')


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


    @foreach($users->Jobdata as $job)
    <div class="row">
        <div class="col-md-9">
            <div class="mb-4">

                <div class="card" style="border: 1px solid #D8D8D8;">
                    <form action="{{ route('customerdata.create') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ticket_number" class="control-label bold mb5 col-form-label ">Ticket
                                        Number</label>
                                    <input type="text" name="ticket_number" class="form-control" id="ticket_number"
                                        value="{{ $job->job_code ?? '' }}" readonly>
                                    <input type="hidden" name="job_id" value="{{ $job->id }}" readonly>
                                </div>
                                <div class="col-md-6  "><label for="tcc"
                                        class="control-label bold mb5 col-form-label required-field">
                                        TCC</label>
                                    <input type="text" name="tcc" class="form-control" id="tcc"
                                        value="{{ $users->tcc }}">
                                </div>
                                <!-- Additional static fields -->

                            </div>
                            {{-- <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label ">Total Travel Time</label>
                                    <input type="text" name="travel_time" class="form-control" id="tcc" value="0h 21m"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label ">Total Time on Job</label>
                                    <input type="text" name="tota_travel_time" class="form-control" id="tcc"
                                        value="1h 08m" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label ">Status</label>
                                    <input type="text" name="tota_travel_time" class="form-control" id="tcc"
                                        value="Time Auto Stopped" readonly>
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label">No of Visits</label>
                                    <input type="text" name="no_of_visits" class="form-control"
                                        value="{{ $users->no_of_visits }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label">Job Completed</label>
                                    <input type="text" name="job_completed" class="form-control"
                                        value="{{ $users->job_completed }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label bold mb5 col-form-label">Issue Resolved</label>
                                    <input type="text" name="issue_resolved" class="form-control"
                                        value="{{ $users->issue_resolved }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="schedule_date" class="control-label bold mb5 col-form-label ">Schedule
                                        Date</label>
                                    <input type="text" name="schedule_date" class="form-control" id="schedule_date"
                                        value="{{ $job->schedule->start_date_time ? \Carbon\Carbon::parse($job->schedule->start_date_time)->format('D, M j \'y h:i a') . ' - ' . \Carbon\Carbon::parse($job->schedule->end_date_time)->format('h:i a') . ' ' . ($job->schedule->start_date_time ? \Carbon\Carbon::parse($job->schedule->start_date_time)->getTimezone()->getName() : '') : '' }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="technician"
                                        class="control-label bold mb5 col-form-label ">Technician</label>
                                    <input type="text" name="technician" class="form-control" id="technician"
                                        value="{{ $job->technician->name ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="service_name" class="control-label bold mb5 col-form-label ">Service
                                        Name</label>
                                    <input type="text" name="service_name" class="form-control" id="service_name"
                                        value="{{ $job->Customerservice->service_name }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="amount" class="control-label bold mb5 col-form-label ">Service
                                        Amount</label>
                                    <input type="text" name="amount" class="form-control" id="amount"
                                        value="{{ $job->Customerservice->amount }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="notes_by" class="control-label bold mb5 col-form-label ">Notes
                                        BY</label>
                                    <input type="text" name="notes_by" class="form-control" id="notes_by"
                                        value="{{ $job->Customernote->notes_by }}">
                                </div>
                                <div class="col-md-8">
                                    <label for="notes" class="control-label bold mb5 col-form-label ">Notes
                                        Description</label>
                                    <textarea rows="4" name="notes" class="form-control"
                                        id="notes">{{ $job->Customernote->notes }}</textarea>
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

        </div>
        <div class="col-md-3">
            <div class="mb-4">
                <div class="card" style="border: 1px solid #D8D8D8;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2"><strong>No of visits</strong> {{$users->no_of_visits ?? '0'}}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Job Completed</strong>{{$users->job_completed ?? '0'}}</div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-2"><strong>Issue resolved</strong>{{ $users->issue_resolved ?? ''}}</div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="mb-2"><strong>Ticket IDs</strong>@php
                                    $jobCodes = $Job->pluck('job_code')->filter()->toArray(); // Filter out empty values

                                    @endphp

                                    {{ implode(',', $jobCodes) ?: ' ' }}

                                </div>
                            </div> --}}

                            <div class="col-md-12">
                                <div class="mb-2"><strong>TCC</strong> {{ $users->tcc ?? ''}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @endforeach

</div>
</div>


@section('script')
@stop
@stop
