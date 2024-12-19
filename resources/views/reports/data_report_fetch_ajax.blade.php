
       
        <table class="table table-hover table-striped customize-table mb-0 v-middle" id="jobRevenue">
            <thead class="table-light">
                <tr>
                    <th class="border-bottom border-top">Jobs by Scheduled Day</th>
                    <th class="border-bottom border-top">Job Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($job->date)->format('M d, Y') }}</td>
                        <td>${{ number_format($job->daily_gross_total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No data available.</td>
                        <td colspan="2" class="text-center" style="display:none;">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
