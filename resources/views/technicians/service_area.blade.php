<style>
    .required-field::after {
        content: " *";
        color: red;
    }

    #autocomplete-results {
        position: absolute;
        background-color: #fff;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: calc(30% - 2px);
        /* Subtract border width from input width */
    }

    #autocomplete-results ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #autocomplete-results li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #autocomplete-results li:hover {
        background-color: #f0f0f0;
    }

    #autocomplete-results li:hover::before {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: -1;
    }

    /* Ensure hover effect covers the entire autocomplete result */
    #autocomplete-results li:hover::after {
        content: "";
        display: block;
        position: absolute;
    }
</style>


<form action="{{ route('technicians.updateservice', $technician->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="technician_id" value="{{ $technician->id }}">

    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
       
        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <style>
            .custom-alert {
                width: 98%;
                /* Adjust the width as needed */
                margin: 0 auto;
                /* Center the alert horizontally */
            }
        </style>
        <div class="custom-alert">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>


    </div>
  
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    
	<div class="container-custom">
         
		<h4 class="card-title">Service Area</h4>
        <div class="row">

			<div class="col-lg-6 d-flex align-items-stretch px-3">
				
				 
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-3">
 							@php
							if (isset($technician->service_areas) && !empty($technician->service_areas)) {
							$service_areas = explode(',', $technician->service_areas);
							} else {
							$service_areas = [];
							}
							@endphp
							<div class="form-check">
								@foreach($serviceAreas as $area)
								<div class="mb-2">
									<label class="form-check-label" for="service_areas{{ $area->area_id }}">
										<input class="form-check-input" type="checkbox"
											@if(in_array($area->area_id,
										$service_areas)) checked @endif
										id="service_areas{{ $area->area_id }}" name="service_areas[]"
										value="{{ $area->area_id }}">
										{{ $area->area_name }}
									</label>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				
             </div>

         </div>
    

		<div class="row">
			<div class="p-3">
				<div class="action-form">
					<div class="mb-3 mb-0 text-left">
						<button type="submit" class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
 					</div>
				</div>
			</div>
		</div>
		
		
	</div>


    <!-- End row -->




</form>
