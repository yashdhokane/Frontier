   <table class="table table-hover table-striped customize-table mb-0 v-middle" id="priority">
       <thead class="table-light">
           <tr>
               <th class="border-bottom border-top">Jobs by Type</th>
               <th class="border-bottom border-top">Job Revenue</th>
               <th class="border-bottom border-top">Job Count</th>
               <th class="border-bottom border-top">Avg. Job Size</th>
           </tr>
       </thead>
       <tbody>
           @foreach ($priorityCounts as $priority)
               <tr data-priority="{{ $priority->priority }}">
                   <td>{{ $priority->priority }}</td>
                   <td>${{ number_format($priority->total_gross_total, 2) }}</td>
                   <td>{{ $priority->job_count }}</td>
                   <td>${{ number_format($priority->total_gross_total / $priority->job_count, 2) }}
                   </td>
               </tr>
           @endforeach
       </tbody>
   </table>
