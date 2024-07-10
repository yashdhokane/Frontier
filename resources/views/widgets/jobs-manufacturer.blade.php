<div class="card card-hover">

    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
            <h4 class="">Jobs by manufacturer</h4>
            @if ($layout->added_by == auth()->user()->id)
                <button class="btn btn-light mx-2 clearSection"
                    data-element-id="{{ $cardPosition->element_id }}">X</button>
            @endif
        </div>
        <div id="chart-pie-simple"></div>
    </div>
</div>
