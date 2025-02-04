@extends('home')

@section('content')
    <div class="col-md-12">
        <div class="card">
           
            <div class="card-body">

                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#navpill-0" role="tab"
                                aria-selected="true">
                                <span>Jobs Completed</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#navpill-1" role="tab"
                                aria-selected="true">
                                <span>Parts & Orders</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#navpill-3" role="tab" aria-selected="false"
                                tabindex="-1">
                                <span>Vehicles</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#navpill-2" role="tab" aria-selected="false"
                                tabindex="-1">
                                <span>Payments</span>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#navpill-4" role="tab" aria-selected="false"
                                tabindex="-1">
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content border mt-2">
                        <div class="tab-pane p-3 active show" id="navpill-0" role="tabpanel">
                            @include('parameterOld.jobs')
                        </div>
                        <div class="tab-pane p-3" id="navpill-1" role="tabpanel">
                            @include('parameterOld.parts_orders')
                        </div>
                        <div class="tab-pane p-3" id="navpill-2" role="tabpanel">
                             @include('parameterOld.payments')
                        </div>
                        <div class="tab-pane p-3" id="navpill-3" role="tabpanel">
                            @include('parameterOld.vehicles')
                        </div>
                        <div class="tab-pane p-3" id="navpill-4" role="tabpanel">
                            @include('parameterOld.customers')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
     @include('parameterOld.script')
    @endsection
@endsection
