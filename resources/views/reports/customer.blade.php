  <table class="table table-hover table-striped customize-table mb-0 v-middle" id="customerReport">
      <thead class="table-light">
          <tr>
              <th class="border-bottom border-top">Customer Name</th>
              <th class="border-bottom border-top">Job Revenue</th>
              <th class="border-bottom border-top">Job Count</th>
              <th class="border-bottom border-top">Avg. Job Size</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($customerDetails as $customer)
              <tr data-customer-name="{{ $customer->name }}">
                  <td>{{ $customer->name }}</td>
                  <td>${{ number_format($customer->total_revenue, 2) }}</td>
                  <td>{{ $customer->total_jobs }}</td>
                  <td>${{ number_format($customer->total_revenue / $customer->total_jobs, 2) }}
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
