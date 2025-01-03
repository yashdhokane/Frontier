   <table class="table table-hover table-striped customize-table mb-0 v-middle" id="job_lead_source">
       <thead class="table-light">
           <tr>
               <th class="border-bottom border-top">Jobs by Lead Source</th>
               <th class="border-bottom border-top">Job Revenue</th>
               <th class="border-bottom border-top">Job Count</th>
               <th class="border-bottom border-top">Avg. Job Size</th>
           </tr>
       </thead>
       <tbody>
           @foreach ($leadSourceCounts as $leadSource)
               <tr data-lead-source="{{ $leadSource->lead_source }}">
                   <td>{{ $leadSource->lead_source }}</td>
                   <td>${{ number_format($leadSource->total_gross_total, 2) }}</td>
                   <td>{{ $leadSource->job_count }}</td>
                   <td>${{ number_format($leadSource->total_gross_total / $leadSource->job_count, 2) }}
                   </td>
               </tr>
           @endforeach
       </tbody>
   </table>
