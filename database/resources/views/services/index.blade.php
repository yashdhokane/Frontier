@extends('home')
@section('content')

    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->


        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Services</h4>
                </div>
                <div class="col-7 align-self-center">
                    <div class="d-flex no-block justify-content-end align-items-center">
                        <div class="me-2">
                            <a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#add-contact"><i class=" fas fa-user-plus "></i> New Category</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Popup Model  1 latest -->
        <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myModalLabel">Add Service Category</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            fdprocessedid="k7jfjv"></button>
                    </div>
                    <form action="{{ route('servicecategory.store') }}" method="post" class="form-horizontal form-material"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-md-12 mb-4">
                                    <label for="" class="my-2"> Category Name </label>
                                    <input type="text" class="form-control" name="category_name" placeholder=""
                                        required />
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="" class="my-2">Category Image </label>
                                        <input type="file" name="category_image" class="form-control" />
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

            <!-- Row -->
            <style>
                .srdrop {
                    float: right;
                }
            </style>

            <div class="row card-body">


                <!-- column -->
                @foreach ($servicecategory as $item)
                    <div class="col-lg-3 col-md-6 col-xl-2">
                        <!-- Card -->

                        <div class="card">

                            <a href="{{ route('services.listingServices', ['category_id' => $item->id]) }}">
                                @if ($item->category_image)
                                    <img class="card-img-top img-responsive"
                                        src="{{ asset('public/images/' . $item->category_image) }}" alt="Card image cap"
                                        style="width:228.5px; height:200px;" />
                                @else
                                    <img class="card-img-top img-responsive" src="{{ asset('public/images/Noimage.png') }}"
                                        style="width:228.5px; height:200px;" alt="Default Image" />
                                @endif
                            </a>
                            </a>
                            <div class="card-body">
                                <h6 class="card-title">{{ $item->category_name }} <div class="dropdown dropstart srdrop ">

                                        <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                            <i data-feather="more-vertical" class="feather-sm"></i>

                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                            <li><a class="dropdown-item viewinfo" href="javascript:void(0)"
                                                    data-bs-toggle="modal" data-bs-target="#add-contact1"
                                                    id="{{ $item->id }}">Edit</a>
                                            </li>

                                            <li>
                                                <form method="post"
                                                    action="{{ route('servicecategory.delete', ['id' => $item->id]) }}">
                                                    @csrf
                                                    @method('DELETE')


                                                    <button type="submit" class="dropdown-item"
                                                        style="border: none; background: none; cursor: pointer;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>

                                    </div>
                                </h6>

                            </div>


                        </div>
                    </div>
                @endforeach

            </div>


            <!-- Card -->

            <!-- edit Popup Model  1 -->
            <div id="add-contact1" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="myModalLabel">Edit Service Category</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>


                        <div class="modal-body" id="appendbody">

                        </div>

                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            </div>
            <!-- edit End Popup Model 1 -->




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
@section('script')
    <script>
        $(document).on('click', '.viewinfo', function() {
            $("#add-contact1").modal({
                backdrop: "static",
                keyboard: false,
            });
            var entry_id = $(this).attr('id');
            $("#appendbody").empty();
            $.ajax({
                url: '{{ route('getStoryDetails') }}',
                type: 'get',
                data: {
                    entry_id: entry_id

                },
                dataType: 'json',
                success: function(data) {
                    $("#appendbody").html(data);
                }
            });
        });
    </script>
    <script src="{{ asset('public/admin/dist/js/pages/contact/contact.js') }}"></script>
    <script src="{{ asset('public/admin/dist/js/feather.min.js') }}"></script>

    <script src="{{ asset('public/admin/dist/js/custom.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                // Trigger click event on the element with class .sidebartoggler
                $('.sidebartoggler').click();
            }); // Adjust the delay time as needed
        });
    </script>
@stop
@stop
