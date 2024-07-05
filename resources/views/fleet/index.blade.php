<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
<div class="container-fluid">

    <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Vehicles</h4>
            </div>

            <div class="col-7 text-end px-4">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="{{ route('product.index') }}"
                        class="btn {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : 'btn-light-info text-info' }}">Parts</a>
                    <a href="{{ route('tool.index') }}"
                        class="btn {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : 'btn-light-info text-info' }}">Tools</a>
                    <a href="{{ route('vehicles') }}"
                        class="btn {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : 'btn-light-info text-info' }}">Vehicles</a>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button"
                            class="btn {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Assign
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_product') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_tool') }}">Tools</a>

                        </div>
                    </div>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop2" type="button"
                            class="btn {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('product.createproduct') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('tool.createtool') }}">Tools</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('addvehicle') }}">Vehicles</a>
                        </div>
                    </div>
                    <a href="{{ route('partCategory') }}"
                        class="btn {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : 'btn-light-info text-info' }}">Categories</a>
                </div>
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
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="technician"
                                                class="control-label bold md5 col-form-label required-field">Technician</label>
                                            <select class="form-select" name="technician" id="technician_filter"
                                                data-placeholder="Choose a technician" tabindex="1">
                                                <option value="">All</option>
                                                @foreach ($technician as $tech)
                                                <option value="{{ $tech->name }}">{{ $tech->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="service"
                                                class="control-label bold md5 col-form-label required-field">Status</label>
                                            <select class="form-select" name="status" id="status_filter"
                                                data-placeholder="Choose a Status" tabindex="1">
                                                <option value="">All</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <tr>
                                    <th>ID</th>
                                    <th>Vehicle Name</th>
                                    <th>Vehicle No.</th>
                                    <th>Vehicle Description</th>
                                    <th>Technician Assigned</th>
                                    <th>Last Modified</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="vehicle_table_body">
                                @foreach ($vehicle as $item)
                                <tr data-technician="{{ $item->technician->name ?? '' }}"
                                    data-status="{{ $item->status }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($item->vehicle_image)
                                            <img src="{{ asset('public/vehicle_image/' . $item->vehicle_image) }}"
                                                alt="{{ $item->vehicle_name }}" class="rounded-circle" width="45" />
                                            @else
                                            <img src="{{ asset('public/images/default-part-image.png') }}"
                                                alt="{{ $item->vehicle_name }}" class="rounded-circle" width="45" />
                                            @endif
                                            <div class="ms-2">
                                                <div class="user-meta-info"><a
                                                        href="{{ route('fleet.fleetedit', ['id' => $item->vehicle_id]) }}">
                                                        <h6 class="user-name mb-0" data-name="name">{{
                                                            $item->vehicle_name ?? '' }}</h6>
                                                    </a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->vehicle_no ?? '' }}</td>
                                    <td>{{ Str::limit($item->vehicle_description, 50) ?? null }}{{
                                        strlen($item->vehicle_description) > 200
                                        ? '...' : '' }}</td>
                                    <td>{{ $item->technician->name ?? null }}</td>
                                    <td>{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('m-d-Y
                                        h:i:a') : null }}
                                    </td>
                                    <td>{{ $item->status ?? null }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const technicianFilter = document.getElementById('technician_filter');
                const statusFilter = document.getElementById('status_filter');
                const tableBody = document.getElementById('vehicle_table_body');

                function filterTable() {
                    const technician = technicianFilter.value.toLowerCase();
                    const status = statusFilter.value.toLowerCase();

                    Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                        const rowTechnician = row.getAttribute('data-technician').toLowerCase();
                        const rowStatus = row.getAttribute('data-status').toLowerCase();

                        const matchesTechnician = technician === '' || rowTechnician === technician;
                        const matchesStatus = status === '' || rowStatus === status;

                        if (matchesTechnician && matchesStatus) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                technicianFilter.addEventListener('change', filterTable);
                statusFilter.addEventListener('change', filterTable);

                // Call filterTable on page load to apply initial filter if any
                filterTable();
            });
        </script>
        @endsection
        @endsection
