@auth

    @if (auth()->user()->role == 'dispatcher')
    @elseif(auth()->user()->role == 'admin')

    @elseif(auth()->user()->role == 'superadmin')
    @else
    @endif
@endauth



<!-- Default Sidebar for other roles -->
<!-- Add your default sidebar code here -->
<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-dark">

        <div class="navbar-header">
            @include('admin.nav-logo')
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto">

                <li class="nav-item d-none d-md-block">
                    <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar"><i data-feather="menu" class="feather-sm"></i></a>
                </li>

                <li class="toplinks"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>

                <li @if (request()->routeIs('schedule')) class="toplinks selected" @else class="toplinks" @endif
                    class="toplinks"><a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i>
                        Schedule</a></li>

                <li class="toplinks"><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Customer</a></li>

                <!-- mega menu -->
                <li class="nav-item dropdown mega-dropdown">
                    @include('admin.nav-mega-menu')
                </li>
                <!-- End mega menu -->

                <!-- create new -->
                <li class="nav-item dropdown">
                    @include('admin.nav-create-new')
                </li>
                <!-- End create new -->

                <!-- SEARCH -->
                <li class="nav-item search-box">
                    @include('admin.nav-search')
                </li>
                <!-- END SEARCH -->

            </ul>

            <!-- Right side toggle and nav items -->
            <ul class="navbar-nav">
                @php
                    use Carbon\Carbon;
                    $currentFormattedDate = Carbon::now($timezoneName)->format('D d, M\' y');
                    $currentFormattedDateTime = Carbon::now($timezoneName)->format('h:i:s A T');
                @endphp
                <li class="nav-item dropdown align-self-center px-2">
                    <div class="nav-clock"><span>{{ $currentFormattedDate }}</span><br />
                        <span id="liveTime"></span>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" id="showStickyNote"><i
                            class="fas fa-sticky-note ft20"></i> </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="{{ route('map') }}"><i
                            class="fas fa-map-marker-alt ft20"></i> </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark"
                        href="{{ route('buisnessprofile.index') }}"><i class="far fa-sun ft20"></i></a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark"
                        href="https://dispatchannel.com/portal/reports/jobs"><i class="fas fa-chart-line ft20"></i> </a>
                </li>

                <!-- NOTIFICATION -->
                <li class="nav-item dropdown">
                    @include('admin.nav-notification')
                </li>
                <!-- END NOTIFICATION -->

                <!-- MESSAGES -->
                <li class="nav-item dropdown">
                    @include('admin.nav-messages')
                </li>
                <!-- END MESSAGES -->


                <!-- USER PROFILE AND SEARCH -->
                <li class="nav-item dropdown">
                    @include('admin.nav-user-profile')
                </li>
                <!-- END USER PROFILE AND SEARCH -->

                <link rel="stylesheet"
                    href="{{ asset('public/admin/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

            </ul>

        </div>

    </nav>


    <div class="stickyMainSection" style="display: none;">
        <a href="#." class="close-task-detail in" id="close-task-detail" style="display: ;">
            X
        </a>
        <div class="sticky-note bg-white w-100 h-100">

            <div class="row m-2 stickyNotesList">
                <div class="col-sm-12 col-md-12">
                    <h3 class="p-3 mt-2">  Notes </h3>
                </div>
                <div class="col-sm-12 col-md-12"><button type="button" class="btn btn-primary ms-3 addStickyNoteBtn">
                        <i class="fa fa-plus"></i> Add Note</button></div>

                @php
                    $stickyNote = \App\Models\StickyNotes::all();
                @endphp

                <div class="col-sm-9 col-md-9">
                    <div class="row sticknoteslist">
                        @foreach ($stickyNote as $item)
                            <div class="col-sm-4 col-md-4 my-3">
                                <div class="card border rounded p-3 h-100 justify-content-between">
                                    <div class="row d-flex justify-content-between">
                                        <div class="col-9"> {{ $item->note }} </div>
                                        <div class="col-2 btn-group ms-2">
                                            <div class="text-primary fw-bold fs-7 actionBtnNote" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                ...
                                            </div>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item editStckyNoteBtn"
                                                    data-note-id="{{ $item->note_id }}"><i data-feather="edit"
                                                        class="feather-sm me-2"></i> Edit</a>
                                                <input type="hidden" class="edit_note_id" value="{{ $item->note_id }}">
                                                <a class="dropdown-item deleteStckyNoteBtn"
                                                    data-note-id="{{ $item->note_id }}"><i data-feather="trash"
                                                        class="feather-sm me-2"></i> Delete</a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div> {{ \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d h:i A') }}
                                        </div>
                                        <div> <i class="fa fa-circle" style="color:{{ $item->color_code }} ;"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-3 col-md-3"> </div>

            </div>

            <div class="row m-2 addStickyNote" style="display: none;">
                <div class="col-sm-12 col-md-12">
                    <h3 class="p-1 mt-2">Add Notes </h3>
                    <hr>
                </div>
                <div class="col-sm-12 col-md-12">
                    <h4>Note Details</h4>
                </div>

                @php
                    $color = \App\Models\ColorCode::all();
                @endphp

                <div class="col-sm-9 col-md-9">
                    <form id="colorNoteForm" method="post" class="form-horizontal form-material"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-md-12 mb-4">
                                    <label for="" class="my-2"> Color Code </label>
                                    <select name="color_code" class="form-control" id="">
                                        @foreach ($color as $value)
                                            <option value="{{ $value->color_code }}"
                                                style="background: {{ $value->color_code }};">
                                                {{ $value->color_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="" class="my-2">Note </label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                Save
                            </button>
                            <button type="button" class="btn btn-info waves-effect closeStickyAdd"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                        </div>
                    </form>
                </div>
                <div class="col-sm-3 col-md-3"> </div>

            </div>

            <div class="row m-2 editStickyNote" style="display: none;">
                <div class="col-sm-12 col-md-12">
                    <h3 class="p-1 mt-2">Edit Note </h3>
                    <hr>
                </div>
                <div class="col-sm-12 col-md-12">
                    <h4>Note Details</h4>
                </div>


                <div class="col-sm-9 col-md-9">
                    <form id="editNoteForm" method="post" class="form-horizontal form-material"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="note_id" id="edit_note_id2" value="">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-md-12 mb-4">
                                    <label for="" class="my-2"> Color Code </label>
                                    <select name="color_code" class="form-control" id="edit_color_code">
                                        @foreach ($color as $value)
                                            <option value="{{ $value->color_code }}"
                                                style="background: {{ $value->color_code }};">
                                                {{ $value->color_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="">
                                        <label for="" class="my-2">Note </label>
                                        <textarea name="note" class="form-control" id="edit_note"></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-info ">
                                Update
                            </button>
                            <button type="button" class="btn btn-info waves-effect closeStickyAdd">
                                Cancel
                            </button>

                        </div>
                    </form>
                </div>
                <div class="col-sm-3 col-md-3"> </div>

            </div>


        </div>

    </div>

</header>

<script>
    const storeColorNoteUrl = "{{ route('store.colorNote') }}";
    const updateColorNoteUrl = "{{ route('update.colorNote') }}";
    const storeEditNoteUrl = "{{ route('note.get') }}";
    const deleteNoteUrl = "{{ route('note.delete') }}";
    const csrfToken = "{{ csrf_token() }}";

    function updateTime() {
        const timezoneName = '{{ $timezoneName }}'; // Dynamic timezone from backend
        const options = {
            timeZone: timezoneName,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
            timeZoneName: 'short'
        };

        const formatter = new Intl.DateTimeFormat('en-US', options);
        const now = new Date();
        const formattedTime = formatter.format(now);

        document.getElementById('liveTime').innerText = formattedTime;
    }

    setInterval(updateTime, 1000); // Update every second
    updateTime(); // Initial call
</script>
