<div class="card card-hover">

    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
            <h4 class="">Jobs by Service Types</h4>
            @if ($layout->added_by == auth()->user()->id)
                <button class="btn btn-light mx-2 clearSection"
                    data-element-id="{{ $cardPosition->element_id }}">X</button>
            @endif
        </div>
        <div id="chart-pie-donut"></div>
    </div>
</div>

<script src="{{ asset('public/admin/dist/js/pages/apex-chart/apex.pie.init.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
