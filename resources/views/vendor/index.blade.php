@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
    @endif

    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->



    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">Vendors</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Other</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-3 align-self-center">
                <a href="{{ route('vendor.create') }}" class="btn btn-info float-end">
                    <i class="ri-device-line"> </i>
                    Add New Vendor</a>
            </div>
        </div>
    </div>
    @if (Session::has('success'))
        <div class="alert_wrap">
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="container-fluid pt-2">
        <div class="row card card-border shadow">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <table id="zero_config" class="table table-hover table-striped text-nowrap" data-paging="true"
                            data-paging-size="7">

                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>Location</th>
                                    <th>Status </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($vendor as $item)
                                    <tr>
                                        <td>{{ $item->vendor_name ?? null }} </td>
                                        <td>
                                            {{ $item->address_line_1 ?? null }} {{ $item->address_line_2 ?? null }},
                                            {{ $item->state ?? null }}, {{ $item->zipcode_id ?? null }}

                                        </td>
                                        <td class="ucfirst">
                                            @if ($item->is_active == 'yes')
                                                Active
                                            @else
                                                Deactive
                                            @endif
                                        </td>

                                        <td class="action footable-last-visible" style="display: table-cell;">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-light-primary text-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ url('vendor/edit/' . $item->vendor_id) }}"><i
                                                            data-feather="edit" class="feather-sm me-2"></i>
                                                        Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('/vendor/delete/' . $item->vendor_id) }}"><i
                                                            data-feather="trash" class="feather-sm me-2"></i>
                                                        Delete</a>

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

    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
