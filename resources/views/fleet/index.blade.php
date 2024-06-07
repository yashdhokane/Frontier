<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">

        <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Vehicles</h4>
                </div>
                <div class="col-7 text-end">
                    <a href="{{ route('addvehicle') }}" id="btn-show-categories" class="btn btn-primary mx-3"><i
                            class="fas fa-plus "></i> New Vehicle</a>

                </div>

            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        @if (Session::has('success'))
            <div class="alert_wrap">
                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                    {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body card-border shadow ">

                        <div class="table-responsive table-custom">
                            <table id="zero_config00" class="table table-hover table-striped text-nowrap"
                                style="overflow-x: auto; overflow-y: auto;" data-paging="true" data-paging-size="7">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Vehicle Summary</th>
                                        <th>Technician</th>
                                        <th>Last Modified</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehicle as $item)
                                        <tr>
                                            <td>{{ Str::limit($item->vehicle_description, 50) ?? null }}{{ strlen($item->vehicle_description) > 200 ? '...' : '' }}
                                            </td>
                                            <td>{{ Str::limit($item->vehicle_summary, 50) ?? null }}{{ strlen($item->vehicle_summary) > 200 ? '...' : '' }}
                                            </td>
                                            <td>{{ $item->technician->name ?? null }}</td>
                                            <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('m-d-Y h:i:a') : null }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-light-primary text-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if ($item->status == 'active')
                                                            <a class="dropdown-item"
                                                                href="{{ url('inactive/fleet/' . $item->vehicle_id) }}"><i
                                                                    data-feather="edit-2" class="feather-sm me-2"></i>
                                                                Inactive</a>
                                                        @else
                                                            <a class="dropdown-item"
                                                                href="{{ url('active/fleet/' . $item->vehicle_id) }}"><i
                                                                    data-feather="edit-2" class="feather-sm me-2"></i>
                                                                Active</a>
                                                        @endif
                                                        @if ($item->vehicle_id)
                                                            <a href="{{ route('fleet.fleetedit', ['id' => $item->vehicle_id]) }}"
                                                                class="text-dark pe-2 dropdown-item">
                                                                <i class="fa fa-edit edit-sm fill-white pe-2"></i> Edit
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        @section('script')
            <script>
                $('#zero_config00').DataTable({
                    "order": [
                        [0, "desc"]
                    ],
                    "pageLength": 25,
                });
            </script>
        @endsection
    @endsection
