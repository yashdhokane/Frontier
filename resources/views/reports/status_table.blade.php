<table class="table table-hover table-striped customize-table mb-0 v-middle" id="status">
    <thead class="table-light">
        <tr>
            <th class="border-bottom border-top">Status</th>
            <th class="border-bottom border-top">Job Revenue</th>
            <th class="border-bottom border-top">Job Count</th>
            <th class="border-bottom border-top">Avg. Job Size</th>
            <th class="border-bottom border-top">Date</th>

        </tr>

    </thead>
    <tbody>
        @forelse ($jobstatus as $item)
            <tr data-status="{{ $item->status }}">
                <td>{{ $item->status }}</td>
                <td>${{ number_format($item->total_gross_total, 2) }}</td>
                <td>{{ $item->job_count }}</td>
                <td>
                    @if ($item->job_count > 0)
                        ${{ number_format($item->total_gross_total / $item->job_count, 2) }}
                    @else
                        $0.00
                    @endif
                </td>
                <td>
                    @if ($item->revenue_date)
                        {{ \Carbon\Carbon::parse($item->revenue_date)->format('M d, Y') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5" style="" class="text-center">No data available for the selected filters.
                </td>
                <td colspan="4" style="display: none;" class="text-center">No data available for the selected
                    filters.
                </td>

                <td colspan="4" style="display: none;" class="text-center">No data available for the selected
                    filters.
                </td>

                <td colspan="4" style="display: none;" class="text-center">No data available for the selected
                    filters.
                </td>

                <td colspan="4" class="text-center" style="display: none;">No data available for the selected
                    filters.</td>

            </tr>
        @endforelse
    </tbody>
</table>
