@extends('home')

@section('content')
<style>
    .alert-dismissible {
        position: relative;
        padding-right: 4em;
        /* Adjust as needed */
    }

    .close-alert {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
        cursor: pointer;
    }
</style>

<div class="page-wrapper" style="display:inline;">

 <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Left section with left margin -->
        <div class="col-auto" style="margin-left: 30px;">
            <h4 class="page-title">My Profile</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">My Profile</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Right section with right margin -->
        <div class="col-auto" style="margin-right: 5px;">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <a href="{{ route('myprofile.index') }}"
                    class="btn {{ request()->routeIs('myprofile.index') ? 'btn-info' : 'btn-secondary text-white' }}">Profile</a>
                <a href="{{ route('myprofile.account') }}"
                    class="btn {{ request()->routeIs('myprofile.account') ? 'btn-info' : 'btn-secondary text-white' }}">Account Settings</a>
                <a href="{{ route('myprofile.activity') }}"
                    class="btn {{ request()->routeIs('myprofile.activity') ? 'btn-info' : 'btn-secondary text-white' }}">Activity and Notifications</a>
            </div>
        </div>
    </div>
</div>



    </div>

    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Display error message if it exists in the session -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert" id="error-alert">
            {{ session('error') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <script>
            // Automatically close the alerts after 5 seconds
		setTimeout(function () {
			$('#success-alert, #error-alert').alert('close');
		}, 5000);
        </script>

        <div class="row">

            <div class="col-md-8">

                <div class="card">
                    <div class="card-body card-border shadow p-4">
                        <form action="{{ route('user.infoadmin') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id ?? null}}">

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext" class="form-label bold mb5">First Name</label>
                                        <input type="text" name="first_name" id="first_name"
                                            value="{{ old('first_name', $first_name) ?? null}}" class="form-control"
                                            id="exampleInputtext" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext" class="form-label bold mb5">Last Name</label>
                                        <input type="text" id="last_name" name="last_name"
                                            value="{{ old('last_name', $last_name) ?? null}}" class="form-control"
                                            id="exampleInputtext" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext" class="form-label bold mb5">Display
                                            Name</label>
                                        <input type="text" id="display_name" name="name"
                                            value="{{ $user->name ?? null}}" class="form-control" id="exampleInputtext"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext3" class="form-label bold mb5">Phone</label>
                                        <input type="text" value="{{ $user->mobile ?? null}}" class="form-control"
                                            name="mobile" id="exampleInputtext3" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext3" class="form-label bold mb5">Home Phone</label>
                                        <input type="text" value="{{ old('home_phone', $home_phone) ?? null}}"
                                            class="form-control" name="home_phone" id="exampleInputtext3"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext3" class="form-label bold mb5">Work Phone</label>
                                        <input type="text" value="{{ old('work_phone', $work_phone) ?? null}}"
                                            class="form-control" name="work_phone" id="exampleInputtext3"
                                            placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext1" class="form-label bold mb5">Email</label>
                                        <input type="email" name=email value="{{ $user->email ?? null}}"
                                            class="form-control" id="exampleInputtext1" placeholder="" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext3" class="form-label bold mb5">SSN (Social
                                            Security Number)</label>
                                        <input type="text" name="ssn" value="{{ old('ssn', $ssn) ?? null}}"
                                            class="form-control" id="exampleInputtext4" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext3" class="form-label bold mb5">DOB (Date of
                                            Birth)</label>
                                        <input type="date" name="dob" value="{{ old('dob', $dob) ?? null}}"
                                            class="form-control" id="exampleInputtext4" placeholder="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="exampleInputtext4" class="form-label bold mb5">Address line
                                            1</label>
                                        <input type="text" name="address_line1"
                                            value="{{ $userAddress->address_line1 ?? null}}" class="form-control"
                                            id="exampleInputtext4" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="exampleInputtext4" class="form-label bold mb5">Address line
                                            2</label>
                                        <input type="text" name="address_line2"
                                            value="{{ $userAddress->address_line2 ?? null}}" class="form-control"
                                            id="exampleInputtext4" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext4" class="form-label bold mb5">City</label>
                                        <input type="text" name="city" value="{{ $userAddress->city ?? null}}"
                                            class="form-control" id="exampleInputtext4" placeholder="">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label class="form-label bold mb5">State</label>
                                        <select class="form-select" aria-label="Default select example" name="state_id">
                                            @foreach($locationStates as $state)
                                            <option value="{{ $state->state_id }}" {{ $userAddress && $state->state_id
                                                == $userAddress->state_id ? 'selected' : '' }}>
                                                {{ $state->state_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-4">
                                        <label for="exampleInputtext4" class="form-label bold mb5">zipcode</label>
                                        <input type="text" name="zipcode" value="{{ $userAddress->zipcode ?? null}}"
                                            class="form-control" id="exampleInputtext4" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                                        <button type="submit" class="btn btn-primary">Save</button>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



            </div>

            <div class="col-md-4">

                <div class="card">
                    <div class="card-body card-border shadow p-4">

                        <form action="{{ route('user.adminprofileimg', $user->id) }}" method="post"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="text-center">
                                @if(file_exists(public_path('images/Uploads/users/' . $user->id . '/' .
                                $user->user_image)))
                                <img src="{{ asset('public/images/Uploads/users/' . $user->id . '/' . $user->user_image) }}"
                                    alt="User Image" id="imagePreview" class="img-fluid rounded-circle" width="120"
                                    height="120">
                                @else
                                <img src="{{ asset('admin/assets/images/users/1.jpg') }}" alt="Default Preview"
                                    id="imagePreview" class="img-fluid rounded-circle" width="120" height="120">
                                @endif


                                <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                    <input type="file" class="form-control" id="file" name="user_image"
                                        value="{{ $user->user_image }}" accept="image/*" onchange="showImagePreview()"
                                        required>
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                    {{-- <button class="btn btn-outline-danger">Reset</button> --}}
                                </div>
                                <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body card-border shadow p-4">
                        <h5 class="card-title">CHANGE PASSWORD</h5>
                        <form action="{{ route('user.passstoreadmin') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="mb-4">
                                <label for="exampleInputPassword1" class="form-label bold mb5">Current
                                    Password</label>
                                <input type="password" name="currentpassword" class="form-control"
                                    id="exampleInputPassword1" value="" required>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputPassword2" class="form-label bold mb5">New Password</label>
                                <input type="password" name="password" class="form-control" id="exampleInputPassword2"
                                    value="" required>
                            </div>
                            <div class="">
                                <label for="exampleInputPassword3" class="form-label bold mb5">Confirm
                                    Password</label>
                                <input type="password" name="conformpassword" class="form-control"
                                    id="exampleInputPassword3" value="" required>
                            </div>
                            <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                <button type="submit" class="btn btn-primary">Change</button>
                                {{-- <button class="btn btn-outline-danger">Reset</button> --}}
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>






    </div>

</div>
</div>
@section('script')
<script>
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const displayNameInput = document.getElementById('display_name');

    // Function to update the display name field
    function updateDisplayName() {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();

        // Concatenate first and last name
        const displayName = firstName + ' ' + lastName;

        // Set the display name input value
        displayNameInput.value = displayName;
    }

    // Listen for input changes on first and last name fields
    firstNameInput.addEventListener('input', updateDisplayName);
    lastNameInput.addEventListener('input', updateDisplayName);

</script>

@endsection
@endsection
