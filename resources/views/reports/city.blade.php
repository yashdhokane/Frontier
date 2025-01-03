 <table class="table table-hover table-striped customize-table mb-0 v-middle" id="cityReport">
     <thead class="table-light">
         <tr>
             <th class="border-bottom border-top">City</th>
             <th class="border-bottom border-top">Job Revenue</th>
             <th class="border-bottom border-top">Job Count</th>
             <th class="border-bottom border-top">Avg. Job Size</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($cityDetails as $cityDetail)
             <tr data-city="{{ $cityDetail->city }}">
                 <td>{{ $cityDetail->city }}</td>
                 <td>${{ number_format($cityDetail->total_gross_total, 2) }}</td>
                 <td>{{ $cityDetail->job_count }}</td>
                 @if ($cityDetail->job_count > 0)
                     <td>${{ number_format($cityDetail->total_gross_total / $cityDetail->job_count, 2) }}
                     </td>
                 @else
                     <td>$0</td>
                 @endif
             </tr>
         @endforeach
     </tbody>
 </table>
