<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav mt-4 mb-4">

             <ul id="sidebarnav">
				 
				<li class="sidebar-item">
				
					<a class="sidebar-link waves-effect waves-dark sidebar-link ft1" href="#."  aria-expanded="false"><i class="fas ri-user-2-fill"></i><span class="hide-menu">Technicians</span></a>
                    
					@if (request()->is(['schedule', 'demo']))
					<div class="sidebar-item"><a href="#" class="sidebar-link">
						<span class="hide-menu">
							{{-- to show technician in sidebar  --}}
							@php
								$tech = DB::table('users')->where('role', 'technician')->where('status', 'active')->get();
							@endphp

							<div class="bg-white h-100 mx-3" id="filterSchedule">
							   <div>
								<input type="text" name="searchTechnician" id="searchTechnician" class="form-control mb-4 border-black" />
								@foreach ($tech as $k => $item)
									<div class="d-flex gap-2 mb-1 technician-item">
										<input type="checkbox" class="technician_check"
											data-id="{{ $item->id }}" id="tech{{ $k }}"  style="/*! transform: scale(1.5); */ height: 20px; width: 20px;"
											{{ $item->status == 'active' ? 'checked' : '' }}>
										<label for="tech{{ $k }}"  class="">{{ $item->name }}</label>
									</div>
								@endforeach
								</div>
							</div>
						</span></a>
					</div>
					@endif
						
				</li>
					
					
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
