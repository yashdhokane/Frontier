            <div class="col-md-3 mt-1">
                <div class="flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Time Period</b></label>
                    <select id="date-type-select" class="form-control select2 filter-input">
                        <option value="">Select Date</option>
                        <option value="custom">Custom</option>
                        <option value="lastweek">Last Week</option>
                        <option value="last2week">Last 2 Week</option>
                        <option value="last3week">Last 3 Week</option>
                        <option value="lastmonth">Last Month</option>
                        <option value="last6week">Last 6 Week</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Job Title</b></label>
                    <select id="title-filter" class="form-control filter-input select2 tito" multiple="multiple">
                        @foreach($title as $item)
                            <option value="{{ $item->field_name }}">{{ $item->field_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Services</b></label>
                    <select id="services-filter" class="form-control w-100 select2 filter-input" multiple="multiple">
                        @foreach ($serviceCat as $category)
                            <option class="text-black fw-bold" disabled>{{ $category->category_name }}</option>
                            @if (isset($category->Services) && count($category->Services) > 0)
                                @foreach ($category->Services as $service)
                                    <option class="ps-3" value="{{ $service->service_id }}"
                                        data-code="{{ $service->service_code }}">
                                        {{ $service->service_name }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

             <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Parts</b></label>
                    <select id="products-filter" class="form-control w-100 select2 filter-input" multiple="multiple">
                        @if (isset($getProduct) && !empty($getProduct))
                            @foreach ($getProduct as $value)
                                <option value="{{ $value->product_id }}" data-code="{{ $value->product_code }}">
                                    {{ $value->product_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>


             <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Published</b></label>
                    <select id="IsPublished-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

             <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Confirmed</b></label>
                    <select id="IsConfirmed-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Status</b></label>
                    <select id="job-status-filter" class="form-control filter-input select2" multiple="multiple">
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job on Schedule</b></label>
                    <select id="showOnSchedule-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

           
            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Technician</b></label>
                    <select id="technician-select" class="form-control filter-input select2" style="width: 100%;" multiple="multiple">
                        @foreach($technician as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Customer</b></label>
                    <select id="customer-select" class="form-control filter-input" style="width: 100%;" multiple="multiple">
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Appliances</b></label>
                    <select id="appliances-filter" class="form-control filter-input select2" multiple="multiple">
                        @foreach ($appliance as $value)
                            <option value="{{ $value->appliance_type_id }}">
                                {{ $value->appliance_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Warranty</b></label>
                    <select id="warranty-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="in_warranty">In Warranty</option>
                        <option value="out_warranty">Out of Warranty</option>
                    </select>
                </div>
            </div>


            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Priority</b></label>
                    <select id="priority-filter" class="form-control filter-input select2 pito" multiple="multiple">
                        <option value="critical">Critical</option>
                        <option value="emergency">Emergency</option>
                        <option value="high">High</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Customer Tags</b></label>
                    <select id="customer-tags-filter" class="form-control filter-input select2" multiple="multiple">
                        @foreach ($tagsList as $value)
                            <option value="{{ $value->tag_id  }}">
                                {{ $value->tag_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Flag Customer</b></label>
                    <select id="flag-filter" class="form-control filter-input select2" multiple="multiple">
                         @foreach($FlagJob as $item)
                            <option value="{{ $item->flag_id  }}">{{ $item->flag_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-1">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Frequency</b></label>
                    <select id="frequency-filter" class="form-control filter-input">
                        <option value="">All</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
            </div>

            <!-- Weekly Days Dropdown (Hidden by Default) -->
            <div class="col-md-3 mt-1" id="weekly-from-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b> Day</b></label>
                    <select id="weekly-from-filter" class="form-control filter-input select2">
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
            </div>

            <!-- Monthly Dates Dropdown (Hidden by Default) -->
            <div class="col-md-3 mt-1" id="monthly-from-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>From Date</b></label>
                    <input id="monthly-from-filter" type="date" class="form-control filter-input">
                </div>
            </div>
            
             
            <div class="col-md-3 mt-1" id="start-date-div">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Start Date</b></label>
                    <input id="start-date-filter" type="date" class="form-control filter-input">

                </div>
            </div>

            <div class="col-md-3 mt-1"  id="end-date-div">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>End Date</b></label>
                    <input id="end-date-filter" type="date" class="form-control filter-input">

                </div>
            </div>

