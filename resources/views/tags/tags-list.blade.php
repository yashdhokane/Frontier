@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-4 align-self-center">
                <h4 class="page-title">Tags</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tags </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-8 text-end px-4">
              @include('header-top-nav.settings-nav') 
            </div>
        </div>
    </div>
    @if (session('success'))
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
    <div class="container-fluid pt-2">

        <div class="row">
            <div class="col-9">
                <div class="card card-border shadow">
                    <div class="card-body">
                        <table id="default_order" class="table table-striped table-bordered display text-nowrap"
                            style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Tag</th>
                                    <th>Added By</th>
                                    <th>Date Added</th>
                                    <th>Date Modified</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tagsList as $tag)
                                    <tr>
                                        <td>
                                            <p class="tag_name">{{ $tag->tag_name }}</p>
                                        </td>
                                        <td>{{ optional($tag->addedByUser)->name }}</td>
                                        <td>{{ $tag->created_at->format('m-d-Y') }}</td>
                                        <td>{{ $convertDateToTimezone($tag->updated_at) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-secondary btn-sm edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#samedata-modal2"
                                                data-tag-id="{{ $tag->tag_id }}" data-tag-name="{{ $tag->tag_name }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ url('/delete/tags/' . $tag->tag_id) }}"
                                                onclick="confirm('Are you sure you want to delete tag?')"><button
                                                    type="button" class="btn btn-secondary btn-sm delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button></a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="samedata-modal2" tabindex="-1" aria-labelledby="exampleModalLabel1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="exampleModalLabel1">Edit Tags</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ url('/edit/tags') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tag1" class="control-label bold mb5">Tags:</label>
                                    <input type="text" class="form-control" id="tag1" name="tag_name"
                                        value="" />
                                </div>
                                <input type="hidden" id="tag-id" name="tag_id" value="">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary font-medium"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-secondary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- tags update model end -->

            <div class="col-3">

                <button type="button" class="btn btn-secondary py-1 mb-4" data-bs-toggle="modal"
                    data-bs-target="#samedata-modal" data-bs-whatever="@mdo"><i class="fas fa-tag"></i> Add New</button>

                <div class="col-md-12 ">
                    <div class="card-body card card-border shadow">
                        <h4 class="card-title">Why use tags?</h4>
                        <p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt minim veniam</p>
                        <p class="card-text pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                        <a href="#" class="card-link">Need more help?</a>
                    </div>
                </div>
                <hr />
                <div class="col-md-12 ">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <span>
                                <i class="fas fa-tag fill-white"></i>
                            </span>
                            <h3 class="card-title mt-3 mb-0">213</h3>
                            <p class="card-text text-white-50" style="color: white !important;">Total Tags</p>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="exampleModalLabel1">Add Tag</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <form method="POST" action="{{ url('/store/tags') }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="tags" class="control-label bold mb5">Tag:</label>
                                        <input type="text" class="form-control" name="tags" />
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary font-medium"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-secondary">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>




            </div>
        </div>


    </div>
@section('script')
    <script>
        if ($.fn.DataTable.isDataTable('#zero_default_orderconfig')) {
            $('#default_order').DataTable().destroy();
        }

        var table = $('#default_order').DataTable({
            "dom": '<"top"f>rt<"bottom d-flex justify-content-between mt-4"lp><"clear">',
            "paging": true,
            "info": false,
            "pageLength": 50, // Set default pagination length to 50
             "language": {
                            "search": "",
                            "searchPlaceholder": "search"
                        }
        });
        // for add tag query
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('saveTagsBtn').addEventListener('click', function() {
                var Tags = document.getElementById('tags').value;
                // alert('1234');
                $.ajax({
                    url: '{{ route('tags-add') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tags: Tags
                    },
                    success: function(response) {
                        // console.log('Success:', response);
                        $('#samedata-modal').modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });

        // for edit model open and fetch
        let row;
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.edit-btn', function() {
                var tagId = $(this).data('tag-id');
                var tagName = $(this).data('tag-name');
                row = $(this).closest('tr');
                // alert(tagId);

                $('#tag-id').val(tagId);
                $('#tag1').val(tagName);
            });


        });
    </script>
@endsection
@endsection
