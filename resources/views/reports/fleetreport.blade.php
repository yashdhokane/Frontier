@extends('home')

@section('content')

    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h3 class="page-title">Fleet Report</h3>
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

                    <div class="row">

                        <div class="col-md-12">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h4 class="card-title">Fleet report by technician</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">

                                                @if (!empty($fleetKeys) && count($fleetKeys) > 0)
                                                <table class="table" id="zero_configss">
                                                    <thead>
                                                        <tr>
                                                            <th>Technician Name</th>
                                                            @foreach($fleetKeys as $fleetKey)
                                                                <th>{{ $fleetKey }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($users as $user)
                                                            <tr>
                                                                <td>{{ $user->name }}</td>
                                                                @if ($user->fleetDetails)
                                                                    @foreach($fleetKeys as $fleetKey)
                                                                        <td>{{ $user->fleetDetails->where('fleet_key', $fleetKey)->first()->fleet_value ?? '' }}</td>
                                                                    @endforeach
                                                                @else
                                                                    @foreach($fleetKeys as $fleetKey)
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
                scrollX: true
            });
        });
    </script>
    
    

@stop
