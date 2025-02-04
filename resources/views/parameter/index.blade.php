@extends('home')

@section('content')

<div class="page-breadcrumb pt-2">
    <div class="row">
        <div class="col-4 align-self-center">
            <h4 class="page-title">Parameters</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Parameters</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-4 d-flex justify-content-evenly gap-2">

            <div class="flex-column align-items-baseline">
                <label class="text-nowrap"><b>Select Type</b></label>
                <select id="type-select" class="form-control select2" style="width: 100%;">
                    <option value="jobs">Jobs</option>
                    <option value="products">Products</option>
                </select>
            </div>

            @if($savedFilters)
                <div class="flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Previous Parameters</b></label>
                    <select id="saved-filters-dropdown" class="form-control select2">
                        <option value="">Select Parameters</option>
                        @foreach($savedFilters as $filter)
                            <option value="{{ $filter->id }}" data-filter="{{ json_encode($filter) }}">
                                {{ $filter->filter_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

        </div>
        <div class="col-4">
            <button class="btn btn-primary float-end">Download</button>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row" id="job-filter-rows">

<div class="col-md-3">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Job Title</b></label>
                    <select id="title-filter" class="form-control filter-input select2 tito">
                        <option value="">All</option>
                        @foreach($title as $item)
                            <option value="{{ $item->field_name }}">{{ $item->field_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

              <div class="col-md-3">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Priority</b></label>
                    <select id="priority-filter" class="form-control filter-input select2 pito">
                        <option value="">All</option>
                        <option value="critical">Critical</option>
                        <option value="emergency">Emergency</option>
                        <option value="high">High</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                    </select>
                </div>
            </div>

             <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Services</b></label>
                    <select id="services-filter" class="form-control w-100 select2 filter-input" multiple="multiple">
                        <option value="">All</option>
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

             <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Products</b></label>
                    <select id="products-filter" class="form-control w-100 select2 filter-input" multiple="multiple">
                        <option value="">All</option>
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

             <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Published</b></label>
                    <select id="IsPublished-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

             <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Confirmed</b></label>
                    <select id="IsConfirmed-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job Status</b></label>
                    <select id="job-status-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Job on Schedule</b></label>
                    <select id="showOnSchedule-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Start Date</b></label>
                    <input id="start-date-filter" type="date" class="form-control filter-input">

                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>End Date</b></label>
                    <input id="end-date-filter" type="date" class="form-control filter-input">

                </div>
            </div>

            <div class="col-md-3">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Technician</b></label>
                    <select id="technician-select" class="form-control filter-input select2" style="width: 100%;">
                        <option value="">All</option>
                        @foreach($technician as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>Customer</b></label>
                    <select id="customer-select" class="form-control filter-input" style="width: 100%;">
                        <option value="">All</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Warranty</b></label>
                    <select id="warranty-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        <option value="in_warranty">In Warranty</option>
                        <option value="out_warranty">Out of Warranty</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Flag Customer</b></label>
                    <select id="flag-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                         @foreach($FlagJob as $item)
                            <option value="{{ $item->flag_id  }}">{{ $item->flag_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Manufacturer</b></label>
                    <select id="manufacturer-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                         @foreach ($manufacture as $manufacturer)
                              <option value="{{ $manufacturer->id }}">
                                  {{ $manufacturer->manufacturer_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Appliances</b></label>
                    <select id="appliances-filter" class="form-control filter-input select2">
                        <option value="">All</option>
                        @foreach ($appliance as $value)
                            <option value="{{ $value->appliance_type_id }}">
                                {{ $value->appliance_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Customer Tags</b></label>
                    <select id="customer-tags-filter" class="form-control filter-input select2" multiple="multiple">
                        <option value="">All</option>
                        @foreach ($tagsList as $value)
                            <option value="{{ $value->tag_id  }}">
                                {{ $value->tag_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
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
            <div class="col-md-3" id="weekly-from-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>From Day</b></label>
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
            <div class="col-md-3" id="weekly-to-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>To Day</b></label>
                    <select id="weekly-to-filter" class="form-control filter-input select2">
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
            <div class="col-md-3" id="monthly-from-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>From Date</b></label>
                    <input id="monthly-from-filter" type="date" class="form-control filter-input">
                </div>
            </div>
            <div class="col-md-3" id="monthly-to-container" style="display: none;">
                <div class="d-flex flex-column align-items-baseline">
                    <label class="text-nowrap"><b>To Date</b></label>
                    <input id="monthly-to-filter" type="date" class="form-control filter-input">
                </div>
            </div>


        </div>

        <div class="row" id="product-filter-rows">

            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Stock</b></label>
                    <select id="stock-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out Of Stock</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Category</b></label>
                    <select id="category-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          @foreach ($product as $product)
                              <option value="{{ $product->id }}">
                                  {{ $product->category_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Manufacturer</b></label>
                    <select id="manufacturer-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          @foreach ($manufacture as $manufacturer)
                              <option value="{{ $manufacturer->id }}">
                                  {{ $manufacturer->manufacturer_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Supppliers </b></label>
                    <select id="supppliers-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                         @foreach ($vendor as $item)
                           <option value="{{ $item->vendor_id }}">
                                  {{ $item->vendor_name }}</option>
                          @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="d-flex flex-column  align-items-baseline">
                    <label class="text-nowrap"><b>Status</b></label>
                    <select id="status-filter" class="form-control filter-products-inputs select2">
                        <option value="">All</option>
                          <option value="Publish">Active</option>
                          <option value="Draft">Inactive</option>
                    </select>
                </div>
            </div>
        
        </div>

        <div class="mt-2 float-end">
            <button type="button" class="btn btn-primary me-5" id="save-filters-modal">Save</button>
        </div>

    </div>
</div>

@php
    $time_interval = Session::get('time_interval', 0);
@endphp
<div class="card">
    <div class="card-body">
        <div class="table-responsive table-custom">

            <table id="zero_configParam" class="table table-hover table-striped table-bordered text-nowrap">
                <div class="d-flex flex-wrap">
                    <thead>
                        <tr>
                            <th>Job ID</th>
                            <th class="job-details-column">Job Details</th>
                            <th>Customer</th>
                            <th>Technician</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold link"><span
                                            class="mb-1 badge bg-primary">{{ $ticket->id }}</span></a>
                                </td>
                                <td class="job-details-column">
                                    <div class="text-wrap2 d-flex">
                                        <div class=" text-truncate w-25">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">
                                                {{ $ticket->job_title ?? null }}</a>
                                        </div>
                                        <span
                                            class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                                    </div>
                                    <div style="font-size:12px;">
                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                            {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                        @endif
                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                            {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                        @endif
                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
                                            {{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
                                        @endif
                                        @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->serial_number)
                                            {{ $ticket->JobAppliances->Appliances->serial_number ?? null }}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($ticket->user)
                                        {{ $ticket->user->name }}
                                    @else
                                        Unassigned
                                    @endif
                                </td>
                                <td>
                                    @if ($ticket->technician)
                                        {{ $ticket->technician->name }}
                                    @else
                                        Unassigned
                                    @endif
                                </td>
                                <td>
                                    @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                                        <div class="font-medium link">
                                            {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                                        </div>
                                    @else
                                        <div></div>
                                    @endif
                                    <div style="font-size:12px;">
                                        {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                        to
                                        {{ $modifyDateTime($ticket->jobassignname->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
            </table>

            <table class="table product-overview" id="product-table">                   
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Parts</th>
                              <th>Category</th>
                              <th>Manufacturer</th>
                              <th>Price</th>
                              <th>status</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($products as $index => $item)
                              <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <td>
                                      <div class="d-flex align-items-center">

                                          @if ($item->product_image)
                                              <img src="{{ asset('public/product_image/' . $item->product_image) }}"
                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                  width="45" />
                                          @else
                                              <img src="{{ asset('public/images/default-part-image.png') }}"
                                                  alt="{{ $item->product_name }}" class="rounded-circle"
                                                  width="45" />
                                          @endif


                                          <div class="ms-2">
                                              <div class="user-meta-info"><a
                                                      href="{{ route('product.edit', ['product_id' => $item->product_id]) }}">
                                                      <h6 class="user-name mb-0" data-name="name">
                                                          {{ $item->product_name }}</h6>
                                                  </a></div>
                                          </div>
                                      </div>
                                  </td>
                                  <td>{{ $item->categoryProduct->category_name ?? null }}</td>
                                  <td>{{ $item->manufacturername->manufacturer_name ?? null }}</td>
                                  <td>${{ $item->base_price ?? '' }}</td>
                                  <td>
                                      @if ($item->status == 'Publish')
                                          Active
                                      @elseif($item->status == 'Draft')
                                          Inactive
                                      @endif
                                  </td>

                              </tr>
                          @endforeach
                      </tbody>
                  </table>

        </div>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="filterNameModal" tabindex="-1" role="dialog" aria-labelledby="filterNameModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterNameModalLabel">Enter Name</h5>

            </div>
            <div class="modal-body">
                <input type="text" id="filter-name" class="form-control" placeholder="Enter name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close-showmodel"
                    data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-filters">Save </button>
            </div>
        </div>
    </div>
</div>

@section('script')
@include('parameter.script')
@endsection
@endsection