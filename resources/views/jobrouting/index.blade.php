@extends('home')

@section('content')
    <style>
        #newEntryFields .card-body {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            /* Space between items */
        }

        #newEntryFields .col-md-2 {
            margin-top: 5px;
            /* Margin at the top for spacing */
        }

        .bold {
            font-weight: 600;
        }

        .form-select,
        .form-check-input {
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
        }

        #submitButton {
            padding: 0.5rem 1.5rem;
        }

        .select2-container {
            width: 100% !important;
        }



        //above is style for map above section



        .full-width-map {
            width: 100%;
            height: 500px;
        }

        .select2 {
            width: 10.75em;
        }

        #map {
            margin-top: 10px;
            width: 100%;

        }

        #menu {
            margin-top: 20px;
        }
    </style>

    <div class=" col-md-12">
        <div class="d-flex justify-content-between align-items-center" id="menu">
            <div class="col-md-3 row">
                <div class="col-md-6">
                    <select id="dateDay" name="dateDay" class="form-select select ms-1">
                        <option value="today">Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="nextdays">Next 3 Days</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select id="routing" name="routing" class="form-select select">
                        <option value="bestroute">Best Route</option>
                        <option value="shortestroute">Shortest Route</option>
                        <option value="customizedroute">Customized Route</option>
                    </select>
                </div>

            </div>
            <div class="col-md-3 ms-3">
                <select id="routingTriggerSelect" name="routing_id" class="form-select select2 " multiple="multiple">
                    <option value="all">All Technician(s)</option>
                    @foreach ($tech as $routing)
                        <option value="{{ $routing->id }}">
                            {{ $routing->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-2 d-flex align-items-center">
                <a href="javascript:void(0);" id="setNewButton1" class="text-decoration-none text-primary"
                    style="color:black;"><i class="ri-settings-2-line"></i> Routing Setting</a><span
                    style="margin-left:5px;">|</span>
                <a href="javascript:void(0);" id="fullview" class="text-decoration-none  text-warning"
                    style="color:black; margin-left:10px;"><i class="ri-zoom-in-line"></i> Full View</a>
            </div>
        </div>
    </div>
    <div class="row">
        @include('jobrouting.map')
        @include('jobrouting.job_details')
    </div>
    @include('jobrouting.modal')
    @include('jobrouting.script')
@endsection
