@extends('home')
@section('content')
<style>
    .paginate_laravel svg {
        width: 15px;
    }

    .paginate_laravel nav>div:first-child {
        display: none;
    }

    .paginate_laravel nav>div:nth-child(2) {
        display: flex;
        justify-content: space-between;
    }
</style>



<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title">Customers Data</h4>
        </div>
        
		<div class="row mt-2">
			<div class="col-md-12 col-xl-2">
				<div class="card card-body shadow">
					<div class="col-md-4 col-xl-2">
						<form>

							<input type="text" name="" class="form-control " aria-controls="" id="searchInput45"
								placeholder="Search Customers..." />
						</form>
					</div>
					{{-- <div
						class="col-md-8 col-xl-10 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">

						<a href="{{ route('users.create') }}" id="newCustomerBtn" class="btn btn-info"><i
								class="ri-user-add-line"></i> Add New Customer Data</a>

						@if(request()->routeIs('users.index'))
						<a href="{{ route('users.status', ['status' => 'deactive']) }}" class="btn btn-danger mx-3"><i
								class="ri-user-unfollow-fill"></i> View Inactive Customers</a>
						@elseif(request()->routeIs('users.status'))
						<a href="{{ route('users.index') }}" class="btn btn-success mx-3"><i
								class="ri-user-follow-line"></i> View
							Active Customers</a>
						@endif


					</div> --}}
				</div>
			</div>
		</div>
		
         <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->


        @if (Session::has('success'))
        <div class="alert_wrap">
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                <strong>Done.</strong> {{ Session::get('success') }} <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Close"></button>
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

                <div class="card card-body shadow">
                    <div class="table-responsive table-custom">

                        <table id="usersTable" class="table table-hover table-striped search-table v-middle"
                            data-paging="true">
                            <thead class="header-item">
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Address</th>
                                <th>Number of Visits</th>
                                <th>Job Completed</th>
                                <th>Issue Resolved</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <!-- Loop through each user -->
                                @foreach ($users as $user)
                                <tr class="search-items">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <div class="user-meta-info">
                                                    <a href="{{ route('customersdata.show', $user->userdata1->id) }}">
                                                        <h6 class="user-name mb-0" data-name="name">{{
                                                            $user->userdata1->name }}
                                                        </h6>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="user-work">{{ $user->userdata1->mobile }}</span><br />
                                        <span class="user-work">{{ $user->userdata1->email }}</span>
                                    </td>
                                    <td>
                                        <div style="display:flex;">
                                            @php
                                            $userAddress = DB::table('user_address')
                                            ->leftJoin('location_states', 'user_address.state_id', '=',
                                            'location_states.state_id')
                                            ->where('user_id', $user->userdata1->id)
                                            ->first();
                                            $userAddresscity = DB::table('user_address')
                                            ->leftJoin('location_cities', 'user_address.city_id', '=',
                                            'location_cities.city_id')
                                            ->where('user_address.user_id', $user->userdata1->id)
                                            ->value('location_cities.city');
                                            @endphp

                                            @if (isset($userAddress) && $userAddress)
                                            <span class="user-work">
                                                @if(isset($userAddress->city) && $userAddress->city !== '')
                                                {{ $userAddress->city }},
                                                @endif

                                                @if(isset($userAddress->state_code) && $userAddress->state_code !==
                                                '')
                                                {{ $userAddress->state_code }},
                                                @endif

                                                @if(isset($userAddress->zipcode) && $userAddress->zipcode !== '')
                                                {{ $userAddress->zipcode }}
                                                @endif
                                            </span>
                                            @else
                                            <span class="user-work">N/A</span>
                                            @endif
                                            <br />
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{ $user->no_of_visits ?? '' }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $user->job_completed ?? '' }}</span>
                                    </td>
                                    <td>{{ $user->issue_resolved ?? '' }}</td>
                                    <td class="action footable-last-visible" style="display: table-cell;">
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('customersdata.show', $user->userdata1->id) }}"><i
                                                        data-feather="eye" class="feather-sm me-2"></i> View</a>
                                                <!--   <a class="dropdown-item" href="{{ route('customersdata.show', $user->userdata1->id) }}"><i
                                                                            data-feather="edit-2" class="feather-sm me-2"></i> Edit</a> -->
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                {{-- model status active in active --}}
                                @endforeach
                            </tbody>

                        </table>
                        <!-- Pagination Links -->
                        <div class="justify-content-center mt-3 paginate_laravel">
                            {{-- {{$user->usedata->id->withQueryString()->links('pagination::bootstrap-5')}} --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- -------------------------------------------------------------- -->
            <!-- End PAge Content -->
            <!-- -------------------------------------------------------------- -->
        </div>

        <!-- Modal for adding comment -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentModalLabel">Add Comment
                        </h5>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
    </div>




    @section('script')
    <script>
        $(document).ready(function() {
                $('#searchInput').keyup(function() {
                // Get the search query from the input field
                var query = $(this).val();

                // Perform AJAX request to fetch search results
                $.ajax({
                    url: "{{ route('users.search') }}",
                    method: "GET",
                    data: { search: query },
                    success: function(response) {
                        $('tbody').html(response.tbody);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });





                $('.deletelink').on('click', function() {
                    var questionId = $(this).data('question-id');

                    $.ajax({
                        url: '/delete/question/' + questionId,
                        type: 'get',
                        dataType: 'json',
                        success: function(data) {
                            $(this).closest('tr').remove();
                            alert(data.success);
                        },
                        error: function(xhr, status, error) {
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
                        const message = (data.status === 'active') ? 'Are you sure to change status to Inactive?' :
                            'Are you sure to change status to Active?';
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

    <script>
        function setUserIdModal2(userId, isUpdated) {
    // Set the user ID
    document.getElementById('userIdModal2').value = userId;

    // Set the checkbox state based on is_updated status
    var checkbox = document.getElementById('statusCheckboxModal2');
    checkbox.checked = isUpdated === 'yes';
}
    function updateIsUpdated(checkbox) {
        // Get the hidden input field
        var hiddenInput = document.getElementById('isUpdatedHidden');

        // Update the value based on checkbox state
        hiddenInput.value = checkbox.checked ? 'yes' : 'no';
    }
    </script>


    @endsection

    @endsection