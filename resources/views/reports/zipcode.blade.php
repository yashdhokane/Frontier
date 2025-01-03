 <table class="table table-hover table-striped customize-table mb-0 v-middle" id="zipcodeReport">
     <thead class="table-light">
         <tr>
             <th class="border-bottom border-top">Zip Code</th>
             <th class="border-bottom border-top">Job Revenue</th>
             <th class="border-bottom border-top">Job Count</th>
             <th class="border-bottom border-top">Avg. Job Size</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($zipCodeDetails as $zipCodeDetail)
             <tr data-zipcode="{{ $zipCodeDetail->zipcode }}">
                 <td>{{ $zipCodeDetail->zipcode }}</td>
                 <td>${{ number_format($zipCodeDetail->total_gross_total, 2) }}</td>
                 <td>{{ $zipCodeDetail->job_count }}</td>
                 @if ($zipCodeDetail->job_count > 0)
                     <td>${{ number_format($zipCodeDetail->total_gross_total / $zipCodeDetail->job_count, 2) }}
                     </td>
                 @else
                     <td>$0</td>
                 @endif
             </tr>
         @endforeach
     </tbody>
 </table>
