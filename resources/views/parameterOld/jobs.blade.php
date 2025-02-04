<div class="row"> 
    <div class="col-sm-3 mb-2">
        <label class="text-nowrap"><strong>Filter By</strong></label>
        <select id="date-filter" class="form-control">
            <option value="">All</option>
            <option value="Last Month">Last Month</option>
            <option value="Last 6 Weeks">Last 6 Weeks</option>
            <option value="Last 3 Months">Last 3 Months</option>
            <option value="Last Year">Last Year</option>
        </select>
    </div>     
</div> 

<div id="schedule-container" class="row">     
    @foreach ($schedules as $schedule)
        @php
            $job = $schedule->JobModel;
        @endphp
        @if ($job)
            <div class="col-md-4 mb-3 schedule-item" 
                    data-start-date="{{ \Carbon\Carbon::parse($schedule->start_date_time)->format('Y-m-d') }}">
                <div class="card shadow-sm h-100 pp_job_info_full">
                    <div class="card-body card-border card-shadow">
                    
                        <h5 class="card-title py-1 d-flex justify-content-between">
                            <strong class="text-uppercase">
                                #{{ $job->id }} 
                                @if ($job->warranty_type === 'in_warranty')
                                    <span class="badge bg-warning">In Warranty</span>
                                @elseif ($job->warranty_type === 'out_warranty')
                                    <span class="badge bg-danger">Out of Warranty</span>
                                @endif
                            </strong>
                        </h5>
                        <div class="pp_job_info pp_job_info_box">
                            <h6 class="text-uppercase">
                                {{ $job->job_title && strlen($job->job_title) > 20 ? substr($job->job_title, 0, 20) . '...' : $job->job_title }}
                            </h6>
                            <div class="description_info text-truncate">{{ $job->description }}</div>
                            <div class="pp_job_date text-primary">
                            @if ($schedule->start_date_time && $schedule->end_date_time)
                                    {{
                                        \Carbon\Carbon::parse($schedule->start_date_time)->format('M j Y g:i A') .
                                        ' - ' .
                                        \Carbon\Carbon::parse($schedule->end_date_time)->format('g:i A')
                                    }}
                                @endif
                            </div>
                        </div>
                        <div class="pp_user_info pp_job_info_box">
                            <h6 class="text-uppercase">
                                <i class="fas fa-user pe-2 fs-2"></i> {{ $job->user->name ?? '' }}
                            </h6>
                            <div>
                                {{ $job->addresscustomer->address_line1 ?? '' }},
                                {{ $job->addresscustomer->zipcode ?? '' }}
                            </div>
                            <div>{{ $job->user->mobile ?? '' }}</div>
                        </div>
                        <div class="pp_job_info_box">
                            <h6 class="text-uppercase">Equipment</h6>
                            <div>
                                {{ $job->JobAppliances->Appliances->appliance->appliance_name ?? '' }} /
                                {{ $job->JobAppliances->Appliances->manufacturer->manufacturer_name ?? '' }} /
                                {{ $job->JobAppliances->Appliances->model_number ?? '' }} /
                                {{ $job->JobAppliances->Appliances->serial_number ?? '' }}
                            </div>
                        </div>
                        <div class="pp_job_info_box">
                            <h6 class="text-uppercase">Parts & Services</h6>
                            <div>
                                {{ $job->jobproductinfohasmany->pluck('product.product_name')->join(', ') ?? '' }}
                                {{ $job->jobserviceinfohasmany->pluck('service.service_name')->join(', ') ?? '' }}
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="btn-group">
                                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#MessageModal" data-job-id="{{ $job->id }}">
                                        <i class="ri-send-plane-line align-middle"></i> Send Message
                                    </button>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</div>
<!-- Modal -->
<div class="modal fade" id="MessageModal" tabindex="-1" aria-labelledby="MessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MessageModalLabel">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="job-id" value="">
                <div class="mb-3">
                    <label for="message">Message:</label>
                    <textarea class="form-control" id="message" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitMessage">Submit</button>
            </div>
        </div>
    </div>
</div>