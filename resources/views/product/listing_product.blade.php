@if (Route::currentRouteName() != 'dash')
@extends('home')
@section('content')
@endif

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title">Parts Category</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Parts</a></li>
                        <li class="breadcrumb-item"><a href="#">Category</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        @include('header-top-nav.asset-nav')


    </div>
</div>
<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->

@if (Session::has('success'))
<div class="alert_wrap">
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
        {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
</div>
@endif

@if (Session::has('error'))
<div class="alert_wrap">
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
</div>
@endif


<div class="container-fluid pt-2">

    <div class="row">
        <!-- Add Popup Model  1 latest -->
        <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
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


    <div class="row">

        <!-- column -->
        @foreach ($productcategory as $item)
        <div class="col-lg-3 col-md-6 col-xl-2 ">
            <!-- Card -->

            <div class="card card-border shadow">


                <a href="">

                    @if ($item->category_image)
                    <img class="card-img-top img-responsive"
                        src="{{ asset('public/images/parts/' . $item->id . '/' . $item->category_image) }}"
                        style="width:228.5px; height:200px;" alt="Card image cap" />
                    @else
                    <img class="card-img-top img-responsive" src="{{ asset('public/images/Noimage.png') }}"
                        style="width:228.5px; height:200px;" alt="Default Image" />
                    @endif
                </a>



                <div class="card-body">
                    <h6 class="card-title">{{ $item->category_name }} <div class="dropdown dropstart srdrop ">

                            <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i data-feather="more-vertical" class="feather-sm"></i>

                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                <li><a class="dropdown-item viewinfo" href="javascript:void(0)" data-bs-toggle="modal"
                                        data-bs-target="#add-contact1" id="{{ $item->id }}">Edit</a>
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
        <div id="add-contact1" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
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
@if (Route::currentRouteName() != 'dash')
@stop
@endif