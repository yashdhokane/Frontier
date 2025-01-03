 <table
                                                    class="table table-hover table-striped datatable_new2 mb-0 v-middle w-100"
                                                    id="file_export11">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Employee</th>
                                                            <th class="border-bottom border-top">Job Created</th>
                                                            <th class="border-bottom border-top">Job updated</th>
                                                            <th class="border-bottom border-top">Job Closed</th>
                                                            <th class="border-bottom border-top">Job Revenue</th>
                                                            <th class="border-bottom border-top">Average size Job</th>
                                                            <th class="border-bottom border-top">Activity</th>
                                                            <th class="border-bottom border-top">Messages</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $job }}
                                                            </th>
                                                            <th class="border-bottom border-top">${{ number_format($alltotalGross, 2) }}
                                                            </th>
                                                            @if ($job > 0)
                                                                <th class="border-bottom border-top">
                                                                    ${{ number_format(intval($alltotalGross / $job), 2) }}</th>
                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                            <th class="border-bottom border-top">{{ $totalActivity }}
                                                            </th>
                                                            <th class="border-bottom border-top">{{ $totalChats }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                            <tr>
                                                                <td>{{ $employee->name ?? null }}</td>
                                                                <td>{{ $jobCountsByEmployee[$employee->id] }}</td>
                                                                <td>{{ $jobCountsUpdatedBy[$employee->id] }}</td>
                                                                <td>{{ $jobCountsClosedBy[$employee->id] }}</td>
                                                                <td>${{ number_format($grossTotalByEmployee[$employee->id], 2) }}</td>
                                                                @if ($jobCountsByEmployee[$employee->id] > 0)
                                                                   <td>${{ number_format(intval($grossTotalByEmployee[$employee->id] / $jobCountsByEmployee[$employee->id]), 2) }}</td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                                <td>{{ $activity[$employee->id] }}</td>
                                                                <td>{{ $chats[$employee->id] }}</td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
