<table class="table table-hover table-striped customize-table mb-0 v-middle" id="monthjobRevenue">
    <thead class="table-light">
        <tr>
            <th class="border-bottom border-top">Jobs by Month</th>
            <th class="border-bottom border-top">Average Job Size</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($monthJobs as $monthJob)
            <tr>
                <td>
                    <?php
                    $startOfMonth = date('M 01, Y', mktime(0, 0, 0, $monthJob->month, 1));
                    $endOfMonth = date('M t, Y', mktime(0, 0, 0, $monthJob->month, 1));
                    ?>
                    {{ $startOfMonth }} - {{ $endOfMonth }}
                </td>
                <td>${{ number_format($monthJob->monthly_gross_total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
