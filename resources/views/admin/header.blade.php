<header class="topbar">

    <nav class="navbar top-navbar navbar-expand-md navbar-dark">

        <div class="navbar-header">

            <!-- This is for the sidebar toggle which is visible on mobile only -->

            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">

                <i class="ri-close-line fs-6 ri-menu-2-line"></i>

            </a>

            <!-- -------------------------------------------------------------- -->

            <!-- Logo -->

            <!-- -------------------------------------------------------------- -->

            <a class="navbar-brand" href="index.html">

                <!-- Logo icon -->

                <b class="logo-icon">

                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->

                    <!-- Dark Logo icon -->

                    <img src="{{ asset('public/admin/assets/images/logo-icon.png') }}" alt="homepage"
                        class="dark-logo" />

                    <!-- Light Logo icon -->

                    <img src="{{ asset('public/admin/assets/images/logo-light-icon.png') }}" alt="homepage"
                        class="light-logo" />

                </b>

                <!--End Logo icon -->

                <!-- Logo text -->

                <span class="logo-text">

                    <!-- dark Logo text -->

                    <img src="{{ asset('public/admin/assets/images/logo-text.png') }}" alt="homepage"
                        class="dark-logo" />

                    <!-- Light Logo text -->

                    <img src="{{ asset('public/admin/assets/images/logo-light-text.png') }}" class="light-logo"
                        alt="homepage" />

                </span>

            </a>

            <!-- -------------------------------------------------------------- -->

            <!-- End Logo -->

            <!-- -------------------------------------------------------------- -->

            <!-- -------------------------------------------------------------- -->

            <!-- Toggle which is visible on mobile only -->

            <!-- -------------------------------------------------------------- -->

            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                    class="ri-more-line fs-6"></i></a>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- End Logo -->

        <!-- -------------------------------------------------------------- -->

        <div class="navbar-collapse collapse" id="navbarSupportedContent">

            <!-- -------------------------------------------------------------- -->

            <!-- toggle and nav items -->

            <!-- -------------------------------------------------------------- -->

            <ul class="navbar-nav me-auto">

                <li class="nav-item d-none d-md-block">

                    <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar"><i data-feather="menu" class="feather-sm"></i></a>

                </li>



                <li class="toplinks"><a href="https://gaffis.in/frontier/website/home"><i class="fas fa-home"></i>
                        Home</a></li>

                <li @if (request()->routeIs('schedule')) class="toplinks selected" @else class="toplinks" @endif class="toplinks"><a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i>
                        Schedule</a></li>

                <li class="toplinks"><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Customer</a></li>



                <!-- -------------------------------------------------------------- -->

                <!-- mega menu -->

                <!-- -------------------------------------------------------------- -->

                <li class="nav-item dropdown mega-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark megamenutop1" role="button"
                        href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-md-block">More <i data-feather="chevron-down"
                                class="feather-sm"></i></span>
                        <span class="d-block d-md-none"><i class="ri-keyboard-line"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animate-up">
                        <div class="mega-dropdown-menu row">
                            <div class="col-lg-3 mb-4">
                                <h4 class="mb-3">Price Book</h4>
                                <ul class="list-style-none">
									<li><a href="https://gaffis.in/frontier/website/book-list/services" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Services </span></a></li>
									<li><a href="https://gaffis.in/frontier/website/book-list/productCategory" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Materials </span></a></li>
									<li><a href="https://gaffis.in/frontier/website/book-list/estimate" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Estimate Templates </span></a></li>
								</ul>
                            </div>
                            <div class="col-lg-3 mb-4">
                                <h4 class="mb-3">Settings</h4>
                                <ul class="list-style-none">
									<li><a href="https://gaffis.in/frontier/website/buisness-profile" class="sidebar-link"><i
									class="mdi mdi-book-multiple"></i><span class="hide-menu"> Profile
									</span></a></li>
									<li><a href="https://gaffis.in/frontier/website/businessHours/business-hours " class="sidebar-link"><i
									class="mdi mdi-book-multiple"></i><span class="hide-menu"> Business
									Hours </span></a></li>
									<li><a href="estimate-templates.html" class="sidebar-link"><i
									class="mdi mdi-book-multiple"></i><span class="hide-menu"> Service Area
									</span></a></li>
                                  </ul>
                            </div>
                            <div class="col-lg-3 col-xl-2 mb-4">
                                <h4 class="mb-3">Profiles</h4>
                                <ul class="list-style-none">
                                    <li><a href="https://gaffis.in/frontier/website/technicians"><i class="fas fa-user"></i> Technicians</a></li>
                                    <li><a href="https://gaffis.in/frontier/website/users"><i class="fas fa-user-plus"></i> Customers</a></li>
                                    <li><a href="#"><i class="fas fa-user-plus"></i> Dispatchers</a></li>
                                 </ul>
                            </div>
                            <div class="col-lg-3 mb-4">
                                <h4 class="mb-3">Reporting</h4>
                                <ul class="list-style-none">
									<li><a href="#."><i class="fas fa-list"></i> Technician Earnings</a></li>
									<li><a href="#."><i class="fas fa-list"></i> Call Monitoring</a></li>
									<li><a href="#."><i class="fas fa-list"></i> Performance Matrix</a></li>
                                 </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- -------------------------------------------------------------- -->

                <!-- End mega menu -->

                <!-- -------------------------------------------------------------- -->

                <!-- -------------------------------------------------------------- -->

                <!-- create new -->

                <!-- -------------------------------------------------------------- -->





                <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle lightbg" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="d-none d-md-block"><i class="fas fa-plus"></i> Create New <i
                                data-feather="chevron-down" class="feather-sm"></i></span>
                         <span class="d-block d-md-none"><i data-feather="plus" class="feather-sm"></i></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="#.">Job</a>
                         <a class="dropdown-item" href="#.">Estimate</a>
                         <a class="dropdown-item" href="https://gaffis.in/frontier/website/technicians/create">Technician</a>
                         <a class="dropdown-item" href="https://gaffis.in/frontier/website/users/create">Customer</a>
                     </div>
                 </li>

                <!-- -------------------------------------------------------------- -->

                <!-- Search -->

                <!-- -------------------------------------------------------------- -->

                <li class="nav-item search-box">

                    <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i data-feather="search"
                            class="feather-sm"></i></a>

                    <form class="app-search position-absolute">

                        <input type="text" class="form-control" placeholder="Search &amp; enter" />

                        <a class="srh-btn"><i data-feather="x" class="feather-sm"></i></a>

                    </form>

                </li>

            </ul>

            <!-- -------------------------------------------------------------- -->

            <!-- Right side toggle and nav items -->

            <!-- -------------------------------------------------------------- -->



            <ul class="navbar-nav">



                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark"
                        href="https://gaffis.in/frontier/website/map"><i class="fas fa-map-marker-alt"
                            style="font-size: 20px;"></i> </a>

                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark"
                        href="{{route('servicearea.index')}}"><i class="far fa-sun"
                            style="font-size: 20px;"></i> </a>

                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark"
                        href="https://gaffis.in/frontier/website/performance-metrix"><i class="fas fa-chart-line"
                            style="font-size: 20px;"></i> </a>

                </li>





                <!-- -------------------------------------------------------------- -->

                <!-- Comment -->

                <!-- -------------------------------------------------------------- -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href=""
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <i data-feather="bell" class="feather-sm"></i>

                    </a>

                    <div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <ul class="list-style-none">

                            <li>

                                <div class="drop-title bg-primary text-white">

                                    <h4 class="mb-0 mt-1">4 New</h4>

                                    <span class="fw-light">Notifications</span>

                                </div>

                            </li>

                            <li>

                                <div class="message-center notifications">

                                    <!-- Message -->

                                    <a href="#" class="message-item">

                                        <span class="btn btn-light-danger text-danger btn-circle">

                                            <i data-feather="link" class="feather-sm fill-white"></i>

                                        </span>

                                        <div class="mail-contnet">

                                            <h5 class="message-title">Luanch Admin</h5>

                                            <span class="mail-desc">Just see the my new admin!</span>

                                            <span class="time">9:30 AM</span>

                                        </div>

                                    </a>

                                    <!-- Message -->

                                    <a href="#" class="message-item">

                                        <span class="btn btn-light-success text-success btn-circle">

                                            <i data-feather="calendar" class="feather-sm fill-white"></i>

                                        </span>

                                        <div class="mail-contnet">

                                            <h5 class="message-title">Event today</h5>

                                            <span class="mail-desc">Just a reminder that you have event</span>

                                            <span class="time">9:10 AM</span>

                                        </div>

                                    </a>

                                    <!-- Message -->

                                    <a href="#" class="message-item">

                                        <span class="btn btn-light-info text-info btn-circle">

                                            <i data-feather="settings" class="feather-sm fill-white"></i>

                                        </span>

                                        <div class="mail-contnet">

                                            <h5 class="message-title">Settings</h5>

                                            <span class="mail-desc">You can customize this template as you want</span>

                                            <span class="time">9:08 AM</span>

                                        </div>

                                    </a>

                                    <!-- Message -->

                                    <a href="#" class="message-item">

                                        <span class="btn btn-light-primary text-primary btn-circle">

                                            <i data-feather="users" class="feather-sm fill-white"></i>

                                        </span>

                                        <div class="mail-contnet">

                                            <h5 class="message-title">Pavan kumar</h5>

                                            <span class="mail-desc">Just see the my admin!</span>

                                            <span class="time">9:02 AM</span>

                                        </div>

                                    </a>

                                </div>

                            </li>

                            <li>

                                <a class="nav-link text-center mb-1 text-dark" href="#">

                                    <strong>Check all notifications</strong>

                                    <i data-feather="chevron-right" class="feather-sm"></i>

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>

                <!-- -------------------------------------------------------------- -->

                <!-- End Comment -->

                <!-- -------------------------------------------------------------- -->

                <!-- -------------------------------------------------------------- -->

                <!-- Messages -->

                <!-- -------------------------------------------------------------- -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <i data-feather="message-square" class="feather-sm"></i>

                    </a>
                    @php
                        use App\Models\SupportMessage;

                        $chats = SupportMessage::with('user')->latest()->limit(5)->get();
                    @endphp

                    <div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up" aria-labelledby="2">

                        <span class="with-arrow"><span class="bg-danger"></span></span>

                        <ul class="list-style-none">

                            <li>

                                <div class="drop-title text-white bg-danger">

                                    <h4 class="mb-0 mt-1">{{ count($chats) }} New</h4>

                                    <span class="fw-light">Messages</span>

                                </div>

                            </li>

                            <li>

                                <div class="message-center message-body">

                                    <!-- Message -->
                                    @foreach ($chats as $item)
                                    <a href="{{ route('app_chats', ['message_id' => $item->id, 'user_one' => $item->user_one, 'user_two' => $item->user_two]) }}" class="message-item">

                                            <span class="user-img">
                                                @if ($item->user)
                                                    <img src="{{ asset('public/images/technician/' . $item->user->user_image) }}"
                                                        alt="user" class="rounded-circle" />
                                                @else
                                                    <img src="{{ asset('public/images/technician/1708105764_avatar-1.png') }}"
                                                        alt="user" class="rounded-circle" />
                                                @endif


                                                <span class="profile-status online pull-right"></span>

                                            </span>

                                            <div class="mail-contnet">
                                                @if ($item->user)
                                                    <h5 class="message-title">{{ $item->user->name }}</h5>
                                                @else
                                                    <p class="m-00">Technician N\A</p>
                                                @endif

                                                <span class="mail-desc">Just see the my admin!</span>

                                                <span class="time">{{ $item->created_at->format('g:i A') }}</span>

                                            </div>

                                        </a>
                                    @endforeach




                                </div>

                            </li>

                            <li>

                                <a class="nav-link text-center link text-dark" href="{{route('app_chats')}}">

                                    <b>Go To Chats</b>

                                    <i data-feather="chevron-right" class="feather-sm"></i>

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>

                <!-- -------------------------------------------------------------- -->

                <!-- End Messages -->

                <!-- -------------------------------------------------------------- -->

                <!-- -------------------------------------------------------------- -->



                <!-- User profile and search -->

                <!-- -------------------------------------------------------------- -->

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                            src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                            class="rounded-circle" width="31" /></a>

                    <div class="dropdown-menu dropdown-menu-end user-dd animated flipInY">

                        <span class="with-arrow"><span class="bg-primary"></span></span>

                        <div class="d-flex no-block align-items-center p-3 bg-primary text-white mb-2">

                            <div class="">

                                <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                                    class="rounded-circle" width="60" />

                            </div>

                            <div class="ms-2">

                                <h4 class="mb-0"> {{ Auth::user()->name }}</h4>

                                <p class="mb-0">{{ Auth::user()->email }}</p>

                            </div>

                        </div>

                        <a class="dropdown-item" href="{{route('myprofile.index')}}"><i data-feather="user"
                                class="feather-sm text-info me-1 ms-1"></i> My Profile</a>

                        <a class="dropdown-item" href="#"><i data-feather="mail"
                                class="feather-sm text-success me-1 ms-1"></i> Messages</a>

                        <a class="dropdown-item" href="#"><i data-feather="settings"
                                class="feather-sm text-success me-1 ms-1"></i> Account Setting</a>

                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <a class="dropdown-item" href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"><i
                                    data-feather="log-out" class="feather-sm text-danger me-1 ms-1"></i>Logout</a>

                        </form>

                        <div class="dropdown-divider"></div>

                        <div class="pl-4 p-2">

                            <a href="#" class="btn d-block w-100 btn-primary rounded-pill">View Profile</a>

                        </div>

                    </div>

                </li>

                <!-- -------------------------------------------------------------- -->

                <!-- User profile and search -->

                <link rel="stylesheet"
                    href="{{ asset('public/admin/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">




            </ul>

        </div>

    </nav>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.min.css">

</header>
