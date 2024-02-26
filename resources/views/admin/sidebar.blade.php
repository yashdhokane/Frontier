<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">

            <div style="height: 10px;"></div>


        <!-- auth dispatcher containt -->

        @auth 
    @if(auth()->user()->role == 'dispatcher') 
        <aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">

            <div style="height: 10px;"></div>

            <ul id="sidebarnav">

                @php

                    $currentRoute = Route::current();

                    $routeUri = $currentRoute->uri();

                    $routeSegments = explode('/', $routeUri);

                    $prefix = (isset($routeSegments[0]) && !empty($routeSegments[0])) ? $routeSegments[0] : '';

                @endphp

                @if ($prefix == 'book-list')

                    <li>
                        <h4 class="card-title text-center mt-4 mb-2">PRICE BOOK</h4>
                    </li>
                    <li class="sidebar-item "><a href="{{ route('services.index') }}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Services </span></a></li>
                    <li class="sidebar-item"><a href="{{route('product.index')}}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Parts
                            </span></a></li>
                    <li class="sidebar-item"><a href="{{route('estimate.index')}}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Estimate Templates
                            </span></a></li>
                    <li>
                        <h5 class="card-title text-center mt-4 mb-2">SETTINGS</h5>
                    </li>
                    <li class="sidebar-item"><a href="#." class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Inport & Export Services
                            </span></a></li>
                    <li class="sidebar-item"><a href="#." class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Inport & Export Materials
                            </span></a></li>

                @else

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link " href="{{ route('home') }}" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
					
					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-users" style="font-size: 16px;"></i> <span class="hide-menu">Profiles </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
							<li class="sidebar-item"><a href="{{ route('technicians.index') }}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Technicians </span></a></li>
							<li class="sidebar-item"><a href="{{ route('users.index') }}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Customers </span></a></li>
							

                          </ul>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu">Jobs</span></a>
                        <ul aria-expanded="false" class="collapse first-level">
							<li class="sidebar-item"><a href="https://gaffis.in/frontier/website/schedule" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu"> Schedule </span></a></li>
							<li class="sidebar-item"><a href="https://gaffis.in/frontier/website/map" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu"> Reschedule </span></a></li>
							<li class="sidebar-item"><a href="{{ route('tickets.index') }}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Jobs List </span></a></li>
                          </ul>
                    </li>
 						
 
                    <!--<li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link " href="#."
                            aria-expanded="false"><i class="fas fa-chart-line" style="font-size: 16px;"></i><span
                                class="hide-menu">Reports</span></a>
                    </li>-->
                    

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#."
                            aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                                class="hide-menu">Log Out</span></a>
                    </li>
                @endif

            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

    @endif
