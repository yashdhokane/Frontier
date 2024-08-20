@extends('home')
@section('content')

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
                <h4 class="page-title">Estimate templates</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Estimate templates</a></li>
                            <li class="breadcrumb-item"><a href="">Estimate templates</a></li>

                        </ol>
                    </nav>
                </div>
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

@if (Session::has('error'))
<div class="alert_wrap">
    <div class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
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
       placeholder="Search Estimate..." />
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
                    <a href="javascript:void(0)" id="btn-add-contact" class="btn btn-info" data-bs-toggle="modal"
                        data-bs-target="#add-contact">
                        <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                        Add Estimate</a>
                </div>

                <!-- Add Popup Model  1 latest -->
                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myModalLabel">Add New Estimate Category</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    fdprocessedid="k7jfjv"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('estimatecategory.store') }}" method="post"
                                    class="form-horizontal form-material" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-md-12 mb-3"> <input type="text" class="form-control"
                                                name="category_name" placeholder="Category Name"  required/>
                                        </div>


                                        <div class="col-md-12 mb-3">
                                            <div class="
                                    fileupload
                                    btn btn-danger btn-rounded
                                    waves-effect waves-light
                                    btn-sm
                                  ">
                                                <span><i class="ri-upload-line align-middle me-1 m-r-5"></i>Upload
                                                    Category Image</span>
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
        </style>

        <div class="row"  id="card-row">

            <!-- column -->
            @foreach ($estimatecategory as $item)
            <div class="col-lg-3 col-md-6 col-xl-2 card-item">
                <!-- Card -->

                <div class="card">
                    <a href="{{ route('estimate.listingestimate', ['category_id' => $item->id]) }}">
                        @if ($item->category_image && file_exists(public_path('images/' . $item->category_image)))
                        <img class="card-img-top img-responsive" src="{{ asset('public/images/' . $item->category_image) }}"
                          style="width:228.5px; height:200px;"  alt="Card image cap" />
                        @else
                        <!-- Use a default image if $item->category_image doesn't exist or is not available -->
                        <img class="card-img-top img-responsive" src="{{ asset('public/images/Noimage.png') }}" style="width:228.5px; height:200px;"
                            alt="Default Image" />
                        @endif
                    </a>

                    <div class="card-body">
                        <h6 class="card-title">{{ $item->category_name }} <div class="dropdown dropstart srdrop ">

                                <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                    <i data-feather="more-vertical" class="feather-sm"></i>

                                </a>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                    <li><a class="dropdown-item viewinfo" href="javascript:void(0)"
                                            data-bs-toggle="modal" data-bs-target="#add-contact2"
                                            id="{{ $item->id }}">Edit</a>
                                    </li>
                                    {{-- {{ $item->id }} --}}

                                    <li>
                                        <form method="post"
                                            action="{{ route('estimatecategory.delete', ['id' => $item->id]) }}">
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
            <div id="add-contact2" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="myModalLabel">Edit Estimate Category</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>


                        <div class="modal-body" id="appendbody1">

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
    $(document).on('click', '.viewinfo', function() { $("#add-contact2").modal({
    backdrop: "static",
    keyboard: false,
    });
    var entry_id = $(this).attr('id');
    // console.log(entry_id);
    $("#appendbody1").empty();
    $.ajax({
   url: '{{ route("estimateDetails") }}',
    type: 'get',
    data: {
    entry_id: entry_id

    },
    dataType: 'json',
    success: function(data) {
    $("#appendbody1").html(data);
    }
    });
    });

     document.getElementById('input-search').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('#card-row .card-item');
        let firstMatch = null;

        cards.forEach(card => {
            const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();

            if (cardTitle.includes(searchTerm)) {
                card.style.display = '';
                if (!firstMatch) {
                    firstMatch = card;
                }
            } else {
                card.style.display = 'none';
            }
        });

        // Scroll to the first matching card if found
        if (firstMatch) {
            firstMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>

@endsection
@endsection