<div class="col-md-3 bg-light mt-2 p-0 card card-shadow card-border" id="jobdiv">

    <ul class="list-group scroll-container reschedule_user_list" style="max-height: 490px; overflow-y: auto;">

        @if (isset($data) && !empty($data->count()))
            @foreach ($data as $key => $value)
                <li class="list-group-item" id="event_click{{ $value->job_id }}"
                    style="cursor: pointer;">
                    <h6 class="uppercase mb-0 text-truncate">{{ $value->subject }}</h6>
                            <small class="text-muted"><i class="ri-user-line"></i>  {{ $value->name }}</small>

    
                            <div class="ft14"><i class="ri-map-pin-fill"></i>  {{ $value->address . ', ' . $value->city . ', ' . $value->state }}</div>

                </li>
            @endforeach
        @else
            <li class="list-group-item mb-2" id="event_click" style="cursor: pointer;">
                <span style="font-size: 15px;font-weight: 700;letter-spacing: 1px;">
                    No Data Found Today</span><br />
            </li>
        @endif
    </ul>
</div>