@endauth


    @auth 
    @if(auth()->user()->role != 'dispatcher') 



            <ul id="sidebarnav">

                @php

                    $currentRoute = Route::current();

                    $routeUri = $currentRoute->uri();

                    $routeSegments = explode('/', $routeUri);

                    $prefix = (isset($routeSegments[0]) && !empty($routeSegments[0])) ? $routeSegments[0] : '';

                @endphp

                @if ($prefix == 'book-list')

                    <li>
                        <h4 class="card-title text-center mt-4 mb-2">PRICE BOOK</h4>
                    </li>
                    <li class="sidebar-item "><a href="{{ route('services.index') }}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Services </span></a></li>
                    <li class="sidebar-item"><a href="{{route('product.index')}}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Parts
                            </span></a></li>
                    <li class="sidebar-item"><a href="{{route('estimate.index')}}" class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Estimate Templates
                            </span></a></li>
                    <li>
                        <h5 class="card-title text-center mt-4 mb-2">SETTINGS</h5>
                    </li>
                    <li class="sidebar-item"><a href="#." class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Inport & Export Services
                            </span></a></li>
                    <li class="sidebar-item"><a href="#." class="sidebar-link"><i
                                class="mdi mdi-book-multiple"></i><span class="hide-menu"> Inport & Export Materials
                            </span></a></li>

                @else

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link " href="{{ route('home') }}" aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
					
					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-users" style="font-size: 16px;"></i> <span class="hide-menu">Profiles </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
							<li class="sidebar-item"><a href="{{ route('technicians.index') }}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Technicians </span></a></li>
							<li class="sidebar-item"><a href="{{ route('users.index') }}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Customers </span></a></li>
							<li class="sidebar-item"><a href="{{route('dispatcher.index')}}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Dispatchers </span></a></li>
                          <li class="sidebar-item"><a href="{{route('multiadmin.index')}}" class="sidebar-link"><i class="fas fa-users"></i><span class="hide-menu"> Admin </span></a></li>

                          </ul>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu">Jobs</span></a>
                        <ul aria-expanded="false" class="collapse first-level">
							<li class="sidebar-item"><a href="https://gaffis.in/frontier/website/schedule" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu"> Schedule </span></a></li>
							<li class="sidebar-item"><a href="https://gaffis.in/frontier/website/map" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu"> Reschedule </span></a></li>
							<li class="sidebar-item"><a href="{{ route('tickets.index') }}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Jobs List </span></a></li>
                          </ul>
                    </li>
 							
					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i data-feather="bookmark" class="feather-icon"></i><span class="hide-menu">Price Book </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/book-list/services" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Services </span></a></li>
                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/book-list/partscategory" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Parts </span></a></li>
                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/book-list/estimate" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Estimate Templates  </span></a></li>
                         </ul>
                    </li>
					
					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i data-feather="clipboard" class="feather-icon"></i><span class="hide-menu">Payments</span></a>
                        <ul aria-expanded="false" class="collapse first-level">
 							<li class="sidebar-item"><a href="{{route('payment-list')}}" class="sidebar-link"><i class="far fa-money-bill-alt"></i><span class="hide-menu"> Payments</span></a></li>
							<li class="sidebar-item"><a href="#." class="sidebar-link"><i class="far fa-money-bill-alt"></i><span class="hide-menu"> Invoices</span></a></li>
							<li class="sidebar-item"><a href="#." class="sidebar-link"><i class="far fa-money-bill-alt"></i><span class="hide-menu"> Estimates</span></a></li>
                         </ul>
                    </li>
					
 					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas fa-chart-line" style="font-size: 16px;"></i> <span class="hide-menu">Reports </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                            <li class="sidebar-item"><a href="#." class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Performance Matrix </span></a></li>
                            <li class="sidebar-item"><a href="#." class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Technician Earnings </span></a></li>
                            <li class="sidebar-item"><a href="#." class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Call Monitoring </span></a></li>
 						</ul>
                    </li>
					
					<li class="sidebar-item">
                        <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas far fa-sun" style="font-size: 16px;"></i> <span class="hide-menu">Settings </span></a>
                        <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/setting/service-area" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Service Area </span></a></li>

                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/setting/buisness-profile" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Business Profile </span></a></li>
                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/setting/businessHours/business-hours" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Working Hours </span></a></li>
                            <li class="sidebar-item"><a href="https://gaffis.in/frontier/website/setting/manufacturer" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Manufacturer</span></a></li>
                                 <li class="sidebar-item"><a href="{{ route('tax.index') }}" class="sidebar-link"><i
                                    class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Tax</span></a></li>

                            <li class="sidebar-item"><a href="{{route('lead.lead-source')}}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Lead Source </span></a></li>
                            <li class="sidebar-item"><a href="{{route('tags.tags-list')}}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Tags </span></a></li>
                            <li class="sidebar-item"><a href="{{route('jobfields.job-fields-list')}}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Job Fields </span></a></li>
						</ul>
                    </li>
 
                    <!--<li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link " href="#."
                            aria-expanded="false"><i class="fas fa-chart-line" style="font-size: 16px;"></i><span
                                class="hide-menu">Reports</span></a>
                    </li>-->
                    

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="#."
                            aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                                class="hide-menu">Log Out</span></a>
                    </li>
                @endif

            </ul>
                @endif
@endauth

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
