   <div class="card-body card-border shadow">

                            <h5 class="card-title uppercase">schedule</h5>

                            @if ($schedule->isEmpty())
                            <div class="alert alert-secondary mt-4 col-md-12" role="alert">
                                Schedule details not available for {{ $user->name ?? '' }}. <strong><a
                                        href="{{ route('schedule') }}">Add New</a></strong>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-body">
                                    <ul class="timeline timeline-left">
                                        @foreach ($schedule as $scheduleItem)
                                        @if (isset($scheduleItem))
                                        <li class="timeline-inverted timeline-item">
                                            <div class="timeline-badge
                                                     @if ($scheduleItem->schedule_type === 'job') secondary
                                                            @elseif ($scheduleItem->schedule_type === 'event')
                                                             success @endif">
                                                <span class="fs-2">
                                                    @if ($scheduleItem->schedule_type === 'job')
                                                    T
                                                    @elseif ($scheduleItem->schedule_type === 'event')
                                                    <i class="ri-cpu-fill fs-7"></i>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="timeline-panel shadow">
                                                <div class="timeline-heading">
                                                    <h5 class="timeline-title uppercase">
                                                        <i class="ri-time-line align-middle"></i>
                                                        {{ $convertDateToTimezone($scheduleItem->created_at) }}
                                                        <span class="ft12">
                                                            @if ($scheduleItem->schedule_type === 'job' ||
                                                            $scheduleItem->event->event_type === 'partial')
                                                            {{ $convertTimeToTimezone($scheduleItem->start_date_time,
                                                            'H:i:a') }}
                                                            to
                                                            {{ $convertTimeToTimezone($scheduleItem->end_date_time,
                                                            'H:i:a') }}
                                                            @elseif ($scheduleItem->event->event_type === 'full')
                                                            FULL DAY
                                                            @endif
                                                        </span>
                                                    </h5>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="row mt1">
                                                        <div class="col-md-12">
                                                            <div class="mb-2">
                                                                <h5 class="card-title uppercase">
                                                                    @if ($scheduleItem->schedule_type === 'job')
                                                                    {{ $scheduleItem->JobModel->job_title ?? null }}
                                                                    @elseif ($scheduleItem->schedule_type === 'event')
                                                                    {{ $scheduleItem->event->event_name ?? null }}
                                                                    @endif
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            @if ($scheduleItem->schedule_type === 'job')
                                                            {{ $scheduleItem->JobModel->description ?? null }}
                                                            @elseif ($scheduleItem->schedule_type === 'event')
                                                            {{ $scheduleItem->event->event_description ?? null }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if ($scheduleItem->schedule_type === 'job')
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <div class="mb-2"><strong>Customer
                                                                    Name:</strong>
                                                                {{ $scheduleItem->JobModel->user->name ?? '' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Address:</strong>
                                                                @if(isset($scheduleItem->JobModel->addresscustomer->address_line1)
                                                                &&
                                                                $scheduleItem->JobModel->addresscustomer->address_line1
                                                                !== '')
                                                                {{
                                                                $scheduleItem->JobModel->addresscustomer->address_line1
                                                                }},
                                                                @endif

                                                                @if(isset($scheduleItem->JobModel->addresscustomer->address_line2)
                                                                &&
                                                                $scheduleItem->JobModel->addresscustomer->address_line2
                                                                !== '')
                                                                {{
                                                                $scheduleItem->JobModel->addresscustomer->address_line2
                                                                }},
                                                                @endif

                                                                @if(isset($scheduleItem->JobModel->addresscustomer->city)
                                                                && $scheduleItem->JobModel->addresscustomer->city !==
                                                                '')
                                                                {{ $scheduleItem->JobModel->addresscustomer->city }},
                                                                @endif

                                                                @if(isset($scheduleItem->JobModel->addresscustomer->state_name)
                                                                && $scheduleItem->JobModel->addresscustomer->state_name
                                                                !== '')
                                                                {{ $scheduleItem->JobModel->addresscustomer->state_name
                                                                }},
                                                                @endif

                                                                @if(isset($scheduleItem->JobModel->addresscustomer->zipcode)
                                                                && $scheduleItem->JobModel->addresscustomer->zipcode !==
                                                                '')
                                                                {{ $scheduleItem->JobModel->addresscustomer->zipcode }}
                                                                @endif
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Mobile:</strong>
                                                                {{ $scheduleItem->JobModel->user->mobile ?? '' }}
                                                            </div>
                                                            <div class="mb-2"><strong>Email:</strong>
                                                                {{ $scheduleItem->JobModel->user->email ?? '' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mb-2">
                                                                <strong>Duration:</strong>
                                                                @php
                                                                $minutes =
                                                                $scheduleItem->JobModel->jobassignname->duration ?? 0;
                                                                $hours = intdiv($minutes, 60);
                                                                $remaining_minutes = $minutes % 60;
                                                                $duration = ($hours > 0 ? $hours . ' hour' . ($hours > 1
                                                                ? 's' : '') : '') .
                                                                ($remaining_minutes > 0 ? ' ' . $remaining_minutes . '
                                                                minute' . ($remaining_minutes > 1 ? 's' : '') : '');
                                                                @endphp

                                                                {{ $duration }}

                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Priority:</strong>
                                                                {{ $scheduleItem->JobModel->priority ?? '' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mb-2">
                                                                <strong>Appliances:</strong>
                                                                {{
                                                                $scheduleItem->JobModel->JobAppliances->Appliances->appliance->appliance_name
                                                                ?? '' }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Manufacturer:</strong>
                                                                {{
                                                                $scheduleItem->JobModel->JobAppliances->Appliances->manufacturer->manufacturer_name
                                                                ?? '' }}
                                                            </div>
                                                            <div class="mb-2"><strong>Model
                                                                    Number:</strong>
                                                                {{
                                                                $scheduleItem->JobModel->JobAppliances->Appliances->model_number
                                                                ?? '' }}
                                                            </div>
                                                            <div class="mb-2"><strong>Serial
                                                                    Number:</strong>
                                                                {{
                                                                $scheduleItem->JobModel->JobAppliances->Appliances->serial_number
                                                                ?? '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                        @endforeach



                                    </ul>
                                </div>
                            </div>
                            @endif

                        </div>