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
    @method('PUT')
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
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->


        <!-- row -->

        <div class="row">

           

           

            <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                        <h4 class="card-title mb-3">&nbsp;</h4>

                     <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-12 col-md-12" style="margin-top: 10px;">
                                <div class="mb-3">
                                    <label class="control-label col-form-label">Service Area</label><br>
                                    @php
                                    if (isset($technician->service_areas) && !empty($technician->service_areas)) {
                                    $service_areas = explode(',', $technician->service_areas);
                                    } else {
                                    $service_areas = [];
                                    }
                                    @endphp
                                    <div class="form-check">
                                        @foreach($serviceAreas as $area)
                                        <div>
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
            </div>


            <div class="row" style="display:none;">
                <div class="col-sm-12 col-md-12">
                    <div class="mb-3">
                        <label for="role" class="control-label col-form-label">Role</label>
                        <input type="text" class="form-control" id="role" value="{{ old('role', $technician->role) }}"
                            name="role" placeholder="" value="technician" />
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- row -->
 


    <!-- End row -->

    <!-- row -->

    <!-- End row -->

    <!-- row -->

    <div class="row">
        <div class="p-3 border-top">
            <div class="action-form">
                <div class="mb-3 mb-0 text-center">
                    <button type="submit" class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
                    <a href="{{ route('technicians.index') }}"> <button type="button"
                            class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button> </a>
                </div>
            </div>
        </div>
    </div>


    <!-- End row -->




</form>
