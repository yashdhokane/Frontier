@if(auth()->check())
    {{-- User is authenticated --}}
    @php
        $userId = auth()->id(); // Get the authenticated user's ID
        $user = App\Models\User::find($userId); // Retrieve the authenticated user from the database
    @endphp

    {{-- Check if the user has an image --}}
    @if($user && $user->user_image)
        {{-- Display the user's image --}}
       <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  <img src="{{ asset('public/images/Uploads/users/' . $user->id . '/' . $user->user_image) }}"
            alt="User Image" class="rounded-circle" width="31"  /></a>
    @else
        {{-- Default image --}}
       <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
            alt="Default Image"  class="rounded-circle" width="31" /></a>
    @endif
    @else
    {{-- User is not authenticated, display default image --}}
    <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
        alt="Default Image" class="rounded-circle" width="31" />
@endif

                    <div class="dropdown-menu dropdown-menu-end user-dd animated flipInY">

                        <span class="with-arrow"><span class="bg-site"></span></span>

                        <div class="d-flex no-block align-items-center p-3 bg-site text-white mb-2">

                            <div class="">

                               @if(auth()->check())
    {{-- User is authenticated --}}
    @php
        $userId = auth()->id(); // Get the authenticated user's ID
        $user = App\Models\User::find($userId); // Retrieve the authenticated user from the database
    @endphp

    {{-- Check if the user has an image --}}
    @if($user && $user->user_image)
        {{-- Display the user's image --}}
        <img src="{{ asset('public/images/Uploads/users/' . $user->id . '/' . $user->user_image) }}"
            alt="User Image" class="rounded-circle" width="60" />
    @else
        {{-- Default image --}}
        <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
            alt="Default Image" class="rounded-circle" width="60" />
    @endif
@else
    {{-- User is not authenticated, display default image --}}
    <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
        alt="Default Image" class="rounded-circle" width="60" />
@endif

                            </div>

                            <div class="ms-2">

                                <h5 class="mb-0 mt-1 uppercase"> {{ Auth::user()->name }}</h5>

                                <p class="mb-0">{{ Auth::user()->email }}</p>

                            </div>

                        </div>

						  <a class="dropdown-item bold" href="{{ route('myprofile.index') }}"><i class="far fa-user me-1 ms-1"></i> My Profile </a>
						  <a class="dropdown-item bold" href="{{ route('app_chats') }}"><i class="ri-mail-line me-1 ms-1"></i> Messages</a>
						  <a class="dropdown-item bold" href="{{route('myprofile.account')}}"><i class="ri-settings-2-line me-1 ms-1"></i> Settings </a>
						  <a class="dropdown-item bold" href="{{route('myprofile.activity')}}"><i class="ri-list-check me-1 ms-1"></i> Activity </a>

                         

                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <a class="dropdown-item  bold" href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"><i
                                    data-feather="log-out" class="feather-sm me-1 ms-1"></i>Logout</a>

                        </form>

                        <div class="dropdown-divider"></div>

                       

                    </div>
 