@extends('home')

@section('content')

<div class="container-fluid pt-2">

     <link href="{{ asset('public/admin/routing/style.css') }}" rel="stylesheet" />
	
	@if($routingJob->isEmpty())
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-warning mb-0 pt-2 pb-2" role="alert">
					<strong>Note:</strong> The job routing is not set.
				</div>
			</div>
		</div>
	@endif
	
	<div class="row">	
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

				<div class="col-md-3"></div>

				<div class="col-md-3 d-flex text-end">
					<a href="javascript:void(0);" id="setNewButton1" class="btn btn-info btn-rounded"><i class="ri-settings-2-line"></i> Routing Setting</a>
					<a href="javascript:void(0);" id="fullview" class="btn btn-info btn-rounded mx-3"><i class="ri-zoom-in-line"></i> Full View</a>
				</div>
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

</div>
