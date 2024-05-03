@extends('home')

@section('content')
    <style>
        #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
            padding-top: 35px;
        }

        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 130px;
        }
    </style>
    <div id="main-wrapper">
        <div class="page-wrapper">

            <!-- -------------------------------------------------------------- -->

            <!-- Bread crumb and right sidebar toggle -->

            <!-- -------------------------------------------------------------- -->

           

            <!-- -------------------------------------------------------------- -->

            <!-- End Bread crumb and right sidebar toggle -->

            <!-- -------------------------------------------------------------- -->

            <!-- -------------------------------------------------------------- -->

            <!-- Container fluid  -->

            <!-- -------------------------------------------------------------- -->

            <div class="container-fluid">

                <!-- -------------------------------------------------------------- -->

                <!-- Start Page Content -->

                <!-- -------------------------------------------------------------- -->

                <div class="row">

                    <div class="col-md-11">

                        <!-- ---------------------

                                                        start INVOICE

                                                    ---------------- -->



                        <div class="card card-body printableArea">



                            <div class="row">

                                <div class="col-md-12">

                                    <div class="card bg-success">

                                        <div class="card-body text-white">

                                            <div class="d-flex flex-row">

                                                <div class="round align-self-center bg-light-success text-success">

                                                    <i data-feather="credit-card" class="feather-sm"></i>

                                                </div>

                                                <div class="ms-3 align-self-center">

                                                    <h2 class="mb-0">{{$siteSettings->business_name ?? null }}</h2>

                                                    <span class="text-white">{{ $siteSettings->address ?? null }}</span>

                                                </div>

                                                <div class="ms-auto align-self-center">

                                                    <p><b>Invoice Number</b><br /><span class="pull-right"
                                                            style="font-size: 22px;">#{{ $payment->invoice_number }}</span>
                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                            <div class="row">

                                <div class="col-md-6">
                                     <div class="pull-left">
										<address>
											<div class="mb-2">To,</div>
											<h5>{{ $payment->user->name }}</h5>
											<p class="mt-2 mb-2"> {{ $payment->JobModel->address ?? null }}
 											{{ $payment->JobModel->city ?? null }} ,
											{{ $payment->JobModel->state ?? null }} ,
											{{ $payment->JobModel->zipcode ?? null }}
											</p>
										</address>
                                     </div>
                                 </div>

                                 <div class="col-md-6">
                                     <div class="pull-right text-end">
                                         <address>
											<h6 class="mb-2"><b>Invoice Date:</b> {{ $convertDateToTimezone($payment->issue_date) }}</h6>
											<h6 class="mb-2"><b>Due Date: </b> {{ $convertDateToTimezone($payment->due_date) }}</h6>
											<h6 class="mb-2"><b>Amount: </b> ${{ $payment->total }} </h6>
											<h6 class="mb-2"><b>Status: </b> {{ $payment->status }} </h6>
                                         </address>
                                     </div>
                                 </div>

 								
                                <div class="col-md-12">

                                    <div class="table-responsive m-t-40" style="clear: both">

                                        <table class="table table-hover">

                                            <thead>
                                                 <tr>
													<th>Item Name</th>
													<th class="text-end">Unit Price</th>
													<th class="text-end">Discount</th>
													<th class="text-end">Total</th>
                                                 </tr>
                                             </thead>

                                           <tbody>
    @foreach($jobproduct as $product)
        <tr>
           
            <td>
                <h6 class="font-weight-medium mb-0">
                  {{ $product->product->product_name ?? null }}
                    {{-- <small class="text-muted">LG Washing Machine Stand</small> --}}
                </h6>
            </td>
            <td class="text-end">${{ $product->base_price ?? null }}</td>
            <td class="text-end">${{ $product->discount ?? null }}</td>
            <td class="text-end">${{ $product->sub_total ?? null }}</td>
        </tr>
    @endforeach

    @foreach($jobservice as $service)
        <tr>
          
            <td>
                <h6 class="font-weight-medium mb-0">
                     {{ $service->service->service_name ?? null }}
                    <small class="text-muted">{{ $service->service->warranty_type ?? null }}</small>
                </h6>
            </td>
            <td class="text-end">${{ $service->base_price ?? null }}</td>
            <td class="text-end">${{ $service->discount ?? null }}</td>
            <td class="text-end">${{ $service->sub_total ?? null }}</td>
        </tr>
    @endforeach
