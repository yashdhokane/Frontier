    @extends('home')

    @section('content')



<style>
.dt-search {
    margin-left: 1200px !important;
}
</style>

        <div class="page-wrapper" style="display:inline;">
            <!-- -------------------------------------------------------------- -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
           <div class="page-breadcrumb" style="">
        <div class="row">
            <div class="col-4 align-self-center">
                <h4 class="page-title">Fleet Report</h4>
  <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Fleet</li>
                        </ol>
                    </nav>
                </div>

            </div>
                                 @include('header-top-nav.report_nav')

        </div>
    </div>
    <!-- -----
            <!-- -------------------------------------------------------------- -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
            <!-- -------------------------------------------------------------- -->
            <!-- Container fluid  -->
            <!-- -------------------------------------------------------------- -->
            <div class="container-fluid">
                <div class="row">



                    <div class="container">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="card shadow card-border">
                                    <div class="card-body">
                                        <h5 class="card-title uppercase">Technician Fleet and Vehicle Report</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">

                                                    @if (!empty($fleetKeys) && count($fleetKeys) > 0)
                                                        <table class="table" id="zero_configss">
                                                            <thead>
                                                                <tr>
                                                                    <th>Technician Name</th>
                                                                    @foreach ($fleetKeys as $fleetKey)
                                                                        <th>
                                                                            @if ($fleetKey == 'oil_change')
                                                                                Oil Change
                                                                            @elseif ($fleetKey == 'tune_up')
                                                                                Tune Up
                                                                            @elseif ($fleetKey == 'tire_rotation')
                                                                                Tire Rotation
                                                                            @elseif ($fleetKey == 'breaks')
                                                                                Breaks
                                                                            @elseif ($fleetKey == 'inspection_codes')
                                                                                Inspection Codes
                                                                            @elseif ($fleetKey == 'mileage')
                                                                                Mileage
                                                                            @elseif ($fleetKey == 'registration_expiration_date')
                                                                                Registration Expiration Date
                                                                            @elseif ($fleetKey == 'vehicle_coverage')
                                                                                Vehicle Coverage
                                                                            @elseif ($fleetKey == 'license_plate')
                                                                                License Plate
                                                                            @elseif ($fleetKey == 'vin_number')
                                                                                Vin Number
                                                                            @elseif ($fleetKey == 'make')
                                                                                Make
                                                                            @elseif ($fleetKey == 'model')
                                                                                Model
                                                                            @elseif ($fleetKey == 'year')
                                                                                Year
                                                                            @elseif ($fleetKey == 'color')
                                                                                Color
                                                                            @elseif ($fleetKey == 'vehicle_weight')
                                                                                Vehicle Weight
                                                                            @elseif ($fleetKey == 'vehicle_cost')
                                                                                Vehicle Cost
                                                                            @elseif ($fleetKey == 'use_of_vehicle')
                                                                                Use Of Vehicle
                                                                            @elseif ($fleetKey == 'repair_services')
                                                                                Repair Services
                                                                            @elseif ($fleetKey == 'ezpass')
                                                                                Ezpass
                                                                            @elseif ($fleetKey == 'service')
                                                                                Service
                                                                            @elseif ($fleetKey == 'additional_service_notes')
                                                                                Additional Service Notes
                                                                            @elseif ($fleetKey == 'last_updated')
                                                                                Last Updated
                                                                            @elseif ($fleetKey == 'epa_certification')
                                                                                Epa Certification
                                                                            @endif
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($users as $user)
                                                                    <tr>
                                                                        <td>{{ $user->name }}</td>
                                                                        @if ($user->fleetDetails)
                                                                            @foreach ($fleetKeys as $fleetKey)
                                                                                <td>{{ $user->fleetDetails->where('fleet_key', $fleetKey)->first()->fleet_value ?? '' }}
                                                                                </td>
                                                                            @endforeach
                                                                        @else
                                                                            @foreach ($fleetKeys as $fleetKey)
                                                                                <td></td>
                                                                            @endforeach
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p>No fleet details available.</p>
                                                    @endif



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

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
               $('#zero_configss').DataTable({
            scrollX: true,
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
            dom: 'Bfrtip', // This adds the button container to show export buttons
            buttons: [
                'excel', // Excel export button
                'pdf' // PDF export button
            ]
        });
            });
        </script>



    @stop
