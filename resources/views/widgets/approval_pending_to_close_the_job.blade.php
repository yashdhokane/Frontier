<div class="row">
    <div class="col-lg-12">
        <div class="card card-border shadow">
            <div class="card-body">
                <div class="d-md-flex align-items-center mt-2 justify-content-between">
                    <div>
                        <h5 class="card-title text-info uppercase mb-1">Approval Pending to Close the Job</h5>
                        <h5 class="ft12">Technician marked the job as closed</h5>
                    </div>
                   
                </div>
                <div class="table-responsive mt-1">
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
                            @foreach ($jobcompleteyes as $item)
                                <tr>
                                    <td>
                                        <a href="{{ url('tickets/' . $item->JobModel->id) }}" class="font-medium link">
                                            {{ $item->JobModel->job_title ?? null }}
                                        </a><br />
                                        {{ Str::limit($item->JobModel->description ?? '', 20) }}
                                    </td>
                                    <td>{{ $item->JobModel->user->name ?? null }}</td>
                                    <td>{{ $item->technician->name ?? null }}</td>
                                    <td>
                                        @php
                                            $time_interval = Session::get('time_interval', 0);
                                        @endphp
                                        @if ($item && $item->start_date_time)
                                            <div class="font-medium link ft12">
                                                {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                                            </div>
                                            <div class="ft12">
                                                {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                to
                                                {{ $modifyDateTime($item->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                            </div>
                                        @else
                                            <div></div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