</tbody>


                                        </table>

                                    </div>

                                </div>

                                <div class="col-md-8">
                                    &nbsp;
                                </div>

                                <div class="col-md-4">

                                    <div class="pull-right m-t-30 text-end">
									
										<div class="price_h5">Subtotal: <span>${{ $job->subtotal ?? null }}</span></div>
										<div class="price_h5">Discount: <span>${{ $job->discount ?? null }}</span></div>
										<div class="price_h5">Tax: <span>${{ $job->tax ?? null }}</span> </div>
										<!--<div class="price_h5">Total: <span>${{ $job->gross_total ?? null }}</span></div>-->
										

                                        <!--<p>Sub - Total amount: $297.00</p><p>Tax (10%) : $27</p><hr />-->

                                        <h3><b>Total :</b>
                                            ${{ $job->gross_total ?? null }}
                                        </h3>

                                    </div>
                                     <div class="clearfix"></div>

                                 </div>
								
								<div class="col-md-12 mt-4">
									 <div class="card-border1 px-3">
										<div class="row mt-3 mb-3">
											<div class="col-md-12">
												<div class="invoice_terms">												
												{!! $siteSettings->message_on_docs ?? null !!}
												</div>
											</div>
 										</div>
									</div>
 								</div>
								
								<div class="col-md-12 mt-4">
									 <div class="card-border1 px-3">
										<div class="text-end">

											<form action="{{ route('update.payment.status') }}" method="POST" id="paymentForm">
    @csrf
    <input type="hidden" name="payment_id" value="{{ $payment->id }}">
    <button class="btn btn-danger" type="submit" id="paymentButton" @if ($payment->status == 'paid') disabled @endif>
        @if ($payment->status == 'paid')
            Paid
        @else
            Mark Paid
        @endif
    </button>
</form>



											<button id="print" class="btn btn-default btn-outline" type="button">

												<span><i data-feather="printer" class="feather-sm"></i> Print</span>

											</button>

										</div>
									 </div>
								 </div>
								
								
								
								
								
								



                            </div>

                        </div>

                        <!-- ---------------------

                                                        end INVOICE

                                                    ---------------- -->

                    </div>
					
					<!--
					<div class="col-md-3">
						 <div class="card ">
							<div class="card-body card-border1 shadow">
								<h5 class="card-title">Job Details</h5>
								<div><a href="https://dispatchannel.com/portal/tickets/156" class="font-medium link"> {{ $payment->JobModel->job_title ?? null }}</a> </div>
								<div>{{ $payment->JobModel->description ?? null }}</div>
 								<div>Technician: [Technician_Name]</div>
								<div>Job Date: [JOB_DATE_TIME]</div>
								<div>Status: [OPEN_CLOSE]</div>
							</div>
						</div>
                     </div>
					 -->

                </div>

                <!-- -------------------------------------------------------------- -->

                <!-- End PAge Content -->

                <!-- -------------------------------------------------------------- -->

                <!-- -------------------------------------------------------------- -->

                <!-- Right sidebar -->

                <!-- -------------------------------------------------------------- -->

                <!-- .right-sidebar -->

                <!-- -------------------------------------------------------------- -->

                <!-- End Right sidebar -->

                <!-- -------------------------------------------------------------- -->

            </div>


        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- End Page wrapper  -->

        <!-- -------------------------------------------------------------- -->

    </div>
    <script>
    document.getElementById('paymentForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var button = document.getElementById('paymentButton');
        button.textContent = 'Processing...'; // Change button text to indicate processing
        button.disabled = true; // Disable the button to prevent multiple submissions
        this.submit(); // Submit the form
    });
</script>

@endsection
