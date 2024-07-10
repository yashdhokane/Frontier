<div class="card card-hover">
    
    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
        <h4 class="">Upcoming Job </h4>
        @if ($layout->added_by == auth()->user()->id)
            <button class="btn btn-light mx-2 clearSection"
                data-element-id="{{ $cardPosition->element_id }}">X</button>
        @endif
    </div>
        <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
            <table id="" class="table table-bordered text-nowrap">
                <thead class="uppercase">
                    <tr>
                        <th>Jobs Details</th>
                        <th>Customer</th>
                        <th>Technician</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($job as $item)
                        <tr>
                            <td>
                                <a href="{{ url('tickets/' . $item->JobModel->id) }}"
                                    class="font-medium link">{{ $item->JobModel->job_title ?? null }}</a><br />
                                {{ $item->JobModel->description ?? null }}
                            </td>
                            <td>{{ $item->JobModel->user->name ?? null }}</td>
                            <td>{{ $item->technician->name ?? null }}</td>
                            <td>
                                @if ($item && $item->start_date_time)
                                    <div class="font-medium link ft12">
                                        {{ $convertDateToTimezone($item->start_date_time ?? null) }}
                                    </div>
                                @else
                                    <div></div>
                                @endif
                                <div class="ft12">
                                    {{ $convertTimeToTimezone($item->start_date_time ?? null, 'H:i:a') }}
                                    to
                                    {{ $convertTimeToTimezone($item->end_date_time ?? null, 'H:i:a') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>