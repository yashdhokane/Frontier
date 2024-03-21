<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">

            <div style="height: 10px;"></div>

            <ul id="sidebarnav">
				<li class="sidebar-item">
					<a class="sidebar-link waves-effect waves-dark sidebar-link ft1" href="#."  aria-expanded="false">
					<i class="fas ri-calendar-line"></i><span class="hide-menu">Date</span></a>
				</li>
				<li class="sidebar-item">
					<a class="sidebar-link waves-effect waves-dark sidebar-link ft1" href="#."  aria-expanded="false">
					<i class="fas ri-user-2-fill"></i><span class="hide-menu">Technicians</span></a>
				</li>
                <li class="sidebar-item">
                   
                        @if (request()->is('schedule'))
                            <li class="sidebar-item"><a href="#" class="sidebar-link">
                                    <span class="hide-menu">
                                        {{-- to show technician in sidebar  --}}
                                        @php
                                            $tech = DB::table('users')->where('role', 'technician')->get();
                                        @endphp

                                        <div class="bg-white h-100" id="filterSchedule">

                                            <div class="mx-sm-n4">
                                                <div id="datepicker-container" class="">
                                                    <input type="text" id="datepicker">
                                                </div>

                                            </div>
                                            <hr>
                                            <div>
                                                <h4>TECHNICIAN</h4>
                                                <input type="text" name="searchTechnician" id="searchTechnician"
                                                    class="form-control mb-4 border-black">
                                                @foreach ($tech as $k => $item)
                                                    <div class="d-flex gap-3 technician-item">
                                                        <input type="checkbox" class="technician_check"
                                                            data-id="{{ $item->id }}" id="tech{{ $k }}"
                                                            style="transform: scale(1.5);"
                                                            {{ $item->status == 'active' ? 'checked' : '' }}>
                                                        <label for="tech{{ $k }}"
                                                            class="fs-4">{{ $item->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>


                                        </div>
                                    </span></a>
                            </li>
							
                        @endif
                    </li>
					
					
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
