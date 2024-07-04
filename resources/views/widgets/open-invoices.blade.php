<div class="card card-hover">
    <div class="card-header bg-danger d-flex justify-content-between">
        <h4 class="mb-0 text-white">Open invoices</h4>
        @if ($layout->added_by == auth()->user()->id)
            <button class="btn btn-light mx-2 clearSection"
                data-element-id="{{ $cardPosition->element_id }}">X</button>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
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
                            <td><a
                                    href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                            </td>

                            <td>{{ $item->user->name ?? null }}</td>
                            <td>{{ $item->JobModel->technician->name ?? null }}</td>
                            <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}
                            </td>
                            <td>{{ $item->due_date ?? null }}</td>
                            <td>${{ $item->total ?? null }}</td>


                        </tr>
                        <!-- Modal for adding comment -->
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>