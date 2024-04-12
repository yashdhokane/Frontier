<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">

        <div class="page-breadcrumb pb-2">
            <div class="row">
                <div class="col-5 align-self-center">

                    </h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('vehicles') }}" class="fs-5">Vehicles </a></li>

                            </ol>
                        </nav>
                    </div>
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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                            <table id="zero_config00" class="table table-bordered text-nowrap" data-paging="true"
                                data-paging-size="7">


                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Technician</th>
                                        <th>Last Modified</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($vehicle as $item)
                                        <tr>
                                            <td>{{ $item->vehicle_description ?? null }} </td>
                                            <td>{{ $item->technician->name ?? null }} </td>
                                            <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('m-d-Y h:i:a') : null }}
                                            </td>
                                            <td >
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
                                                        <a href="{{ url('vehicle/details/' . $item->vehicle_id) }}"
                                                            class="text-dark pe-2 dropdown-item">
                                                            <i class="fa fa-edit edit-sm fill-white pe-2"></i> Edit
                                                        </a>
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
    </div>
@section('script')
    <script>
        $('#zero_config00').DataTable();
    </script>

@endsection
@endsection
