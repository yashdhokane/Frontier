<div class="card card-hover">
    <div class="card-header bg-info d-flex justify-content-between">
        <h4 class="mb-0 text-white">Jobs by Service Types</h4>
        @if ($layout->added_by == auth()->user()->id)
            <button class="btn btn-light mx-2 clearSection"
                data-element-id="{{ $cardPosition->element_id }}">X</button>
        @endif
    </div>
    <div class="card-body">
        <div id="chart-pie-donut"></div>
    </div>
</div>