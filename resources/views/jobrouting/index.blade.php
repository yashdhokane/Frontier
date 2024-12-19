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
        .gm-style .gm-style-iw-d {
            margin-top: -30px;
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
                        <option value="week">Next 7 Days</option>
                        <option value="chooseDate">Choose Date</option>
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
    <div class=" col-md-12" id="showChooseDate">
      <div class="row ps-1 pt-2">
       <div class=" col-md-2" >
      <input type="date" class="form-control" name="chooseFrom" id="chooseFrom">
       </div>
       <div class=" col-md-2">
      <input type="date" class="form-control" name="chooseTo" id="chooseTo">

       </div>
       </div>
    </div>

    <div class="row">
        @include('jobrouting.map')
        @include('jobrouting.job_details')
    </div>

    
<!-- Modal -->
<div class="modal fade" id="allJobsTechnician" tabindex="-1" aria-labelledby="allJobsTechnicianLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="allJobsTechnicianLabel46"></h4>


                <button class=" popup-option123 togglebutton btn btn-outline-primary btn-sm">Best Route</button>

            </div>
            <div class="modal-body row openJobTechDetails">
                <!-- Original content -->
            </div>
            <!-- <div class="modal-body row mapbestroute" style="display:none;">
         
                            <div id="map" style="height: 500px; width: 100%;"></div>

        </div> -->
            <div class="modal-body row mapbestroute" style="display:none;">
                <!-- Buttons -->
                <div class="col-12">
                    <button class="btn btn-primary float-left">Default Route</button>
                    <button class="btn btn-success float-left" style="margin-left: 5px;">Save Route</button>
                </div>


                <div class="d-flex w-100 mt-3">

                    <div id="customer-show" style="width: 35%; height: 500px; background-color: #f1f1f1;">

                    </div>

                    <!-- Second div - map (75% width) -->
                    <div id="map1" style="width: 65%; height: 500px; background-color: #e2e2e2;">

                    </div>
                </div>

                <!-- Third div - map2 (75% width, hidden by default) -->
                <div id="map2" style="width: 75%; height: 500px; background-color: #ccc; display: none;">

                </div>


                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- map best root model -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Best Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="map3" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

</div>


    @include('jobrouting.modal')
    @include('jobrouting.script')
@endsection
