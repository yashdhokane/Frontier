<div class="card card-hover">

    <div class="p-3">
        <div class="d-flex justify-content-between mb-1">
         <!--   <h4 class="">Paid invoices</h4> -->
            
        </div>
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
                    @foreach ($paymentopen as $index => $item)
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
