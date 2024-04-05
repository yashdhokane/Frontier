@extends('home')
@section('content')


<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb" style="padding-top: 0px;">
        <div class="row">
            <div class="col-6 align-self-center">
                <h4 class="page-title">Technicians</h4>
            </div>
            <div class="col-6 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">
                </div>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div style="width:98%; margin-left:5px;">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @endif

        @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif

    </div>
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

                            <input type="text" name="" class="form-control " aria-controls="" id="searchInput"
                                placeholder="Search Technicians..." />
                        </form>
                    </div>
                    <div
                        class="col-md-8 col-xl-10 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
                        <a href="{{route('technicians.create')}}" id="" class="btn btn-info"><i
                                class=" fas fa-user-plus "></i> New Technicians</a>
                    </div>
                </div>
            </div>
            <!-- ---------------------
                        end Contact
                    ---------------- -->
            <!-- Modal style="overflow-x: auto;" -->


            <div class="card card-body">
                <div class="table-responsive table-custom">
                    <table id="zero_config" class="table table-striped search-table v-middle " data-paging="true">
                        <thead class="header-item ft16">

                            {{-- <th>
                                <div class="n-chk align-self-center text-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input secondary"
                                            id="contact-check-all" />
                                        <label class="form-check-label" for="contact-check-all"></label>
                                        <span class="new-control-indicator"></span>
                                    </div>
                                </div>
                            </th> --}}
                            <th>Name</th>
                            <th>Contacts</th>
                            <th class="text-wrap">Service Area</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <!-- Loop through each user -->
                            @foreach($users as $user)
                            <tr class="search-items">
                                {{-- <td>
                                    <div class="n-chk align-self-center text-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input contact-chkbox primary"
                                                id="checkbox{{ $user->id }}">
                                            <!-- Assuming your user model has an 'id' field -->

                                            <label class="form-check-label" for="checkbox{{ $user->id }}"></label>
                                        </div>
                                    </div>
                                </td>
                                --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->user_image)
                                        <img src="{{ asset('public/images/Uploads/users/' . $user->id . '/' .  $user->user_image) }}"
                                            class="rounded-circle" width="45" />
                                        @else
                                        <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar"
                                            class="rounded-circle" width="45" />
                                        @endif

                                        <div class="ms-2">
                                            <div class="user-meta-info">
                                                <a href="{{ route('technicians.show', $user->id) }}">
                                                    <h5 class="user-name mb-0" data-name="name"> {{ $user->name }}</h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="user-work">{{ $user->email }}</span><br />
                                    <span class="user-work">{{ $user->mobile }}</span><br />
                                </td>
                                <td class="text-wrap">
                                    {{-- <span class="usr-location" data-location="{{ $user->location }}">{{
                                        $user->Location->city ??
                                        'null'}}</span> --}}
                                    {{-- {{ dd($user->serviceArea1) }} --}}
                                    @if(isset($user->area_name) && !empty($user->area_name))
                                    {{ $user->area_name }}
                                    @endif
                                </td>
                                <td><span
                                        class="mb-1 badge @if($user->status == 'deactive' ) { bg-danger } @else { bg-success } @endif">{{
                                        $user->status }}</span>
                                </td>



                                <td class="action footable-last-visible" style="display: table-cell;">

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light-primary text-primary dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ri-settings-3-fill align-middle fs-5"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('technicians.show', $user->id) }}"><i data-feather="eye"
                                                    class="feather-sm me-2"></i> View</a>
                                           <!-- <a class="dropdown-item"
                                                href="{{ route('technicians.edit', $user->id) }}"><i
                                                    data-feather="edit-2" class="feather-sm me-2"></i> Edit</a> -->
                                            <a class="dropdown-item activity" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#commentModal1"
                                                onclick="setUserId({{ $user->id }})">
                                                <i data-feather="activity" class="feather-sm me-2"></i> Status
                                            </a>

                                            <a class="dropdown-item add-comment" href="javascript:void(0)"
                                                data-bs-toggle="modal" data-bs-target="#commentModal"
                                                onclick="setUserIdForCommentModal('{{ $user->id }}')">
                                                <i data-feather="message-circle" class="feather-sm me-2"></i> Comments
                                            </a>
                                            <a class="dropdown-item" href="{{route('permissionindex')}}">
                                                <i data-feather="user-check" class="feather-sm me-2"></i>
                                                Permission
                                            </a>

                                        </div>
                                    </div>
                                    {{-- <div class="action-btn" style="display:flex">

                                        <a href="{{ route('technicians.show', $user->id) }}" class="text-info edit">
                                            <span class="badge bg-info">
                                                <i data-feather="eye" class="feather-sm fill-white"></i> View
                                            </span>
                                        </a>
                                        <a href="{{ route('technicians.edit', $user->id) }}"
                                            class="text-info edit ms-2">
                                            <span class="badge bg-success">
                                                <i data-feather="eye" class="feather-sm fill-white"></i> Edit
                                            </span>
                                        </a>--}}
                                        {{--
                                        <form method="POST" action="{{ route('technicians.destroy', $user->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="text-dark  ms-2"
                                                style="border: none; background: none; cursor: pointer;">
                                                <span class="badge bg-danger">
                                                    <i data-feather="trash-2" class="feather-sm fill-white"></i> Delete
                                                </span>
                                            </button>
                                        </form>
                                        --}}


                                    </div>
                                </td>
                            </tr>
                            {{-- model staus active in active--}}
                            <div class="modal fade" id="commentModal1" tabindex="-1" aria-labelledby="commentModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="commentModalLabel">Change Status</h5>
                                        </div>
                                        <!-- Comment form -->
                                        <form id="commentForm" action="{{ route('technicianstaus.update') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <input type="hidden" name="user_id" id="userId">
                                                    <span id="confirmationMessage"></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary"
                                                    id="confirmButton">Yes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">No</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>



                                {{-- end model--}}
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

    <!-- Modal for adding comment -->

    <!-- Modal for adding comment -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Comment
                    </h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    --}}
                </div>
                <!-- Comment form -->
                <form action="{{ route('techniciancomment.store') }}" method="POST">

                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comment">Comment:</label>
                            <input type="hidden" name="id" id="userIdForCommentModal">
                            <textarea class="form-control" id="comment" name="note" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    @section('script')
    <script>
        $(document).ready(function () {
    var table = $('#zero_config').DataTable();

    // Apply search on input change
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('.deletelink').on('click', function () {
        var questionId = $(this).data('question-id');

        $.ajax({
            url: '/delete/question/' + questionId,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                $(this).closest('tr').remove();
                alert(data.success);
            },
            error: function (xhr, status, error) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});
    </script>




    <script>
        function setUserId(userId) {
        $('#userId').val(userId);

        // Fetch the user's status using AJAX
        $.ajax({
            url: "{{ route('get.user.status') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                user_id: userId
            },
            dataType: 'json',
            success: function(data) {
                // Update the confirmation message based on the user's status
                const message = (data.status === 'active') ? 'Are you sure to change status to Inactive?' : 'Are you sure to change status to Active?';
                $('#confirmationMessage').text(message);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    </script>
    <script>
        // Function to set the user ID in the comment modal
    function setUserIdForCommentModal(userId) {
        document.getElementById('userIdForCommentModal').value = userId;
    }
    </script>
    @endsection
    @endsection