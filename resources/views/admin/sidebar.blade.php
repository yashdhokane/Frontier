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

                $prefix = isset($routeSegments[0]) && !empty($routeSegments[0]) ? $routeSegments[0] : '';

                @endphp

                @if ($prefix == 'book-list')
                <li>
                    <h5 class="card-title text-center mt-4 mb-2">PRICE BOOK</h5>
                </li>
                <li class="sidebar-item ft1"><a href="{{ route('services.index') }}" class="sidebar-link"><i
                            class="ri-tools-line fas"></i><span class="hide-menu"> Services </span></a>
                </li>
                <li class="sidebar-item ft2"><a href="{{ route('product.index') }}" class="sidebar-link"><i
                            class="ri-folder-chart-line fas"></i><span class="hide-menu"> Parts
                        </span></a></li>
                <li class="sidebar-item ft3"><a href="{{route('assign_product')}}" class="sidebar-link"><i
                            class="ri-shape-2-line fas"></i> <span class="hide-menu">Assign Parts </span></a></li>
                <li class="sidebar-item ft4"><a href="{{ route('estimate.index') }}" class="sidebar-link"><i
                            class="ri-pages-line fas"></i><span class="hide-menu"> Estimate Templates
                        </span></a></li>
                <li>
                    <h6 class="card-title text-center mt-4 mb-2">SETTINGS</h6>
                </li>
                <li class="sidebar-item ft5"><a href="#." class="sidebar-link"><i class="ri-settings-5-line fas"></i><span
                            class="hide-menu"> Inport &
                            Export Services
                        </span></a></li>
                <li class="sidebar-item ft7"><a href="#." class="sidebar-link"><i class="ri-settings-5-fill fas"></i><span
                            class="hide-menu"> Inport &
                            Export Materials
                        </span></a></li>
                @else
					
                <li class="sidebar-item">
					<a class="sidebar-link waves-effect waves-dark sidebar-link ft1" href="{{ route('home') }}"  aria-expanded="false">
					<i class="fas fa-home"></i><span class="hide-menu">Dashboard</span></a>
                 </li>

                <li class="sidebar-item ft2">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="fas ri-group-fill fs20" style="font-size: 16px;"></i> <span class="hide-menu">Profiles</span></a>
                    <ul aria-expanded="false" class="collapse first-level ft2_sub">
 						<li class="sidebar-item">
							<a href="{{ route('users.index') }}" class="sidebar-link fs18"><i class="ri-group-line"></i><span class="hide-menu">Customers </span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{ route('technicians.index') }}" class="sidebar-link fs18"><i class="ri-contacts-line"></i><span class="hide-menu">Technicians </span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{ route('dispatcher.index') }}" class="sidebar-link fs18"><i class="ri-admin-line"></i><span class="hide-menu">Dispatchers </span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{ route('multiadmin.index') }}" class="sidebar-link fs18"><i class="ri-admin-fill"></i><span class="hide-menu">Admin </span></a>
						</li>
					</ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark ft3" href="#" aria-expanded="false"><i class="fas fa-calendar-check"></i> <span class="hide-menu">Jobs</span></a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
							<a href="{{ route('schedule') }}" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu">Schedule </span></a>
						</li>            
                        <li class="sidebar-item">
							<a href="{{ route('map') }}" class="sidebar-link"><i class="fas fa-calendar-check"></i><span class="hide-menu">Reschedule </span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('tickets.index') }}" class="sidebar-link"><i class="mdi mdi-book-multiple"></i><span class="hide-menu"> Jobs List </span></a>
                        </li>
                         <li class="sidebar-item"><a href="{{ route('events') }}" class="sidebar-link"><i
                                    class="mdi mdi-book-multiple"></i><span class="hide-menu"> Event List
                                </span></a></li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark ft4" href="#" aria-expanded="false"><i class="fas ri-list-check-2"></i><span class="hide-menu">Price Book</span></a>
                     <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
							<a href="{{ route('services.index') }}" class="sidebar-link"><i class="ri-book-line"></i> <span class="hide-menu">Services </span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('product.index') }}" class="sidebar-link"><i class="ri-book-mark-line"></i> <span class="hide-menu">Parts </span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{route('assign_product')}}" class="sidebar-link"><i class="ri-book-2-line"></i> <span class="hide-menu">Assign Parts </span></a>
                        </li>
						<li class="sidebar-item">
							<a href="{{route('manufacturer.index')}}" class="sidebar-link"><i class="ri-building-3-line"></i> <span class="hide-menu"> Manufacturers</span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('estimate.index') }}" class="sidebar-link"><i class="ri-article-line"></i> <span class="hide-menu">Estimate Templates </span></a>
						</li>
                     </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark ft5" href="#" aria-expanded="false"><i class="fas fa-clipboard-list "></i><span class="hide-menu">Payments</span></a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
							<a href="{{ route('payment-list') }}" class="sidebar-link"><i class="ri-money-dollar-box-line"></i><span class="hide-menu"> Payments</span></a>
						</li>
                        <li class="sidebar-item">
							<a href="#." class="sidebar-link"><i class="far ri-price-tag-line"></i><span class="hide-menu">Invoices</span></a>
						</li>
                        <li class="sidebar-item">
							<a href="#." class="sidebar-link"><i class="far ri-price-tag-2-line"></i><span class="hide-menu">Estimates</span></a>
						</li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark ft6" href="#" aria-expanded="false"><i class="fas fa-chart-line" style="font-size: 16px;"></i><span class="hide-menu">Reports</span></a>
                    <ul aria-expanded="false" class="collapse first-level">
						<li class="sidebar-item">
							<a href="{{route('jobreport.index')}}" class="sidebar-link"><i class="ri-file-chart-line"></i> <span class="hide-menu">Jobs</span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{route('technicianreport.index')}}" class="sidebar-link"><i class="ri-file-chart-line"></i> <span class="hide-menu">Technicians</span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{route('employeereport.index')}}" class="sidebar-link"><i class="ri-file-chart-line"></i> <span class="hide-menu">Employees </span></a>
						</li>
                         <li class="sidebar-item">
							<a href="{{route('performanncematrix')}}" class="sidebar-link"><i class="ri-file-chart-line"></i> <span class="hide-menu">Performance Matrix </span></a>
						</li>
                     </ul>
                </li>
				
				<li class="sidebar-item">
					<a class="sidebar-link waves-effect waves-dark sidebar-link ft1" href="{{ route('app_chats') }}"  aria-expanded="false">
					<i class="ri-message-2-line"></i><span class="hide-menu">Messages</span></a>
                 </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow waves-effect waves-dark ft7" href="#" aria-expanded="false"><i class="fas ri-settings-2-line"></i><span class="hide-menu">Settings</span></a>
                    <ul aria-expanded="false" class="collapse first-level">
 						<li class="sidebar-item">
							<a href="{{ route('buisnessprofile.index') }}" class="sidebar-link"><i class="ri-file-list-line"></i> <span class="hide-menu">Business Profile </span></a>
						</li>
                        <li class="sidebar-item ft2">
							<a href="{{ route('businessHours.business-hours') }}" class="sidebar-link"><i class="ri-24-hours-line fas"></i> <span class="hide-menu">Working Hours </span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('manufacturer.index') }}" class="sidebar-link"><i class="ri-building-2-line"></i> <span class="hide-menu">Manufacturer</span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('tax.index') }}" class="sidebar-link"><i class="ri-bar-chart-fill"></i> <span class="hide-menu"> Tax</span></a>
                        </li>
						<li class="sidebar-item">
							<a href="{{ route('servicearea.index') }}" class="sidebar-link"><i class="ri-service-line"></i> <span class="hide-menu">Service Area </span></a>
						</li>
						<li class="sidebar-item">
							<a href="{{ route('lead.lead-source') }}" class="sidebar-link"><i class="ri-focus-2-line"></i> <span class="hide-menu"> Lead Source </span></a>
                        </li>
                        <li class="sidebar-item">
							<a href="{{ route('tags.tags-list') }}" class="sidebar-link"><i class="fas fa-tag"></i> <span class="hide-menu"> Tags </span></a>
						</li>
                        <li class="sidebar-item">
							<a href="{{ route('site_job_fields') }}" class="sidebar-link"><i class="fas fa-tags "></i> <span class="hide-menu"> Job Fields </span></a>
                        </li>
                    </ul>
                </li>
				
				<li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link ft8" href="#." aria-expanded="false"><i class="fas fa-power-off "></i><span class="hide-menu">Log Out</span></a>
                </li>
                @endif

            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
