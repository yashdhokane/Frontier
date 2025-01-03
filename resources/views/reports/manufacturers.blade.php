  <table class="table table-hover table-striped customize-table mb-0 v-middle " id="manufacturers">
      <thead class="table-light">
          <tr>
              <th class="border-bottom border-top">Manufacturers</th>
              <th class="border-bottom border-top">Job Revenue</th>
              <th class="border-bottom border-top">Job Count</th>
              <th class="border-bottom border-top">Avg. Job Size</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($CountsManufacturer as $manufacturer)
              <tr data-manufacturer="{{ $manufacturer->manufacturer_name }}">
                  <td>{{ $manufacturer->manufacturer_name }}</td>
                  <td>${{ number_format($manufacturer->total_revenue, 2) }}</td>
                  <td>{{ $manufacturer->total_jobs }}</td>
                  <td>${{ number_format($manufacturer->total_revenue / $manufacturer->total_jobs, 2) }}
                  </td>
              </tr>
          @endforeach
      </tbody>
  </table>
