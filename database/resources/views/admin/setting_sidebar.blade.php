<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">

            <div style="height: 10px;"></div>

            <ul id="sidebarnav">
                <li class="sidebar-item"><a href="{{route('servicearea.index')}}"
                        class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Service Area
                        </span></a></li>

                <li class="sidebar-item"><a href="{{route('buisnessprofile.index')}}"
                        class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Business
                            Profile </span></a></li>
                <li class="sidebar-item"><a
                        href="{{route('businessHours.business-hours')}}"
                        class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Working
                            Hours </span></a></li>
                <li class="sidebar-item"><a href="{{route('manufacturer.index')}}"
                        class="sidebar-link"><i class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Manufacturer
                        </span></a></li>
                <li class="sidebar-item"><a href="{{ route('tax.index') }}" class="sidebar-link"><i
                            class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Tax </span></a></li>

                <li class="sidebar-item"><a href="{{ route('lead.lead-source') }}" class="sidebar-link"><i
                            class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Lead Source </span></a></li>
                <li class="sidebar-item"><a href="{{ route('tags.tags-list') }}" class="sidebar-link"><i
                            class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Tags </span></a></li>
                <li class="sidebar-item"><a href="{{ route('site_job_fields') }}" class="sidebar-link"><i
                            class="mdi mdi-book-multiple"></i> <span class="hide-menu"> Job Fields </span></a></li>
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
