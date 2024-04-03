@extends('home')
@section('content')

<style>
    .activegreen {
        border: 2px solid green !important;
    }

    .user_head_link {
        color: #2962ff !important;
        text-transform: uppercase;
        font-size: 13px;
    }

    .user_head_link:hover {
        color: #ee9d01 !important;
    }


    .dts2 {
        min-height: 60px;
    }

    .table> :not(caption)>*>* {
        padding: 0.3rem;
    }

    .dat table th {
        text-align: center;
    }

    .dts {
        background: #3699ff;
        padding: 5px;
        border-radius: 5px;
        color: #FFFFFF;
    }

    .dts p {
        margin-bottom: 5px;
        line-height: 17px;
    }

    .out {
        background: #fbeccd !important;
    }

    .out:hover {
        background: #fbeccd !important;
    }

    .out .dts {
        background: #fbeccd !important;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-color-state: var(--bs-table-hover-color);
        --bs-table-bg-state: transparent;
    }

    img.calimg2 {
        width: 224px;
        margin: 0px 10px;
    }
</style>
<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb" style="padding-top: 0px;">
        <div class="row">
            <div class="col-5 align-self-center">
                <h3 class="page-title">Performance Metrix</h3>
            </div>
            <div class="col-7 align-self-right" style="text-align: right;padding-right: 40px;">
                <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i> Select
                    Dates</a>
             </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <div class="row">



            <div class="container">
 
                <div class="row shadow card-border mx-0">

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Top Performers</h4>
                                <h6 class="card-subtitle">Top 5 Performers</h6>
								<ul class="list-style-none mt-4">
									@foreach ($topPerformers as $performer)
 										<li class="mb-4">
											<div class="d-flex align-items-center">
												<div>
													<h6 class="mb-0 fw-bold">{{ $performer->name }} <span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
												</div>
												<div class="ms-auto">
													<h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
												</div>
											</div>
											<div class="progress mt-1">
												<div class="progress-bar bg-success" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</li>
 									@endforeach
								</ul>
                             </div>
                        </div>
					</div>

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Poor Performers</h4>
                                <h6 class="card-subtitle">Top 5 Poor Performers</h5>
								<ul class="list-style-none mt-4">
									@foreach ($goodPerformers as $performer)
 										<li class="mb-4">
											<div class="d-flex align-items-center">
												<div>
													<h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
												</div>
												<div class="ms-auto">
													<h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
												</div>
											</div>
											<div class="progress mt-1">
												<div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</li>
 									@endforeach
								</ul>
                             </div>
                        </div>
                     </div>

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Critical Performers</h4>
                                <h6 class="card-subtitle">Top 5 Critical performance</h6>
								<ul class="list-style-none mt-4">
									@foreach ($poorPerformers as $performer)
 										<li class="mb-4">
											<div class="d-flex align-items-center">
												<div>
													<h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
												</div>
												<div class="ms-auto">
													<h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
												</div>
											</div>
											<div class="progress mt-1">
												<div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</li>
 									@endforeach
								</ul>
                            </div>
                        </div>
                     </div>
 
                </div>


 				<div class="row shadow card-border mx-0 mt-5 mb-5">

                    <div class="bg-light py-2 px-3 border"">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
									<div class="col-md-8">
										<h4 class="card-title">Technician Performance Report</h4>
										<h6 class="card-subtitle">Detailed Performance Report</h5>
									</div>
									<div class="col-md-4">
										<button type="button" class="justify-content-center w-100 btn btn-rounded btn-outline-primary d-flex align-items-center"><i class="fas fa-download fill-white me-2"></i>Download</button>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-md-12">
										<ul class="list-style-none mt-4">
											@foreach ($allPerformers as $performer)
											<li class="mb-4">
												<div class="d-flex align-items-center">
													<div>
														<h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
													</div>
													<div class="ms-auto">
														<h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
													</div>
												</div>
												<div class="progress mt-1">
													@if ($performer->percentage_completed >= 50)
													<div class="progress-bar bg-success" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
													@elseif ($performer->percentage_completed >= 30 && $performer->percentage_completed < 50)
													<div class="progress-bar bg-warning" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
													@else
													<div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
													@endif
												</div>
											</li>
											@endforeach
										</ul>
									</div>
 								</div>
                            </div>
                        </div>
 
					</div>
				
					
 
            </div>
			
			

        </div>





    </div>
</div>
</div>
</div>
</div>

@section('script')
<script src="{{ asset('public/admin/dist/js/app.min.js')}}"></script>
<script src="{{ asset('public/admin/dist/js/app.init.js')}}"></script>
<script src="{{ asset('public/admin/dist/js/app-style-switcher.js')}}"></script>
<script src="{{ asset('public/admin/dist/libs/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
    <script src="{{asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
    <script src="{{asset('public/admin/dist/js/pages/dashboards/dashboard6.js')}}"></script>

@endsection
@stop
