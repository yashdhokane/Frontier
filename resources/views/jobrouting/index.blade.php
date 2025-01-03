@extends('home')

@section('content')
    <link href="{{ asset('public/admin/routing/style.css') }}" rel="stylesheet" />

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
            <div class="modal-header d-flex align-items-center mod-head">
                <h4 class="modal-title" id="allJobsTechnicianLabel46"></h4>
            </div>
            <div class="modal-body row openJobTechDetails">
                <!-- Original content -->
            </div>
        </div>
    </div>
</div>


    @include('jobrouting.modal')
    @include('jobrouting.script')
@endsection
