   <table class="table table-hover table-striped customize-table mb-0 v-middle" id="appliances">
       <thead class="table-light">
           <tr>
               <th class="border-bottom border-top">Appliances</th>
               <th class="border-bottom border-top">Job Revenue</th>
               <th class="border-bottom border-top">Job Count</th>
               <th class="border-bottom border-top">Avg. Job Size</th>
           </tr>
       </thead>
       <tbody>
           @foreach ($CountsAppliance as $appliance)
               <tr data-appliance="{{ $appliance->appliance_name }}">
                   <td>{{ $appliance->appliance_name }}</td>
                   <td>${{ number_format($appliance->total_revenue, 2) }}</td>
                   <td>{{ $appliance->total_jobs }}</td>
                   <td>${{ number_format($appliance->total_revenue / $appliance->total_jobs, 2) }}
                   </td>
               </tr>
           @endforeach
       </tbody>
   </table>
 