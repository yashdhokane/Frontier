@extends('home')
@section('content')


<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Dispatcher</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="https://gaffis.in/frontier/website/home">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dispatcher</li>
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
                            <input type="text" class="form-control product-search" id="input-search"
                                placeholder="Search Dispatcher..." />
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
                            <a href="" class="
                        delete-multiple
                        btn-light-danger btn
                        me-2
                        text-danger
                        d-flex
                        align-items-center
                        font-medium
                      ">
                                <i data-feather="trash-2" class="feather-sm fill-white me-1 deletelink"></i>
                                Delete All Row</a>
                        </div>
                        <a href="{{route('dispatcher.create')}}" id="" class="btn btn-info">
                            <i data-feather="users" class="feather-sm fill-white me-1"> </i>
                            Add Dispatcher</a>
                    </div>
                </div>
            </div>
            <!-- ---------------------
                        end Contact
                    ---------------- -->
            <!-- Modal -->


            <div class="card card-body">
                <div class="table-responsive" style="overflow-x: auto">
                    <table class="table search-table v-middle text-nowrap">
                        <thead class="header-item">

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
              <th>Location/Address</th>
              <th>Action</th>

                        </thead>
                        <tbody>
                            <!-- Loop through each user -->
                             @foreach($users as $user)
              <tr class="search-items">
                {{--
                <td>
                  <div class="n-chk align-self-center text-center">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input contact-chkbox primary"
                        id="checkbox{{ $user->id }}"> <!-- Assuming your user model has an 'id' field -->

                      <label class="form-check-label" for="checkbox{{ $user->id }}"></label>
                    </div>
                  </div>
                </td> --}}
                <td>
                  <div class="d-flex align-items-center">
                  @if($user->user_image)
                    <img src="{{ asset('public/images/dispatcher/' . $user->user_image) }}" alt="avatar" class="rounded-circle"
                      width="45" />
                    @else
                    <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar" class="rounded-circle" width="45" />


                    @endif

                    <div class="ms-2">
                      <div class="user-meta-info">
                        <a href="{{ route('users.show', $user->id) }}">
                          <h5 class="user-name mb-0" data-name="name"> {{ $user->name }}</h5>
                        </a>
                      {{-- <span class="user-work text-muted">{{ $user->email }}</span><br />
                        <span class="user-work text-muted">{{ $user->mobile }}</span><br /> --}}
                        <span class="badge bg-dark">{{ $user->status }}</span>
                      </div>
                    </div>
                  </div>
                </td>
  <td>
        <div class="row">
            <div class="col-sm-12 col-md-12">
               {{--   @if ($user->tags->isNotEmpty())
                   @foreach ($user->tags as $tag)
                        <div class="row">
                            <div class="col-12" style="margin-top:3px;">
                                <span class="badge bg-warning">{{ $tag->tag_name }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-12">
                            <span class="badge bg-warning">No Tags Available</span>
                        </div>
                    </div>
                @endif 
                --}}

                <span class="user-work text-muted">{{ $user->email }}</span><br />
                        <span class="user-work text-muted">{{ $user->mobile }}</span><br />
                
                <div class="row">
                    <div class="col-12">
                        <span class="badge bg-info"></span>
                    </div>
                </div>
            </div>
        </div>
    </td>

                <td>
            {{--       <span class="usr-location" data-location="{{ $user->homeAddress }}">{{ $user->homeAddress->city ??
                    'null'}}</span>  --}}
                    <div style="display:flex;">
                   @if($user)
  @php
    $userAddress = DB::table('user_address')
        ->leftJoin('location_states', 'user_address.state_id', '=', 'location_states.state_id')
        ->where('user_id', $user->id)
        ->first();
       $userAddresscity = DB::table('user_address')
    ->leftJoin('location_cities', 'user_address.city', '=', 'location_cities.city_id')
    ->where('user_address.user_id', $user->id)
    ->value('location_cities.city');


@endphp

    @if($userAddress)
         <span class="user-work text-muted">{{ $userAddresscity }}</span>&nbsp;

  
             <span class="user-work text-muted">{{ $userAddress->state_name }}</span>
      

         <span class="user-work text-muted">,</span>
         <span class="user-work text-muted">{{ $userAddress->zipcode }}</span>
    @else
         <span class="user-work text-muted">null</span>
    @endif
@else
     <span class="user-work text-muted">null</span>
@endif
<br />


                </div>
                </td>
     <td class="action footable-last-visible" style="display: table-cell;">
                                    <div class="action-btn" style="display:flex">
                                        <a href="{{ route('dispatcher.show', $user->id) }}" class="text-info edit">
                                            <span class="badge bg-info">
                                                <i data-feather="eye" class="feather-sm fill-white"></i> View
                                            </span>
                                        </a>
                                        <a href="{{ route('dispatcher.edit', $user->id) }}" class="text-info edit ms-2">
                                            <span class="badge bg-success">
                                                <i data-feather="eye" class="feather-sm fill-white"></i> Edit
                                            </span>
                                        </a>
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
    <!-- Share Modal -->
    <div class="modal fade" id="Sharemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form>
                    <div class="modal-header d-flex align-items-center">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <i class="ri-share-fill me-2 align-middle"></i> Share With
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <button type="button" class="btn btn-info">
                                <i class="ti-user text-white"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Enter Name Here"
                                aria-label="Username" />
                        </div>
                        <div class="row">
                            <div class="col-3 text-center">
                                <a href="#Whatsapp" class="text-success">
                                    <i class="display-6 ri-whatsapp-fill"></i><br /><span
                                        class="text-muted">Whatsapp</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Facebook" class="text-info">
                                    <i class="display-6 ri-facebook-fill"></i><br /><span
                                        class="text-muted">Facebook</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Instagram" class="text-danger">
                                    <i class="display-6 ri-instagram-fill"></i><br /><span
                                        class="text-muted">Instagram</span>
                                </a>
                            </div>
                            <div class="col-3 text-center">
                                <a href="#Skype" class="text-cyan">
                                    <i class="display-6 ri-skype-fill"></i><br /><span class="text-muted">Skype</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button> 
                        <button type="submit" class="btn btn-success">
                            <i class="ri-send-plane-fill align-middle"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @section('script')
    <script>
        $(document).ready(function () {
        $('.deletelink').on('click', function () {
            var userId = $(this).data('question-id');

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

            }
        );

    </script>

    @endsection
    @endsection