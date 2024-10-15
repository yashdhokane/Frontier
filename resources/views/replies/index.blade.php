@extends('home')

@section('content')

    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">Replies</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Predefine Replies </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-3 align-self-center">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#samedata-modal"
                    data-bs-whatever="@mdo"><i class="fas fa-tag"></i> Add New</button>

                <div class="modal fade" id="samedata-modal" tabindex="-1" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="exampleModalLabel1">Add Replies</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <form method="POST" action="{{ url('/store/replies') }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="pt_title" class="control-label bold mb5">Title:</label>
                                        <input type="text" class="form-control" name="pt_title" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="pt_content" class="control-label bold mb5">Content:</label>
                                        <textarea class="form-control" name="pt_content" ></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger text-danger font-medium"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>

                            </form>
                        </div>
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
    <div class="container-fluid">

        <div class="row">
            <div class="col-9">
                <div class="card card-border shadow">
                    <div class="card-body">
                            <table id="default_order" class="table table-striped table-bordered display text-nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Active</th>
                                        <th>Date Added</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reply as $item)
                                        <tr>
                                            <td> {{ $item->pt_title }} </td>
                                            <td> {{ $item->pt_content }} </td>
                                            <td> {{ $item->pt_active }} </td>
                                            <td>{{ $convertDateToTimezone($item->pt_date_added) }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    data-bs-toggle="modal" data-bs-target="#samedata-modal2"
                                                    data-reply-id="{{ $item->pt_id  }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="{{ url('/delete/replies/' . $item->pt_id ) }}"
                                                    onclick="confirm('Are you sure you want to delete reply?')"><button
                                                        type="button" class="btn btn-danger btn-sm delete-btn">
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
                            <h4 class="modal-title" id="exampleModalLabel1">Edit Replies</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ url('/edit/replies') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tag1" class="control-label bold mb5">Title:</label>
                                    <input type="text" class="form-control" id="tag1" name="pt_title"
                                        value="" />
                                </div>
                                <div class="mb-3">
                                    <label for="pt_content" class="control-label bold mb5">Content:</label>
                                    <textarea class="form-control" name="pt_content" ></textarea>
                                </div>
                                <input type="hidden" id="pt_id" name="pt_id" value="">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger text-danger font-medium"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- tags update model end -->

            <div class="col-3">


                <div class="col-md-12 ">
                    <div class="card-body card card-border shadow">
                        <h4 class="card-title">Why use predefine replies?</h4>
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
                            <h3 class="card-title mt-3 mb-0">00</h3>
                            <p class="card-text text-white-50" style="color: white !important;">Total Replies</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>




    </div>
@section('script')
    <script>
        if ($.fn.DataTable.isDataTable('#default_order')) {
            $('#default_order').DataTable().destroy();
        }
        $('#default_order').DataTable({
            "order": [
                [0, "desc"]
            ],
            "pageLength": 25,
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
