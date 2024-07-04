<div class="card card-hover">
    <div class="card-header bg-warning d-flex justify-content-between">
        <h4 class="mb-0 text-white">Stats</h4>
        @if ($layout->added_by == auth()->user()->id)
            <button class="btn btn-light mx-2 clearSection"
                data-element-id="{{ $cardPosition->element_id }}">X</button>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6 mb-3">
                <div class="text-white bg-primary rounded">
                    <div class="card-body">
                        <span><i class="ri-group-line"
                                style="font-size: 36px;"></i></span>
                        <h3 class="card-title mt-1 mb-0 text-white">
                            {{ $customerCount ?? null }}</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">Customers</p>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="text-white bg-warning rounded">
                    <div class="card-body">
                        <span><i class="ri-contacts-line"
                                style="font-size: 36px;"></i></span>
                        <h3 class="card-title mt-1 mb-0 text-white">
                            {{ $technicianCount ?? null }}</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">Technicians
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="text-white bg-success rounded">
                    <div class="card-body">
                        <span><i class="ri-admin-line"
                                style="font-size: 36px;"></i></span>
                        <h3 class="card-title mt-1 mb-0 text-white">
                            {{ $dispatcherCount ?? null }}</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">Dispatchers
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="text-white bg-danger rounded">
                    <div class="card-body">
                        <span><i class="ri-admin-fill"
                                style="font-size: 36px;"></i></span>
                        <h3 class="card-title mt-1 mb-0 text-white">
                            {{ $adminCount ?? null }}</h3>
                        <p class="card-text text-white-50 fs-3 fw-normal">Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>