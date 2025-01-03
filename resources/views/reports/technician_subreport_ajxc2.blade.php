<table class="table table-hover table-striped customize-table mb-0 v-middle overflow-auto" id="technician1">
    <thead class="table-dark">
        <tr>
            <th class="border-bottom border-top">Technician Name</th>
            <th class="border-bottom border-top">Total On Job Hrs</th>
            <th class="border-bottom border-top">Total Travel Hrs</th>
            <th class="border-bottom border-top">Total Hrs Per Job</th>
        </tr>
        <tr class="table-success border-success">
            <th class="border-bottom border-top">Total</th>
            <th class="border-bottom border-top">{{ number_format($totalJobHours / 60, 2) }}</th>
            <th class="border-bottom border-top">{{ number_format($totalDrivingHours / 60, 2) }}</th>
            @if ($totalDrivingHours > 0)
                <td>{{ number_format(intval($totalJobHours / $totalDrivingHours), 2) }}</td>
            @else
                <td>N/A</td>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($technicians as $technician)
            <tr>
                <td>{{ $technician->technician_name }}</td>
                <td>{{ number_format($technician->total_job_hours / 60, 2) }}</td>
                <td>{{ number_format($technician->total_driving_hours / 60, 2) }}</td>
                @if ($technician->total_driving_hours > 0)
                    <td>{{ number_format(intval($technician->total_job_hours / $technician->total_driving_hours), 2) }}</td>
                @else
                    <td>N/A</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
