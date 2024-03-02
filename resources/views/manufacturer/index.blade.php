@extends('home')
@section('content')
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display: inline;">
    <!-- ------------------------------------------------- -->


    <!-- Bread crumb and right sidebar toggle -->

    <!-- -------------------------------------------------------------- -->


    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->



        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Manufacture</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Setting</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Manufacturer List</li>

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
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <!-- ------------------------------------------------------------ -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="container-fluid">

            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4 col-xl-2">
                        <form>
                            <input type="text" class="form-control product-search" id="input-search"
                                placeholder="Search Manufacturer..." />
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
                        <a href="{{ route('manufacturer.create') }}" class="btn btn-info">
                            <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                            Add Manufacturer</a>
                    </div>


                    <!-- Add Popup Model  1 latest -->
                    <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="myModalLabel">Add New Manufacturer</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        fdprocessedid="k7jfjv"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="https://gaffis.in/frontier/website/book-list/productcategory-store"
                                        method="post" class="form-horizontal form-material"
                                        enctype="multipart/form-data">
                                        <input type="hidden" name="_token"
                                            value="Cqsz3INQEz3Md4hqmIFyhdOa9CKzApWEguNsBNME" autocomplete="off">
                                        <div class="form-group">
                                            <div class="col-md-12 mb-3"> <input type="text" class="form-control"
                                                    name="category_name" placeholder="Category Name" />
                                            </div>


                                            <div class="col-md-12 mb-3">
                                                <div class="
                                    fileupload
                                    btn btn-danger btn-rounded
                                    waves-effect waves-light
                                    btn-sm
                                  ">
                                                    <span><i class="ri-upload-line align-middle me-1 m-r-5"></i>Upload
                                                        Manufacturer Image</span>
                                                    <input type="file" name="category_image" class="upload" />
                                                </div>
                                            </div>
                                        </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                        Save
                                    </button>
                                    <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
                                        Cancel
                                    </button>

                                </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- End Popup Model 1 latest-->

                </div>
            </div>


            <!-- Row -->
            <style>
                .srdrop {
                    float: right;
                }

                .card {
                    min-height: 100%;
                }

                .card-img-top {
                    aspect-ratio: 5/2;
                    object-fit: contain;
                    padding-block: 8px;
                }
            </style>

            <div class="row">

                <!-- column -->
                @foreach ($manufacture as $item)
                <div class="col-lg-3 col-md-6 col-xl-2">
                    <!-- Card -->
                    <div class="card">


                        <a href="#" class="card-link">
                            @if ($item->manufacturer_image)
                            <img class="card-img-top img-responsive"
                                src="{{ asset('public/images/' . $item->manufacturer_image) }}" alt="Card image cap" />
                            @else
                            <img class="card-img-top img-responsive"
                                src="{{ asset('public/images/1703141665_heating-air-conditioning.jpg') }}"
                                alt="Default Image" />
                            @endif
                        </a>



                        <div class="card-body">
                            <h6 class="card-title">{{ $item->manufacturer_name }}<div
                                    class="dropdown dropstart srdrop ">

                                    <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                        aria-expanded="false">

                                        <i data-feather="more-vertical" class="feather-sm"></i>

                                    </a>

                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                        <li><a class="dropdown-item viewinfo"
                                                href="{{ route('manufacturer.edit', ['id' => $item->id]) }}">Edit</a>
                                        </li>
                                        @if ($item->is_active == 'no')
                                        <li><a class="dropdown-item viewinfo"
                                                href="{{ url('/setting/manufacturer-enable', ['id' => $item->id]) }}">Enable</a>
                                        </li>
                                        @else
                                        <li><a class="dropdown-item viewinfo"
                                                href="{{ url('/setting/manufacturer-disable', ['id' => $item->id]) }}">Disable</a>
                                        </li>
                                        @endif




                                    </ul>

                                </div>
                            </h6>

                        </div>


                    </div>

                </div>
                @endforeach



                <!-- column -->
                <!-- column -->

            </div>
            <!-- Row -->








            <!-- -------------------------------------------------------------- -->
            <!-- Recent comment and chats -->
            <!-- -------------------------------------------------------------- -->

        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Recent comment and chats -->
        <!-- -------------------------------------------------------------- -->
    </div>
</div>
@endsection
@section('script')
@endsection