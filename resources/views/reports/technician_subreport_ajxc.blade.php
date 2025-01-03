  <table
                                                    class="table table-hover table-striped customize-table mb-0 v-middle overflow-auto"
                                                    id="technician">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Technician Name</th>
                                                            <th class="border-bottom border-top">Job Revenue</th>
                                                            <th class="border-bottom border-top">Job Count</th>
                                                            <th class="border-bottom border-top">Avg. Job Size</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">${{ number_format($totalJobRevenue, 2) }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $totalJobCount }}</th>
                                                            @if ($totalJobCount > 0)
 <td>${{ number_format(intval($totalJobRevenue / $totalJobCount), 2) }}</td>                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($technicians as $technician)
                                                            <tr>
                                                                <td>{{ $technician->technician_name }}</td>
                <td>${{ number_format($technician->total_gross, 2) }}</td>
                                                                <td>{{ $technician->job_count }}</td>
                                                                @if ($technician->job_count > 0)
                                                                                       <td>${{ number_format(intval($technician->total_gross / $technician->job_count), 2) }}</td>

                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>