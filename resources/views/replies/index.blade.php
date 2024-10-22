@extends('home')

@section('content')
    <style>
        .table-fixed {
            table-layout: fixed;
            width: 100%;
        }

        .table-fixed th:nth-child(1),
        .table-fixed td:nth-child(1),
        .table-fixed th:nth-child(2),
        .table-fixed td:nth-child(2) {
            width: 100px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>

    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">Predefine Reply</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Predefine Reply </li>
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
                                <h4 class="modal-title" id="exampleModalLabel1">Add Predefine Reply</h4>
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
                                        <textarea class="form-control" name="pt_content" cols="3" rows="5"></textarea>
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
        <div class="alert alert-success mx-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="container-fluid">

        <div class="row">
            <div class="col-9">
                <div class="card card-border shadow">
                    <div class="card-body">
                        <table id="default_order"
                            class="table table-striped table-bordered display text-nowrap table-fixed">
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
                                        <td>{{ $item->pt_title }}</td>
                                        <td>{{ $item->pt_content }}</td>
                                        <td>{{ $item->pt_active }}</td>
                                        <td>{{ $convertDateToTimezone($item->pt_date_added) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#samedata-modal2"
                                                data-reply-id="{{ $item->pt_id }}" data-title="{{ $item->pt_title }}"
                                                data-content="{{ $item->pt_content }}"
                                                data-active="{{ $item->pt_active }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ url('/delete/replies/' . $item->pt_id) }}"
                                                onclick="return confirm('Are you sure you want to delete reply?')">
                                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </a>
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
                            <h4 class="modal-title" id="exampleModalLabel1">Edit Predefine Reply</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ url('/edit/replies') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tag1" class="control-label bold mb5">Title:</label>
                                    <input type="text" class="form-control" id="tag1" name="pt_title"
                                        value="" id="title" />
                                </div>
                                <div class="mb-3">
                                    <label for="pt_content" class="control-label bold mb5">Content:</label>
                                    <textarea class="form-control" name="pt_content" id="content" cols="3" rows="5"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="active" class="control-label bold mb5">Active:</label>
                                    <select name="active" id="active" class="form-control">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <input type="hidden" id="pt_id" name="pt_id" value="" id="pt_id">

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


        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click', '.edit-btn', function() {
                // Get data attributes from the clicked button
                var id = $(this).data('reply-id');
                var title = $(this).data('title');
                var content = $(this).data('content');
                var active = $(this).data('active');

                // Set the values in the form inputs
                $('#pt_id').val(id); // Set hidden input field
                $('#tag1').val(title); // Set title input field
                $('#content').val(content); // Set content textarea

                // Set the selected value for the "Active" dropdown
                $('#active').val(active).change(); // Dynamically set the selected value
            });
        });
    </script>
@endsection
@endsection
