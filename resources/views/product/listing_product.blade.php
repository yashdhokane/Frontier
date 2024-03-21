@extends('home')
@section('content')

    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->

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
        <div class="show-categories">

            <div class="container-fluid">

                <div class="card card-body">
                    <div class="row">
                        <div class="col-md-4 col-xl-2">
                            <h4 class="page-title">Parts</h4>
                        </div>
                        <div
                            class="col-md-8 col-xl-10 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                            <a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#add-contact"><i class=" fas fa-user-plus "></i> New Category</a>
                        </div>

                        <!-- Add Popup Model  1 latest -->
                        <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header d-flex align-items-center">
                                        <h4 class="modal-title" id="myModalLabel">New Category</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                            fdprocessedid="k7jfjv"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('productcategory.store') }}" method="post"
                                            class="form-horizontal form-material" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <div class="col-md-12 mb-3"> <input type="text" class="form-control"
                                                        name="category_name" placeholder="Category Name" required />
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

                    </div>
                </div>


                <div class="row">

                    <!-- column -->
                    @foreach ($productcategory as $item)
                        <div class="col-lg-3 col-md-6 col-xl-2">
                            <!-- Card -->

                            <div class="card">


                                <a href="">

                                    @if ($item->category_image)
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('public/images/parts/'.$item->id.'/' . $item->category_image) }}"
                                            style="width:228.5px; height:200px;" alt="Card image cap" />
                                    @else
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('public/images/Noimage.png') }}"
                                            style="width:228.5px; height:200px;" alt="Default Image" />
                                    @endif
                                </a>



                                <div class="card-body">
                                    <h6 class="card-title">{{ $item->category_name }} <div
                                            class="dropdown dropstart srdrop ">

                                            <a href="#" class="link" id="dropdownMenuButton"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i data-feather="more-vertical" class="feather-sm"></i>

                                            </a>

                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                                <li><a class="dropdown-item viewinfo" href="javascript:void(0)"
                                                        data-bs-toggle="modal" data-bs-target="#add-contact1"
                                                        id="{{ $item->id }}">Edit</a>
                                                </li>

                                                <li>
                                                    <form method="post"
                                                        action="{{ route('productcategory.delete', ['id' => $item->id]) }}">
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



                    <!-- Card -->

                    <!-- edit Popup Model  1 -->
                    <div id="add-contact1" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header d-flex align-items-center">
                                    <h4 class="modal-title" id="myModalLabel">Edit Parts Category</h4>
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


            </div>

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
                url: '{{ route('editproduct') }}',
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
@stop
@stop
