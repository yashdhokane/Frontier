@extends('home')
@section('content')
<style>
    .table-responsive .description-column {
        max-width: 200px;
        /* Adjust the maximum width as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">{{ \App\Models\ServiceCategory::find($category_id)->category_name ?? null }}</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('services.index')}}">Price Book</a>
                            </li>
                            <li class="breadcrumb-item"><a href="">Services</a></li>

                            @if($category_id)
                            <li class="breadcrumb-item">
                                {{ \App\Models\ServiceCategory::find($category_id)->category_name }}
                            </li>
                            @else
                            <li class="breadcrumb-item"><a href="">Category</a></li>
                            @endif
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">
                    <div class="me-2">
                        <div class="lastmonth"></div>
                    </div>
                    <div class="">
                        <small>LAST MONTH</small>
                        <h4 class="text-info mb-0 font-medium">$58,256</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}

    </div>
    @endif
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="widget-content searchable-container list">
            <!-- ---------------------
                        start Contact
                    ---------------- -->
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-xl-2">
                        <form>
                            <input type="text" class="form-control product-search" id="input-search"
                                placeholder="Search Service..." />
                        </form>
                    </div>
                    <div class="
                    col-md-8 col-xl-10
                    text-end
                    d-flex
                    justify-content-md-end justify-content-center
                    mt-3 mt-md-0
                  ">
                        <div class="action-btn show-btn" style="display: none">
                            <a href="javascript:void(0)" class="
                        delete-multiple
                        btn-light-danger btn
                        me-2
                        text-danger
                        d-flex
                        align-items-center
                        font-medium
                      ">
                                <i data-feather="trash-2" class="feather-sm fill-white me-1"></i>
                                Delete All Row</a>
                        </div>
                        <a href="{{ route('services.createServices') }}" id="btn-add-contact" class="btn btn-info">
                            <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                            Add New Service</a>
                    </div>
                </div>
            </div>
            <!-- ---------------------
                        end Contact
                    ---------------- -->

            <div class="card card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table search-table v-middle text-nowrap">
                        <thead class="header-item">

                            <th>Services</th>
                            <th>Description</th>
                            <th>Service Code</th>
                            <th>Base Pricing</th>
                            <th>Action</th>
                        </thead>

                        <tbody>
                            @foreach($service as $service)
                            <!-- start row -->
                            <tr class="search-items">

                                <td>
                                    <div class="d-flex align-items-center">

                                        <h6 class="user-name mb-0" data-name="Emma Adams">{{ $service->service_name ??
                                            null}}
                                        </h6>

                                    </div>
                                </td>
                                <td class="description-column">{{ $service->service_description ?? null }}</td>
                                <td>{{ $service->service_code ?? null }}

                                </td>
                                <td>
                                    {{ $service->service_cost ?? null }}
                                </td>
                                <td class="action footable-last-visible" style="display: table-cell;">
                                    <div class="action-btn" style="display:flex;">

                                        <a href="{{ route('services.edit', ['service_id' => $service->service_id]) }}"
                                            class="text-info edit ms-2"><span class="badge bg-success"><i
                                                    data-feather="eye" class="feather-sm fill-white"></i>
                                                Edit</span></a>
                                        <form method="POST"
                                            action="{{ route('services.delete', ['service_id' => $service->service_id]) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-dark  ms-2"
                                                style="border: none; background: none; cursor: pointer;">
                                                <span class="badge bg-danger">
                                                    <i data-feather="trash-2" class="feather-sm fill-white"></i> Delete
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- Share Modal -->
    <div class="modal fade" id="Sharemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header d-flex align-items-center">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="ri-share-fill me-2 align-middle"></i> Share With
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-info">
                                <i class="ti-user text-white"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Enter Name Here"
                                aria-label="Username" />
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <a href="#Whatsapp" class="text-success">
                                    <i class="display-6 ri-whatsapp-fill"></i><br /><span
                                        class="text-muted">Whatsapp</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Facebook" class="text-info">
                                    <i class="display-6 ri-facebook-fill"></i><br /><span
                                        class="text-muted">Facebook</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Instagram" class="text-danger">
                                    <i class="display-6 ri-instagram-fill"></i><br /><span
                                        class="text-muted">Instagram</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Skype" class="text-cyan">
                                    <i class="display-6 ri-skype-fill"></i><br /><span class="text-muted">Skype</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-send-plane-fill align-middle"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
</div>

@section('script')
<script src="{{ asset('public/admin/dist/js/pages/contact.js') }}"></script>

@stop
@stop
