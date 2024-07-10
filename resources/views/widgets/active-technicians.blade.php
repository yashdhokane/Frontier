<div class="card card-hover">

    <div class="p-3">
        <div class="mb-1 d-flex justify-content-between">
            <h4 class="">Active Technicians</h4>
            @if ($layout->added_by == auth()->user()->id)
                <button class="btn btn-light mx-2 clearSection"
                    data-element-id="{{ $cardPosition->element_id }}">X</button>
            @endif
        </div>
        <div class="row">
            @foreach ($technicianuser as $item)
                <div class="col-lg-4">
                    <div class="card card-border shadow">
                        <div class="card-body">
                            <h5 class="card-title ft13 uppercase text-primary">
                                {{ $item->name ?? null }}</h5>
                            <h6 class="ft11 mb-2 d-flex align-items-center">
                                <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
                                @if (isset($item->area_name) && !empty($item->area_name))
                                    {{ $item->area_name ?? null }}
                                @endif
                            </h6>
                            <p class="card-text pt-2 ft12">
                                {{ $item->completed_jobs_count }}/{{ $item->total_jobs_count }}
                                Job Completed<br />
                                Completion Rate:
                                {{ number_format($item->completion_rate, 2) }}%
                            </p>
                            <a href="{{ route('technicians.show', ['id' => $item->id]) }}" class="card-link">View
                                Profile</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
