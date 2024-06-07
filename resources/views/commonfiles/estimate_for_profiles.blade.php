@if ($estimates->isEmpty())
<h5 class="card-title uppercase">Estimates</h5>
<div class="alert alert-info mt-4 col-md-12" role="alert">
    Estimates details not available for {{ $commonUser->name ?? null }}. <strong><a href="{{ route('schedule') }}">Add
            New</a></strong>
</div>
@else
<div class="table-responsive table-custom2 mt-2">
    <table id="zero_config" class="table table-hover table-striped text-nowrap">
        <thead>
            <tr>
                <th>#</th>
                <th>Job</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Technician</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estimates as $estimate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $estimate->ticket ?? null }}</td>
                <td>{{ isset($estimate->date) ?
                    \Carbon\Carbon::parse($estimate->date)->format('m-d-Y') : null }}
                </td>
                <td>{{ $estimate->amount ?? null }}</td>
                <td>{{ $estimate->technician ?? null }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif