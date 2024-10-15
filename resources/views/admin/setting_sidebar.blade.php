 

if sidebar OFF 
{
	NO NEED TO PRINT 
}
else 
{
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">

            <div style="height: 10px;"></div>

            <ul id="sidebarnav">
                
				<li class="sidebar-item ft1">
					<a href="{{ route('buisnessprofile.index') }}" class="sidebar-link"><i class="ri-file-list-line fas"></i> <span class="hide-menu">Business Profile </span></a>
				</li>
				<li class="sidebar-item ft2">
					<a href="{{ route('businessHours.business-hours') }}" class="sidebar-link"><i class="ri-24-hours-line fas"></i> <span class="hide-menu">Working Hours </span></a>
				</li>
				<li class="sidebar-item ft3">
					<a href="{{ route('servicearea.index') }}" class="sidebar-link"><i class="ri-service-line fas"></i> <span class="hide-menu">Service Area </span></a>
				</li>
				<li class="sidebar-item ft4">
					<a href="{{ route('manufacturer.index') }}" class="sidebar-link"><i class="ri-building-2-line fas"></i> <span class="hide-menu">Manufacturer</span></a>
				</li>
				<li class="sidebar-item ft5">
					<a href="{{ route('tax.index') }}" class="sidebar-link"><i class="ri-bar-chart-fill fas"></i> <span class="hide-menu"> Tax</span></a>
				</li>
				<li class="sidebar-item ft6">
					<a href="{{ route('lead.lead-source') }}" class="sidebar-link"><i class="ri-focus-2-line fas"></i> <span class="hide-menu"> Lead Source </span></a>
				</li>
				<li class="sidebar-item ft7">
					<a href="{{ route('tags.tags-list') }}" class="sidebar-link"><i class="fas fa-tags"></i> <span class="hide-menu"> Tags </span></a>
				</li>
				<li class="sidebar-item ft8">
					<a href="{{ route('site_job_fields') }}" class="sidebar-link"><i class="fas ri-command-fill"></i> <span class="hide-menu"> Job Fields </span></a>
				</li>
				<li class="sidebar-item ft8">
					<a href="{{ route('predefined-replies') }}" class="sidebar-link"><i class="fas fa-comment-dots"></i> <span class="hide-menu"> Predefine Reply </span></a>
				</li>
 						
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

}
