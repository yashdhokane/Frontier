  <table class="table table-hover table-striped customize-table mb-0 v-middle" id="stateReport">
      <thead class="table-light">
          <tr>
              <th class="border-bottom border-top">State</th>
              <th class="border-bottom border-top">Job Revenue</th>
              <th class="border-bottom border-top">Job Count</th>
              <th class="border-bottom border-top">Avg. Job Size</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($stateDetails as $stateDetail)
              <tr data-state="{{ $stateDetail->state }}">
                  <td>{{ $stateDetail->state }}</td>
                  <td>${{ number_format($stateDetail->total_gross_total, 2) }}</td>
                  <td>{{ $stateDetail->job_count }}</td>
                  @if ($stateDetail->job_count > 0)
                      <td>${{ number_format($stateDetail->total_gross_total / $stateDetail->job_count, 2) }}
                      </td>
                  @else
                      <td>$0</td>
                  @endif
              </tr>
          @endforeach
      </tbody>
  </table>
