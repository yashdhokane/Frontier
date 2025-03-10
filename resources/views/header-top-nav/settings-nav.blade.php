<div class="col-7 text-end px-4">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <a href="{{ route('buisnessprofile.index') }}"
            class="btn {{ Route::currentRouteName() === 'buisnessprofile.index' ? 'btn-info' : 'btn-secondary text-white' }}">
            Business Profile</a>
        <a href="{{ route('businessHours.business-hours') }}"
            class="btn {{ Route::currentRouteName() === 'businessHours.business-hours' ? 'btn-info' : 'btn-secondary text-white' }}">Workings
            Hours</a>
        <a href="{{ route('servicearea.index') }}"
            class="btn {{ Route::currentRouteName() === 'servicearea.index' ? 'btn-info' : 'btn-secondary text-white' }}">Service
            Area</a>
        <a href="{{ route('manufacturer.index') }}"
            class="btn {{ Route::currentRouteName() === 'manufacturer.index' ? 'btn-info' : 'btn-secondary text-white' }}">Manufaturer</a>
        <a href="{{ route('tax.index') }}"
            class="btn {{ Route::currentRouteName() === 'tax.index' ? 'btn-info' : 'btn-secondary text-white' }}">Tax</a>
        <a href="{{ route('parameters') }}"
            class="btn {{ Route::currentRouteName() === 'parameters' ? 'btn-info' : 'btn-secondary text-white' }}">Parameters</a>
        <div class="btn-group" role="group">
            <button id="btnGroupDrop1" type="button"
                class="btn {{ Route::currentRouteName() === 'lead.lead-source' || Route::currentRouteName() === 'tags.tags-list' || Route::currentRouteName() === 'site_job_fields' ? 'btn-info' : 'btn-secondary text-white' }} dropdown-toggle"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More
            </button>
            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item {{ Route::currentRouteName() === 'lead.lead-source' ? 'btn-info' : 'text-info' }}"
                    href="{{ route('lead.lead-source') }}">Lead Source</a>
                <a class="dropdown-item {{ Route::currentRouteName() === 'tags.tags-list' ? 'btn-info' : 'text-info' }}"
                    href="{{ route('tags.tags-list') }}">Tags</a>
                <a class="dropdown-item {{ Route::currentRouteName() === 'site_job_fields' ? 'btn-info' : 'text-info' }}"
                    href="{{ route('site_job_fields') }}">Job Fields</a>

            </div>
        </div>
    </div>
</div>