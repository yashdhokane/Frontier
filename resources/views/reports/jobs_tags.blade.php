<table class="table table-hover table-striped customize-table mb-0 v-middle" id="job_tags">
    <thead class="table-light">
        <tr>
            <th class="border-bottom border-top">Jobs by Tags</th>
            <th class="border-bottom border-top">Job Revenue</th>
            <th class="border-bottom border-top">Job Count</th>
            <th class="border-bottom border-top">Avg. Job Size</th>
            <th class="border-bottom border-top">Date</th> <!-- Add Date column -->
        </tr>
    </thead>
    <tbody>
        @foreach ($tagCounts as $tag)
            <tr data-tag="{{ $tag->tag_name }}">
                <td>{{ $tag->tag_name }}</td>
                <td>${{ number_format($tag->total_gross_total, 2) }}</td>
                <td>{{ $tag->job_count }}</td>
                <td>${{ number_format($tag->total_gross_total / $tag->job_count, 2) }}</td>
                <td>
                    @if ($tag->created_at)
                        {{ \Carbon\Carbon::parse($tag->created_at)->format('M d, Y') }}
                    @else
                        N/A
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
