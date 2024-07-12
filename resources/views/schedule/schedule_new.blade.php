@extends('home')
@section('content')
    <style>
        .show_job_details {
            text-decoration: none;
            /* Prevent underline on links */
        }

        .job-container {
            display: flex;
            flex-wrap: wrap;
            /* Allows jobs to wrap to the next line if necessary */
            margin-bottom: 10px;
            /* Adjust margin as needed */
        }

        .schedulJob {
            font-size: 9px;
        }
    </style>

    <div class="page-wrapper p-0 ms-2" style="display:flex;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->

        <div class="container-fluid container-schedule">

            <div class="row">

                <div class="card">
                    <div>
                        <div class="row gx-0 px-3">
                            <div class="col-lg-12 d-flex justify-content-end mt-1">
                                <ul class="nav nav-pills" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#navpill-1" role="tab">
                                            <span>Screen 1</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#navpill-2" role="tab">
                                            <span>Screen 2</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#navpill-3" role="tab">
                                            <span>Screen 3</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#navpill-4" role="tab">
                                            <span>Expand All</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-12">
                                <div class="row calendar-container1">
                                    <div class="col-lg-12 screen1">
                                        @include('schedule.scheduleCalender1')
                                    </div>
                                    <div class="col-lg-12 screen2" style="display: none;">
                                        @include('schedule.scheduleCalender2')
                                    </div>
                                    <div class="col-lg-12 screen3" style="display: none;">
                                        @include('schedule.scheduleCalender3')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal -->





        </div>

    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="event" tabindex="-1" aria-labelledby="scroll-long-inner-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
            <form action="{{ url('store/event/') }}" method="POST" id="addEvent">
                <input type="hidden" name="event_technician_id" id="event_technician_id" value="">
                <input type="hidden" name="scheduleType" id="scheduleType" value="event">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;">
                                New Event
                            </h4>
                        </div>
                        <button type="submit" class="btn btn-primary">SAVE EVENT</button>

                    </div>
                    <hr color="#6a737c">
                    <div class="modal-body createCustomerData pb-5">
                        @include('schedule.event')

                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    @include('schedule.indexScript')
@endsection
