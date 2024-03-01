@extends('home')
@section('content')
    <style>
        .activegreen {
            border: 2px solid green !important;
        }

        .user_head_link {
            color: #2962ff !important;
            text-transform: uppercase;
            font-size: 13px;
        }

        .user_head_link:hover {
            color: #ee9d01 !important;
        }

        .dts2 {
            min-height: 60px;
        }

        .table> :not(caption)>*>* {
            padding: 0.3rem;
        }

        .dat table th {
            text-align: center;
        }

        .dts {
            background: #3699ff;
            padding: 5px;
            border-radius: 5px;
            color: #FFFFFF;
        }

        .dts p {
            margin-bottom: 5px;
            line-height: 17px;
        }

        .out {
            background: #fbeccd !important;
        }

        .out:hover {
            background: #fbeccd !important;
        }

        .out .dts {
            background: #fbeccd !important;
        }

        .table-hover>tbody>tr:hover>* {
            --bs-table-color-state: var(--bs-table-hover-color);
            --bs-table-bg-state: transparent;
        }

        img.calimg2 {
            width: 224px;
            margin: 0px 10px;
        }

        .error {
            color: #ca1414;
        }

        .table-responsive {
            overflow-x: auto !important;
            width: 100% !important;
        }

        .timeslot_td {
            position: relative;
            height: 80px;
            width: 100px;
            font-size: 12px;
        }

        .timeslot_th {
            position: relative;
            width: 100px;
        }

        .flexibleslot {
            cursor: pointer;
            position: absolute;
            z-index: 1;
            width: -webkit-fill-available;
        }

        .overflow_x {
            overflow-x: auto;
        }

        .overflow_y {
            overflow-y: auto;
        }

        .pending_jobs2 {
            border: 1px solid #2962ff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .img40 {
            width: 40px;
            height: 40px;
            line-height: 40px;
        }

        .customer_sr_box {
            padding: 10px;
            border: 1px solid #2962ff;
            border-radius: 4px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .confirm_job_box {
            margin-bottom: 20px;
        }

        .test {
            display: contents;
            font-size: 11px;
        }

        .reschedule_job {
            font-size: 12px;
        }

        .customer_sr_box:hover {
            background-color: #f3f3f3;
        }

        .pending_jobs2:hover {
            background-color: #f3f3f3;
        }

        .service_css {
            font-size: 11px;
        }

        .total_css {
            font-size: 14px;
        }

        .customers {
            height: 304px;
            overflow-y: auto;
        }

        .rescheduleJobs {
            height: 304px;
            overflow-y: auto;
        }
    </style>
    <div class="page-wrapper" style="display:inline;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="margin-top: -21px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Calls Schedule</h4>
                </div>
                <div class="col-7 align-self-right" style="text-align: right;padding-right: 40px;">
                    <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i>
                        Select Dates</a>
                    <a href="#." style="margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
                            class="fas fa-calendar-check"></i> Today</a>
                    <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i>
                        Yesterday</a>
                    <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i>
                        Tomorrow</a>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div>
                        <div class="row gx-0">
                            <div class="col-lg-12">
                                <div class="p-4 calender-sidebar app-calendar">
                                    <div class="row">
                                        <div class="col-md-2"><a href="schedule?date={{ $previousDate }}"><i
                                                    class="fas fa-arrow-left"></i></a></div>
                                        <div class="col-md-8">
                                            <h4 class="fc-toolbar-title text-center" id="fc-dom-1">
                                                {{ $formattedDate }}
                                            </h4>
                                        </div>
                                        <div class="col-md-2" style="text-align: right;"><a
                                                href="schedule?date={{ $tomorrowDate }}"><i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-responsive dat">
                                        <table id="demo-foo-addrow"
                                            class="table table-bordered m-t-30 table-hover contact-list text-nowrap"
                                            data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @if (isset($user_array) && !empty($user_array))
                                                        @foreach ($user_array as $value)
                                                            <th><a href="#" class="link user_head_link"
                                                                    style="color: {{ $user_data_array[$value]['color_code'] }} !important;">
                                                                    @if (isset($user_data_array[$value]['user_image']) && !empty($user_data_array[$value]['user_image']))
                                                                        <img src="{{ asset('public/images/technician/' . $user_data_array[$value]['user_image']) }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle" /><br>
                                                                    @else
                                                                        <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle " /><br>
                                                                    @endif
                                                                    {{ 'EMP' . $value }} <br>
                                                                    @if (isset($user_data_array[$value]) && !empty($user_data_array[$value]))
                                                                        {{ $user_data_array[$value]['name'] }}
                                                                    @endif
                                                                </a>
                                                            </th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 7; $i <= 18; $i++)
                                                    <tr>
                                                        <td class="timeslot_td">
                                                            @if ($i > 12)
                                                                @php
                                                                    $time = $i - 12;
                                                                    $date = $time . ' pm';
                                                                @endphp
                                                                {{ $date }}
                                                            @else
                                                                @php
                                                                    $time = $i;
                                                                    $date = $i . ' am';
                                                                @endphp
                                                                {{ $date }}
                                                            @endif
                                                        </td>
                                                        @if (isset($user_array) && !empty($user_array))
                                                            @foreach ($user_array as $value)
                                                                @php
                                                                    $assigned_data = [];
                                                                    if (
                                                                        isset($assignment_arr[$value][$i]) &&
                                                                        !empty($assignment_arr[$value][$i])
                                                                    ) {
                                                                        $assigned_data = $assignment_arr[$value][$i];
                                                                    }
                                                                @endphp
                                                                <td class="timeslot_td"
                                                                    data-slot_time="{{ $time }}"
                                                                    data-technician_id="{{ $value }}">
                                                                    @if (isset($assigned_data) && !empty($assigned_data))
                                                                        <div class="testclass">
                                                                            @foreach ($assigned_data as $value2)
                                                                                @if ($i == $value2->start_slot)
                                                                                    @php
                                                                                        $duration = $value2->duration;
                                                                                        $height_slot = $duration / 60;
                                                                                        $height_slot_px =
                                                                                            $height_slot * 80 - 10;
                                                                                    @endphp
                                                                                    <div class="dts mb-1 edit_schedule flexibleslot"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#edit"
                                                                                        style="cursor: pointer;height:{{ $height_slot_px . 'px' }};background:{{ $value2->color_code }};"
                                                                                        data-id="{{ $value2->main_id }}">
                                                                                        <h5
                                                                                            style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                                            {{ $value2->customername }}
                                                                                            &nbsp;&nbsp;
                                                                                        </h5>
                                                                                        <p style="font-size: 11px;"><i
                                                                                                class="fas fa-clock"></i>
                                                                                            {{ $date }} --
                                                                                            {{ $value2->job_code }}<br>{{ $value2->job_title }}
                                                                                        </p>
                                                                                        <p style="font-size: 12px;">
                                                                                            {{ $value2->city }},
                                                                                            {{ $value2->state }}
                                                                                        </p>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <div class="dts2 createSchedule"
                                                                            style="height: 100%;position: revert;"
                                                                            data-bs-toggle="modal"
                                                                            data-id="{{ $value }}"
                                                                            data-time="{{ $date }}"
                                                                            data-date="{{ $filterDate }}"
                                                                            data-bs-target="#create"></div>
                                                                    @endif
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">ADD NEW
                                JOB (ADD JOB & ASSIGN TECHNICIAN)
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body createScheduleData">
                            @include('schedule.create')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <!-- Modal -->
            <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">
                                UPDATE JOB
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body editScheduleData">
                            @include('schedule.edit')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        </div>
    </div>
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var ajaxRequestForCustomer;

        var ajaxRequestForService;

        var ajaxRequestForProduct;

        $.ucfirst = function(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        };

        function showAllInformation(params) {

            var customer_address = $('.customer_address').find(':selected');
            var cityValue = customer_address.data('city');
            var selectedText = customer_address.text();
            $('.show_customer_area').text(cityValue + ' Area');
            $('.show_customer_adderss').text(selectedText);

            $('.show_job_title').text($('.job_title').val());
            $('.show_job_code').text($('.job_code').val());
            $('.show_job_information').text($('.appliances').val() + ' / Model No.: ' + $('.model_number').val() +
                ' / Serial Number: ' + $('.serial_number').val());
            $('.show_job_description').text($('.job_description').val());
            $('.show_job_duration').text('Duration: ' + $('.duration option:selected').text());

            var services = $('.services').find(':selected');
            var services_code = services.data('code');
            var servicesText = services.text();
            var service_cost = $('.service_cost').val();
            var service_discount = $('.service_discount').val();
            var service_tax = $('.service_tax').val();
            var service_total = $('.service_total').val();

            $('.show_service_code_name').text(services_code + ' - ' + servicesText);
            $('.show_warranty').text(+$('.job_type option:selected').text());
            $('.show_service_cost').text('$' + service_cost);
            $('.show_service_discount').text('$' + service_discount);
            $('.show_service_tax').text('$' + service_tax);
            $('.show_service_total').text('$' + service_total);

            var products = $('.products').find(':selected');
            var products_code = products.data('code');
            var productsText = products.text();
            var product_cost = $('.product_cost').val();
            var product_discount = $('.product_discount').val();
            var product_tax = $('.product_tax').val();
            var product_total = $('.product_total').val();

            $('.show_product_code_name').text(products_code + ' - ' + productsText);
            $('.show_product_cost').text('$' + product_cost);
            $('.show_product_discount').text('$' + product_discount);
            $('.show_product_tax').text('$' + product_tax);
            $('.show_product_total').text('$' + product_total);

            var getDiscount = $('.discount').val().trim();
            var getTax = parseInt(service_tax) - parseInt(product_tax);
            var getTotal = $('.total').val().trim();

            $('.show_total_discount').text('$' + getDiscount);
            $('.show_total_tax').text('$' + getTax);
            $('.show_total').text('$' + getTotal);

        }

        $(document).on('click', '.createSchedule', function() {

            var id = $(this).attr('data-id');
            var time = $(this).attr('data-time');
            var date = $(this).attr('data-date');

            $.ajax({
                method: 'get',
                url: "{{ route('schedule.create') }}",
                data: {
                    id: id,
                    time: time,
                    date: date
                },
                beforeSend: function() {
                    $('.createScheduleData').html('Processing Data...');
                },
                success: function(data) {

                    $('.createScheduleData').empty();
                    $('.createScheduleData').html(data);

                    $('.tab-wizard').steps({
                        headerTag: 'h6',
                        bodyTag: 'section',
                        transitionEffect: 'fade',
                        titleTemplate: '<span class="step">#index#</span> #title#',
                        labels: {
                            finish: 'Submit Job',
                        },
                        onStepChanging: function(event, currentIndex, newIndex) {

                            if (newIndex < currentIndex) {
                                return true;
                            }

                            if (newIndex == 3) {
                                showAllInformation(newIndex);
                            }

                            return true;
                        },
                        onFinished: function(event, currentIndex) {
                            
                            var form = $('#createScheduleForm')[0];
                            var params = new FormData(form);

                            $.ajax({
                                url: "{{ route('schedule.create.post') }}",
                                data: params,
                                method: 'post',
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(data) {

                                    $('a[href="#finish"]:eq(0)').text(
                                        'Submit Job');

                                    if (date.start_date) {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            title: "Success!",
                                            text: "Job Has Been Reschedule",
                                            icon: "success"
                                        }).then((
                                            result) => {
                                            if (result.isConfirmed ||
                                                result.dismiss === Swal
                                                .DismissReason.backdrop
                                            ) {
                                                location
                                                    .reload();
                                            }
                                        });

                                        var elements = $(
                                            '[data-slot_time="' +
                                            data
                                            .start_date +
                                            '"][data-technician_id="' +
                                            data
                                            .technician_id +
                                            '"]');

                                        elements.empty();

                                        elements.append(data
                                            .html)

                                    } else if (data ==
                                        'false') {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "Something went wrong !",
                                        });

                                    } else {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            title: "Success!",
                                            text: "Job Has Been Created",
                                            icon: "success"
                                        });

                                        scheduleButton.empty();
                                        scheduleButton.html(
                                            data.html);
                                    }

                                }
                            });
                        },
                    });

                    $('.searchCustomer').keyup(function() {

                        var name = $(this).val().trim();

                        $('.customersSuggetions').show();

                        $('.pendingJobsSuggetions').show();

                        $('.customers').empty();

                        $('.rescheduleJobs').empty();

                        if (ajaxRequestForCustomer) {
                            ajaxRequestForCustomer.abort();
                        }

                        if (name.length != 0) {

                            ajaxRequestForCustomer = $.ajax({
                                url: "{{ route('autocomplete.customer') }}",
                                data: {
                                    name: name,
                                },
                                beforeSend: function() {

                                    $('.rescheduleJobs').text('Processing...');

                                    $('.customers').text('Processing...');
                                },
                                type: 'GET',
                                success: function(data) {

                                    $('.rescheduleJobs').empty();

                                    $('.customers').empty();

                                    if (data.customers) {
                                        $('.customers').append(data.customers);
                                    } else {
                                        $('.customers').html(
                                            '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                        );
                                    }
                                    if (data.pendingJobs) {
                                        $('.rescheduleJobs').append(data
                                            .pendingJobs);
                                    } else {
                                        $('.rescheduleJobs').html(
                                            '<div class="pending_jobs2"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                        );
                                    }
                                }
                            });

                        } else {

                            $('.customersSuggetions').hide();

                            $('.pendingJobsSuggetions').hide();

                        }

                    });

                }
            });
        });

        $(document).on('click', '.selectCustomer', function() {

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $('.customer_id').val(id);
            $('.searchCustomer').val(name);
            $('.searchCustomer').prop('disabled', true);
            $('.customersSuggetions').hide();
            $('.pendingJobsSuggetions').hide();
            $('.CustomerAdderss').show();

            var selectElement = $('.customer_address');
            selectElement.empty();

            var option = $('<option>', {
                value: '',
                text: '-- Select Address --'
            });

            selectElement.append(option);

            $.ajax({
                url: "{{ route('customer.details') }}",
                data: {
                    id: id,
                },
                type: 'GET',
                success: function(data) {
                    if (data) {
                        $('.customer_number_email').text(data.mobile + ' / ' + data.email);
                        $('.show_customer_name').text(data.name);
                    }
                    if (data.address && $.isArray(data.address)) {
                        $.each(data.address, function(index, element) {
                            var addressString = $.ucfirst(element.address_type) + ':  ' +
                                element.address_line1 + ', ' + element.city +
                                ', ' + element.state_name + ', ' + element
                                .zipcode;
                            var option = $('<option>', {
                                value: element.address_type,
                                text: addressString
                            });

                            option.attr('data-city', element.city);

                            selectElement.append(option);
                        });
                    }
                }
            });
        });

        $(document).on('change', '.services', function(event) {

            event.stopPropagation();

            var id = $(this).val().trim();

            if ($('.pre_service_id').val() != '') {

                var service_cost = $('.service_cost').val();

                var service_discount = $('.service_discount').val();

                var service_tax = $('.service_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                $('.subtotal').val(subTotal);
                $('.subtotaltext').text('$' + subTotal);

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(service_discount);
                $('.discount').val(discount);
                $('.discounttext').text('$' + discount);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(service_discount) - parseInt(
                    service_tax);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.service_cost').val(0);

                $('.service_discount').val(0);

                $('.service_tax_text').text('$0');

                $('.service_total_text').text('$0');

                $('.pre_service_id').val('');
            }

            if (id.length != 0) {

                if (ajaxRequestForService) {
                    ajaxRequestForService.abort();
                }

                ajaxRequestForService = $.ajax({
                    url: "{{ route('services.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {

                        if (data) {

                            $('.pre_service_id').val(id);

                            $('.service_cost').val(data.service_cost);
                            $('.pre_service_cost').val(data.service_cost);

                            $('.service_discount').val(data.service_discount);
                            $('.pre_service_discount').val(data.service_discount);

                            $('.service_tax_text').text('$' + data.service_tax);
                            $('.service_tax').val(data.service_tax);

                            $('.service_total_text').text('$' + data.service_cost);
                            $('.service_total').val(data.service_cost);

                            var getSubTotalVal = $('.subtotal').val().trim();
                            var subTotal = parseInt(getSubTotalVal) + parseInt(data.service_cost);
                            $('.subtotal').val(subTotal);
                            $('.subtotaltext').text('$' + subTotal);

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.service_discount);
                            $('.discount').val(discount);
                            $('.discounttext').text('$' + discount);

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.service_cost) - parseInt(data
                                .service_discount) + parseInt(data.service_tax);
                            $('.total').val(total);
                            $('.totaltext').text('$' + total);

                        }

                    }
                });
            }

        });

        $(document).on('change', '.service_cost', function() {

            var pre_service_cost = parseInt($('.pre_service_cost').val());

            var service_cost = $(this).val();

            if ((/-/.test(service_cost) || /\./.test(service_cost) || isNaN(service_cost))) {
                $(this).val(pre_service_cost);
                return true;
            }

            $('.service_cost').val(service_cost);
            $('.pre_service_cost').val(service_cost);

            $('.service_total_text').text('$' + service_cost);
            $('.service_total').val(service_cost);

            var getSubTotalVal = $('.subtotal').val().trim();
            var subTotal = parseInt(getSubTotalVal) - parseInt(pre_service_cost);
            var currentSubTotal = parseInt(subTotal) + parseInt(service_cost);
            $('.subtotal').val(currentSubTotal);
            $('.subtotaltext').text('$' + currentSubTotal);

            var getTotal = $('.total').val().trim();
            var total = parseInt(getTotal) - parseInt(pre_service_cost);
            var currentTotal = parseInt(total) + parseInt(service_cost);
            $('.total').val(currentTotal);
            $('.totaltext').text('$' + currentTotal);

        });

        $(document).on('change', '.service_discount', function() {

            var service_discount = $(this).val();

            var pre_service_discount = parseInt($('.pre_service_discount').val());

            if ((/-/.test(service_discount) || /\./.test(service_discount) || isNaN(service_discount))) {
                $(this).val(pre_service_discount);
                return true;
            }

            var getDiscount = parseInt($('.discount').val());
            var totalDiscount = parseInt(getDiscount) - parseInt(pre_service_discount);
            var finnalDiscount = parseInt(totalDiscount) + parseInt(service_discount);
            $('.discount').val(finnalDiscount);
            $('.discounttext').text('$' + finnalDiscount);

            var getPreTotal = parseInt($('.total').val());
            var getTotal = parseInt(getPreTotal) + parseInt(pre_service_discount);
            var total = parseInt(getTotal) - parseInt(service_discount);
            $('.total').val(total);
            $('.totaltext').text('$' + total);

            $('.pre_service_discount').val(service_discount);

        });

        $(document).on('change', '.products', function(event) {

            event.stopPropagation();

            var id = $(this).val().trim();

            if ($('.pre_product_id').val() != '') {

                var product_cost = $('.product_cost').val();

                var product_discount = $('.product_discount').val();

                var product_tax = $('.product_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                $('.subtotal').val(subTotal);
                $('.subtotaltext').text('$' + subTotal);

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(product_discount);
                $('.discount').val(discount);
                $('.discounttext').text('$' + discount);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(product_discount) - parseInt(
                    product_tax);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.product_cost').val(0);

                $('.product_discount').val(0);

                $('.product_tax_text').text('$0');

                $('.product_total_text').text('$0');

                $('.pre_product_id').val('');
            }

            if (id.length != 0) {

                if (ajaxRequestForProduct) {
                    ajaxRequestForProduct.abort();
                }

                ajaxRequestForProduct = $.ajax({
                    url: "{{ route('product.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {

                        if (data) {

                            $('.pre_product_id').val(id);

                            $('.product_cost').val(data.base_price);
                            $('.pre_product_cost').val(data.base_price);

                            $('.product_discount').val(data.discount);
                            $('.pre_product_discount').val(data.discount);

                            $('.product_tax_text').text('$' + data.tax);
                            $('.product_tax').val(data.tax);

                            $('.product_total_text').text('$' + data.base_price);
                            $('.product_total').val(data.base_price);

                            var getSubTotalVal = $('.subtotal').val().trim();
                            var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                .base_price);
                            $('.subtotal').val(subTotal);
                            $('.subtotaltext').text('$' + subTotal);

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.discount);
                            $('.discount').val(discount);
                            $('.discounttext').text('$' + discount);

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.base_price) -
                                parseInt(data.discount) + parseInt(data.tax);
                            $('.total').val(total);
                            $('.totaltext').text('$' + total);

                        }

                    }
                });
            }
        });

        $(document).on('change', '.product_cost', function() {

            var pre_product_cost = parseInt($('.pre_product_cost').val());

            var product_cost = $(this).val();

            if ((/-/.test(product_cost) || /\./.test(product_cost) || isNaN(product_cost))) {
                $(this).val(pre_product_cost);
                return true;
            }

            $('.product_cost').val(product_cost);
            $('.pre_product_cost').val(product_cost);

            $('.product_total_text').text('$' + product_cost);
            $('.product_total').val(product_cost);

            var getSubTotalVal = $('.subtotal').val().trim();
            var subTotal = parseInt(getSubTotalVal) - parseInt(pre_product_cost);
            var currentSubTotal = parseInt(subTotal) + parseInt(product_cost);
            $('.subtotal').val(currentSubTotal);
            $('.subtotaltext').text('$' + currentSubTotal);

            var getTotal = $('.total').val().trim();
            var total = parseInt(getTotal) - parseInt(pre_product_cost);
            var currentTotal = parseInt(total) + parseInt(product_cost);
            $('.total').val(currentTotal);
            $('.totaltext').text('$' + currentTotal);


        });

        $(document).on('change', '.product_discount', function() {

            var product_discount = $(this).val();

            var pre_product_discount = parseInt($('.pre_product_discount').val());

            if ((/-/.test(product_discount) || /\./.test(product_discount) || isNaN(product_discount))) {
                $(this).val(pre_product_discount);
                return true;
            }

            var getDiscount = parseInt($('.discount').val());
            var totalDiscount = parseInt(getDiscount) - parseInt(pre_product_discount);
            var finnalDiscount = parseInt(totalDiscount) + parseInt(product_discount);
            $('.discount').val(finnalDiscount);
            $('.discounttext').text('$' + finnalDiscount);

            var getPreTotal = parseInt($('.total').val());
            var getTotal = parseInt(getPreTotal) + parseInt(pre_product_discount);
            var total = parseInt(getTotal) - parseInt(product_discount);
            $('.total').val(total);
            $('.totaltext').text('$' + total);

            $('.pre_product_discount').val(product_discount);

        });
    </script>
@endsection
@endsection
