{{-- <div class="col-md-4 col-sm-12  align-items-stretch  box" tabindex="0" data-index="0">
    <div class="card w-100">
        <div class="card-body card-border shadow">
            <div class="form-group">
                <div class=" justify-content-between align-items-center mb-3">
                    <h4 class="text-warning">Open invoices</h4>
                    <button type="button" class="btn btn-link text-warning expand-toggle" data-index="0">Expand</button>
                </div> --}}
<!-- Add iframe or other content here -->

<div class="table-responsive mt-1">
    <table id="" class="table table-bordered text-nowrap">
        <thead>
            <!-- start row -->
            <tr>
                <th>ID</th>

                <th>Customer</th>
                <th>Technician</th>
                <th>Inv. Date</th>
                <th>Due Date</th>
                <th>Amount</th>

            </tr>
            <!-- end row -->
        </thead>
        <tbody>
            <!-- start row -->
            @foreach ($paymentclose as $index => $item)
                <tr>
                    <td><a href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                    </td>

                    <td>{{ $item->user->name ?? null }}</td>
                    <td>{{ $item->JobModel->technician->name ?? null }}</td>
                    <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                    <td>{{ $convertDateToTimezone($item->due_date ?? null) }}</td>
                    <td>${{ $item->total ?? null }}</td>


                </tr>
                <!-- Modal for adding comment -->
            @endforeach

        </tbody>
    </table>
</div>
{{-- </div>
        </div>
    </div>
</div> --}}
