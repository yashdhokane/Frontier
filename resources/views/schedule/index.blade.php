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
                                                                    if (isset($assignment_arr[$value][$i]) && !empty($assignment_arr[$value][$i])) {
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
                                                                                        $height_slot_px = $height_slot * 80 - 10;
                                                                                    @endphp
                                                                                    <div class="dts mb-1 edit_schedule flexibleslot"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#edit"
                                                                                        style="cursor: pointer;height:{{ $height_slot_px.'px' }};background:{{ $value2->color_code }};"
                                                                                        data-id="{{ $value2->main_id }}">
                                                                                        <h5
                                                                                            style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                                            {{ $value2->customername }} &nbsp;&nbsp;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            var ajaxRequestForService;

            var ajaxRequestForProduct;

            var ajaxRequestForCustomer;

            var ajaxRequestForSubmit;

            var scheduleButton;

            var ajaxRequestForupdate;

            var editscheduleButton;

            var url = "{{ route('schedule.create.post') }}";

            function validateForm(currentIndex, edit) {

                var error = 0;

                if (currentIndex == 0) {

                    var checkedValue = $('input[type="radio"][name="radio-stacked"]:checked').val();

                    console.log(checkedValue);

                    if (checkedValue == 'old') {

                        if ($('.serchOldJob').val().trim() == '') {
                            $('#serchOldJobError').text('Please select the job.');
                            error++;
                        } else {
                            $('#serchOldJobError').text('');
                        }

                    } else {

                        if (edit == 1) {

                            if ($('.technician_name').val().trim() == '') {
                                $('#technician_name').text('Technician is required.');
                                error++;
                            } else if ($('.technician_name').val().trim() != '' && $('.technician_id').val()
                                .trim() ==
                                '') {
                                $('#technician_name').text('Technician is not valid.');
                                error++;
                            } else {
                                $('#technician_name').text('');
                            }

                            if ($('.start_date_time').val().trim() == '') {
                                $('#start_date_time').text('Start Date is required.');
                                error++;
                            } else {
                                $('#start_date_time').text('');
                            }

                            if ($('.end_date_time').val().trim() == '') {
                                $('#end_date_time').text('End Date is required.');
                                error++;
                            } else {
                                $('#end_date_time').text('');
                            }

                        } else {

                            if ($('.customerAuto').val().trim() == '') {
                                $('#customer_name').text('Customer is required.');
                                error++;
                            } else if ($('.customerAuto').val().trim() != '' && $('.customer_id').val().trim() ==
                                '') {
                                $('#customer_name').text('Customer is not valid.');
                                error++;
                            } else {
                                $('#customer_name').text('');
                            }

                            if ($('.technician_name').val().trim() == '') {
                                $('#technician_name').text('Techinician is required.');
                                error++;
                            } else {
                                $('#technician_name').text('');
                            }

                            if ($('.job_title').val().trim() == '') {
                                $('#job_title').text('Title is required.');
                                error++;
                            } else {
                                $('#job_title').text('');
                            }

                            if ($('.datetime').val().trim() == '') {
                                $('#datetime').text('Datetime is required.');
                                error++;
                            } else {
                                $('#datetime').text('');
                            }

                            if ($('.job_code').val().trim() == '') {
                                $('#job_code').text('Job Code is required.');
                                error++;
                            } else {
                                $('#job_code').text('');
                            }

                            if ($('.job_type').val().trim() == '') {
                                $('#job_type').text('Type is required.');
                                error++;
                            } else {
                                $('#job_type').text('');
                            }

                            if ($('.description').val().trim() == '') {
                                $('#description').text('Description is required.');
                                error++;
                            } else {
                                $('#description').text('');
                            }

                        }
                    }



                } else if (currentIndex == 1) {

                    $('#select_error').text('');
                    $('#service_error').val('');
                    $('#product_error').val('');

                    if ($('.searchServices').val().trim() == '' && $('.searchProduct').val().trim() == '') {
                        $('#select_error').text('Please select service or product.');
                        error++;
                    } else if ($('.searchServices').val().trim() != '' && $('.service_id').val().trim() == '') {
                        $('#service_error').text('Selected service is not valid.');
                        error++;
                    } else if ($('.service_id').val().trim() != '' && $('.product_id').val().trim() == '' && ($(
                            '.service_quantity').val().trim() == 0 || $('.service_quantity').val().trim() == '')) {
                        $('#service_error').text('Selected service quantity must not be 0.');
                        error++;
                    } else if ($('.searchProduct').val().trim() != '' && $('.product_id').val().trim() == '') {
                        $('#product_error').text('Selected product is not valid.');
                        error++;
                    } else if ($('.service_id').val().trim() == '' && $('.product_id').val().trim() != '' && ($(
                            '.product_quantity').val().trim() == 0 || $('.product_quantity').val().trim() == '')) {
                        $('#service_error').text('Selected product quantity must not be 0.');
                        error++;
                    } else if ($('.service_id').val().trim() != '' && $('.product_id').val().trim() != '' &&
                        (
                            $('.service_quantity').val().trim() == 0 || $('.service_quantity').val().trim() == ''
                        ) &&
                        (
                            $('.product_quantity').val().trim() == 0 || $('.product_quantity').val().trim() == '')
                    ) {
                        $('#select_error').text('Both Quantity must not be 0.');
                        error++;
                    } else {
                        $('#service_error').val('');
                        $('#product_error').val('');
                    }

                } else if (currentIndex == 2) {

                    if ($('.technician_notes').val().trim() == '') {
                        $('#technician_notes').text('Technician notes is required.');
                        error++;
                    } else {
                        $('#technician_notes').text('');
                    }

                    if (edit == 0) {

                        if ($('.duration').val().trim() == '') {
                            $('#duration').text('Duration is required.');
                            error++;
                        } else {
                            $('#duration').text('');
                        }
                    }

                }

                return error;
            }

            function rescheduleOldJob(jobid) {

                if (jobid) {

                    $('.searchexistingJob').hide();
                    $('.radioDiv').hide();
                    $('.new_schedule').show();

                    $.ajax({
                        url: "{{ route('existing.schedule') }}",
                        data: {
                            jobid: jobid,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data) {

                                $('.customerAuto').val(data.customername);
                                $('.customer_id').val(data.customer_id);
                                $('.customerAuto').attr('readonly', 'readonly');
                                $('.technician_name').val(data.technicianname);
                                $('#technician_id').val(data.technician_id);

                                $('.job_title').val(data.job_title);
                                $('.job_code').val(data.job_code);
                                $('.priority').val(data.priority);
                                $('.description').val(data.description);
                                $('.job_type').val(data.job_type);

                                $('.datetime').val(data.start_date_time);
                                $('.datetime').removeAttr('readonly');
                                $('.technician_name').removeAttr('readonly');

                                $('.technician_notes').val(data.technician_notes);
                                $('.duration').val(data.duration);

                                var selectElement = $('.address');

                                if (data.address && $.isArray(data.address)) {

                                    $.each(data.address, function(index, element) {

                                        var addressString = element.address_type + ': ' +
                                            element.address_line1 + ', ' + element.city +
                                            ', ' + element.state_name + ', ' + element
                                            .zipcode;
                                        var option = $('<option>', {
                                            value: element.address_type,
                                            text: addressString
                                        });

                                        selectElement.append(option);
                                    });
                                }

                                $('.address').val(data.address_type);
                                $('.service_id').val(data.service_id);
                                $('.searchServices').val(data.service_name);
                                $('.service_description').val(data.service_description);
                                $('.service_quantity').val(1);
                                $('.service_cost').val(data.service_cost);
                                $('.service_total').val(data.service_cost);
                                $('.service_discount').val(data.service_discount);
                                $('.pre_service_discount').val(data.service_discount);
                                $('.service_tax').val(data.service_tax);
                                $('.service_line_total').empty();
                                $('.service_line_total').text('$' + data.service_cost);
                                $('.pre_service_tax').val(data.service_tax);

                                var getSubTotalVal = $('.subtotal').val().trim();
                                var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                    .service_cost);
                                $('.subtotal').val(subTotal);
                                $('.subtotaltext').text('$' + subTotal);

                                var getDiscount = $('.discount').val().trim();
                                var discount = parseInt(getDiscount) + parseInt(data
                                    .service_discount);
                                $('.discount').val(discount);
                                $('.discounttext').text('$' + discount);

                                var getTotal = $('.total').val().trim();
                                var total = parseInt(getTotal) + parseInt(data.service_cost) -
                                    parseInt(data.service_discount) + parseInt(data
                                        .service_tax);
                                $('.total').val(total);
                                $('.totaltext').text('$' + total);

                                $('.product_id').val(data.product_id);
                                $('.searchProduct').val(data.product_name);
                                $('.product_description').val(data.product_description);
                                $('.product_quantity').val(1);
                                $('.product_cost').val(data.product_cost);
                                $('.product_total').val(data.product_cost);
                                $('.product_discount').val(data.product_discount);
                                $('.product_line_total').empty();
                                $('.product_line_total').text('$' + data.product_cost);
                                $('.product_tax').val(data.product_tax);
                                $('.pre_product_discount').val(data.product_discount);
                                $('.pre_product_tax').val(data.product_tax);

                                var getSubTotalVal = $('.subtotal').val().trim();
                                var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                    .product_cost);
                                $('.subtotal').val(subTotal);
                                $('.subtotaltext').text('$' + subTotal);

                                var getDiscount = $('.discount').val().trim();
                                var discount = parseInt(getDiscount) + parseInt(data.product_discount);
                                $('.discount').val(discount);
                                $('.discounttext').text('$' + discount);

                                var getTotal = $('.total').val().trim();
                                var total = parseInt(getTotal) + parseInt(data.product_cost) -
                                    parseInt(data.product_discount) + parseInt(data.product_tax);
                                $('.total').val(total);
                                $('.totaltext').text('$' + total);

                                $('a[href="#finish"]:eq(0)').text(
                                    'Reschedule Job');

                            }
                        }
                    });
                }

            }

            $(document).on('click', '.createSchedule', function() {

                scheduleButton = $(this).closest('td');

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

                                var container = $('#createScheduleForm').find(
                                    'section[data-step="' + currentIndex +
                                    '"]'
                                );

                                if (newIndex < currentIndex) {
                                    return true;
                                }

                                var edit = 0;

                                var validation = validateForm(currentIndex, edit);

                                if (validation != 0) {
                                    return false;
                                }

                                return true;
                            },
                            onFinished: function(event, currentIndex) {

                                if (!ajaxRequestForSubmit) {

                                    var edit = 0;

                                    var validation = validateForm(currentIndex,
                                        edit);

                                    if (validation != 0) {
                                        return false;
                                    }

                                    $('a[href="#finish"]:eq(0)').text(
                                        'processing...');

                                    var form = $('#createScheduleForm')[0];
                                    var params = new FormData(form);

                                    ajaxRequestForSubmit = $.ajax({
                                        url: url,
                                        data: params,
                                        method: 'post',
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                            'X-CSRF-TOKEN': $(
                                                'meta[name="csrf-token"]'
                                            ).attr('content')
                                        },
                                        success: function(data) {

                                            $('a[href="#finish"]:eq(0)')
                                                .text('Submit Job');

                                            ajaxRequestForSubmit = '';

                                            if (date.start_date) {

                                                $('.btn-close').trigger(
                                                    'click');

                                                Swal.fire({
                                                    title: "Success!",
                                                    text: "Job Has Been Reschedule",
                                                    icon: "success"
                                                }).then((
                                                    result) => {
                                                    if (result
                                                        .isConfirmed ||
                                                        result
                                                        .dismiss ===
                                                        Swal
                                                        .DismissReason
                                                        .backdrop
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
                                }
                            },
                        });

                        $('.customerAuto').typeahead({
                            source: function(query, process) {
                                return $.get(
                                    "{{ route('autocomplete.search') }}", {
                                        query: query
                                    },
                                    function(data) {
                                        return process(data);
                                    });
                            }
                        });

                        $('.searchServices').typeahead({
                            source: function(query1, process1) {
                                return $.get(
                                    "{{ route('autocomplete.services') }}", {
                                        query: query1
                                    },
                                    function(data1) {
                                        return process1(data1);
                                    });
                            }
                        });

                        $('.searchProduct').typeahead({
                            source: function(query2, process2) {
                                return $.get(
                                    "{{ route('autocomplete.product') }}", {
                                        query: query2
                                    },
                                    function(data2) {
                                        return process2(data2);
                                    });
                            }
                        });

                        // var engine = new Bloodhound({
                        //     datumTokenizer: Bloodhound.tokenizers.whitespace,
                        //     queryTokenizer: Bloodhound.tokenizers.whitespace,
                        //     remote: {
                        //         url: "{{ route('autocomplete.serchOldJob') }}?query=%QUERY",
                        //         wildcard: '%QUERY'
                        //     },
                        //     identify: function(obj) {
                        //         console.log($('#job_id'), obj.id, item);
                        //         return obj.id;
                        //     }
                        // });

                        // $('#serchOldJob').typeahead({
                        //     hint: true,
                        //     highlight: true,
                        //     minLength: 1
                        // }, {
                        //     name: 'engine',
                        //     source: engine,
                        //     display: 'name',
                        //     limit: 5
                        // }).on('typeahead:select', function(event, item) {
                        //     $('#job_id').val(item.id);
                        //     rescheduleOldJob(item.id);
                        // });

                        $('.technician_name').typeahead({
                            source: function(query4, process4) {
                                return $.get(
                                    "{{ route('autocomplete.technician') }}", {
                                        query: query4
                                    },
                                    function(data4) {
                                        return process4(data4);
                                    });
                            },
                            updater: function(item) {
                                $('#technician_id').val(item.id);
                                return item.name;
                            }
                        });

                        $('#serchOldJob').typeahead({
                            source: function(query3, process3) {
                                return $.get(
                                    "{{ route('autocomplete.serchOldJob') }}", {
                                        query: query3
                                    },
                                    function(data3) {
                                        return process3(data3);
                                    });
                            },
                            updater: function(item) {
                                $('#job_id').val(item.id);
                                rescheduleOldJob(item.id);
                                return item.name;
                            },
                            items: 5
                        });
                    }
                });
            });

            $(document).on('change', '.customerAuto', function(event) {

                event.stopPropagation();

                var name = $(this).val().trim();

                $('.customer_id').val('');

                var selectElement = $('.address');
                selectElement.empty();

                if (ajaxRequestForCustomer) {
                    ajaxRequestForCustomer.abort();
                }

                if (name.length != 0) {

                    ajaxRequestForCustomer = $.ajax({
                        url: "{{ route('customer.details') }}",
                        data: {
                            name: name,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data.id) {
                                $('.customer_id').val(data.id);
                            }
                            if (data.address && $.isArray(data.address)) {

                                $.each(data.address, function(index, element) {

                                    var addressString = element.address_type + ': ' +
                                        element.address_line1 + ', ' + element.city +
                                        ', ' + element.state_name + ', ' + element
                                        .zipcode;
                                    var option = $('<option>', {
                                        value: element.address_type,
                                        text: addressString
                                    });

                                    selectElement.append(option);
                                });
                            }

                        }
                    });
                }

            });

            $(document).on('change', '.searchServices', function(event) {

                event.stopPropagation();

                var searchServices = $(this).val().trim();

                if (ajaxRequestForService) {
                    ajaxRequestForService.abort();
                }

                var service_id = $('.service_id').val();

                if (service_id.length != 0) {

                    var totalCost = parseInt($('.service_total').val()) *
                        parseInt($('.service_quantity').val());
                    var discount = $('.service_discount').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(
                        totalCost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    var totalDiscount = $('.discount').val();
                    var finnalDiscount = parseInt(totalDiscount) - parseInt(
                        discount);
                    $('.discount').val(finnalDiscount);
                    $('.discounttext').text('$' + finnalDiscount);

                    var getTotal = parseInt($('.total').val().trim()) +
                        parseInt(discount);
                    var total = parseInt(getTotal) - parseInt(totalCost);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                    $('.service_id').val('');
                    $('.service_description').val('');
                    $('.service_quantity').val('');
                    $('.service_cost').val('');
                    $('.service_total').val('');
                    $('.service_discount').val('');
                    $('.pre_service_discount').val('');
                    $('.service_line_total').empty();
                    $('.service_line_total').text('$0');
                    $('.service_tax').val('');
                    $('.pre_service_tax').val('');
                }

                if (searchServices.length != 0) {

                    ajaxRequestForService = $.ajax({
                        url: "{{ route('services.details') }}",
                        data: {
                            searchServices: searchServices,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data.created_by) {

                                $('.service_id').val(data.service_id);
                                $('.service_description').val(data.service_description);
                                $('.service_quantity').val(1);
                                $('.service_cost').val(data.service_cost);
                                $('.service_total').val(data.service_cost);
                                $('.service_discount').val(data.service_discount);
                                $('.pre_service_discount').val(data.service_discount);
                                $('.service_tax').val(data.service_tax);
                                $('.service_line_total').empty();
                                $('.service_line_total').text('$' + data.service_cost);
                                $('.pre_service_tax').val(data.service_tax);

                                var getSubTotalVal = $('.subtotal').val().trim();
                                var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                    .service_cost);
                                $('.subtotal').val(subTotal);
                                $('.subtotaltext').text('$' + subTotal);

                                var getDiscount = $('.discount').val().trim();
                                var discount = parseInt(getDiscount) + parseInt(data
                                    .service_discount);
                                $('.discount').val(discount);
                                $('.discounttext').text('$' + discount);

                                var getTotal = $('.total').val().trim();
                                var total = parseInt(getTotal) + parseInt(data.service_cost) -
                                    parseInt(data.service_discount) + parseInt(data
                                        .service_tax);
                                $('.total').val(total);
                                $('.totaltext').text('$' + total);


                            }

                        }
                    });
                }

            });

            $(document).on('change', '.searchProduct', function(event) {

                event.stopPropagation();

                var searchProduct = $(this).val().trim();

                if (ajaxRequestForProduct) {
                    ajaxRequestForProduct.abort();
                }

                var product_id = $('.product_id').val();

                if (product_id.length != 0) {

                    var totalCost = parseInt($('.product_total').val()) *
                        parseInt($('.product_quantity').val());
                    var discount = $('.product_discount').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(
                        totalCost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    var totalDiscount = $('.discount').val();
                    var finnalDiscount = parseInt(totalDiscount) - parseInt(
                        discount);
                    $('.discount').val(finnalDiscount);
                    $('.discounttext').text('$' + finnalDiscount);

                    var getTotal = parseInt($('.total').val().trim()) +
                        parseInt(discount);
                    var total = parseInt(getTotal) - parseInt(totalCost);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                    $('.product_id').val('');
                    $('.product_description').val('');
                    $('.product_quantity').val('');
                    $('.product_cost').val('');
                    $('.product_total').val('');
                    $('.product_tax').val('');
                    $('.product_discount').val('');
                    $('.pre_product_discount').val('');
                    $('.product_line_total').empty();
                    $('.product_line_total').text('$0');
                    $('.pre_product_tax').val('');
                }

                if (searchProduct.length != 0) {

                    ajaxRequestForProduct = $.ajax({
                        url: "{{ route('product.details') }}",
                        data: {
                            searchProduct: searchProduct,
                        },
                        type: 'GET',
                        success: function(data) {

                            if (data.created_by) {

                                $('.product_id').val(data.product_id);
                                $('.product_description').val(data.product_description);
                                $('.product_quantity').val(1);
                                $('.product_cost').val(data.base_price);
                                $('.product_total').val(data.base_price);
                                $('.product_discount').val(data.discount);
                                $('.product_line_total').empty();
                                $('.product_line_total').text('$' + data.base_price);
                                $('.product_tax').val(data.tax);
                                $('.pre_product_discount').val(data.discount);
                                $('.pre_product_tax').val(data.tax);

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

            $(document).on('change', '.service_cost', function() {

                var quantity = $('.service_quantity').val().trim();

                if ($('.service_quantity').val().trim() == 0) {
                    return true;
                }

                var service_cost = $(this).val().trim();
                if ($(this).val().trim() === '') {
                    service_cost = 0;
                }
                var cost = parseInt(quantity) * parseInt(service_cost);
                var preCost = parseInt(quantity) * parseInt($('.service_total').val());

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val($('.service_total').val());
                    return true;
                }

                if (cost != 0) {

                    $('.service_line_total').empty();
                    $('.service_line_total').text('$' + cost);
                    $('.service_total').val(service_cost);

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(preCost);
                    var currentSubTotal = parseInt(subTotal) + parseInt(cost);
                    $('.subtotal').val(currentSubTotal);
                    $('.subtotaltext').text('$' + currentSubTotal);

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(preCost);
                    var currentTotal = parseInt(total) + parseInt(cost);
                    $('.total').val(currentTotal);
                    $('.totaltext').text('$' + currentTotal);

                }
            });

            $(document).on('change', '.product_cost', function() {

                var quantity = $('.product_quantity').val().trim();

                if ($('.product_quantity').val().trim() == 0) {
                    return true;
                }

                var product_cost = $(this).val().trim();

                if ($(this).val().trim() === '') {
                    product_cost = 0;
                }

                var cost = parseInt(quantity) * parseInt(product_cost);
                var preCost = parseInt(quantity) * parseInt($('.product_total').val());

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val($('.product_total').val());
                    return true;
                }

                if (cost != 0) {

                    $('.product_line_total').empty();
                    $('.product_line_total').text('$' + cost);
                    $('.product_total').val(product_cost);

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(preCost);
                    var currentSubTotal = parseInt(subTotal) + parseInt(cost);
                    $('.subtotal').val(currentSubTotal);
                    $('.subtotaltext').text('$' + currentSubTotal);

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(preCost);
                    var currentTotal = parseInt(total) + parseInt(cost);
                    $('.total').val(currentTotal);
                    $('.totaltext').text('$' + currentTotal);
                }
            });

            $(document).on('change', '.service_quantity', function() {

                var quantity = $(this).val().trim();

                if ($(this).val() === '') {
                    quantity = 0;
                }

                var preQuantity = $('.pre_service_quantity').val();
                var preCost = $('.service_total').val();
                var discount = $('.service_discount').val();
                var service_tax = $('.service_tax').val().trim();

                if (/-/.test(quantity) || /\./.test(quantity)) {
                    $(this).val(preQuantity);
                    return true;
                }

                if (preQuantity > quantity) {

                    $('.pre_service_quantity').val(quantity);
                    var totalCost = parseInt(preCost) * parseInt(quantity);
                    $('.service_line_total').empty();
                    $('.service_line_total').text('$' + totalCost);
                    $('.service_added_quantity_price').val(totalCost)

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(preCost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    if (quantity == 0) {
                        var totalDiscount = $('.discount').val();
                        var preCost = parseInt(preCost) - parseInt(discount);
                        var finnalDiscount = parseInt(totalDiscount) - parseInt(discount);
                        $('.discount').val(finnalDiscount);
                        $('.discounttext').text('$' + finnalDiscount);
                    }

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(preCost) - parseInt(service_tax);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                } else {

                    var totalCost = parseInt(preCost) * parseInt(quantity);
                    $('.service_line_total').empty();
                    $('.service_line_total').text('$' + totalCost);
                    $('.service_added_quantity_price').val(totalCost)

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var currentSubTotal = parseInt(getSubTotalVal) + parseInt(preCost);
                    $('.subtotal').val(currentSubTotal);
                    $('.subtotaltext').text('$' + currentSubTotal);

                    if (quantity == 1) {
                        var totalDiscount = $('.discount').val();
                        var preCost = parseInt(preCost) - parseInt(discount);
                        var finnalDiscount = parseInt(totalDiscount) + parseInt(discount);
                        $('.discount').val(finnalDiscount);
                        $('.discounttext').text('$' + finnalDiscount);
                    }

                    var getTotal = $('.total').val().trim();
                    var currentTotal = parseInt(getTotal) + parseInt(preCost) + parseInt(service_tax);
                    $('.total').val(currentTotal);
                    $('.totaltext').text('$' + currentTotal);

                    $('.pre_service_quantity').val(quantity);
                }


            });

            $(document).on('change', '.product_quantity', function() {

                var quantity = $(this).val().trim();
                if ($(this).val() === '') {
                    quantity = 0;
                }
                var preQuantity = $('.pre_product_quantity').val();
                var preCost = $('.product_total').val();
                var discount = $('.product_discount').val();
                var product_tax = $('.product_tax').val().trim();

                if (/-/.test(quantity) || /\./.test(quantity)) {
                    $(this).val(preQuantity);
                    return true;
                }

                if (preQuantity > quantity) {

                    $('.pre_product_quantity').val(quantity);
                    var totalCost = parseInt(preCost) * parseInt(quantity);
                    $('.product_line_total').empty();
                    $('.product_line_total').text('$' + totalCost);
                    $('.product_added_quantity_price').val(totalCost)

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(preCost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    if (quantity == 0) {
                        var totalDiscount = $('.discount').val();
                        var preCost = parseInt(preCost) - parseInt(discount);
                        var finnalDiscount = parseInt(totalDiscount) - parseInt(discount);
                        $('.discount').val(finnalDiscount);
                        $('.discounttext').text('$' + finnalDiscount);
                    }

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(preCost) - parseInt(product_tax);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                } else {

                    var totalCost = parseInt(preCost) * parseInt(quantity);
                    $('.product_line_total').empty();
                    $('.product_line_total').text('$' + totalCost);
                    $('.product_added_quantity_price').val(totalCost)

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var currentSubTotal = parseInt(getSubTotalVal) + parseInt(preCost);
                    $('.subtotal').val(currentSubTotal);
                    $('.subtotaltext').text('$' + currentSubTotal);

                    if (quantity == 1) {
                        var totalDiscount = $('.discount').val();
                        var preCost = parseInt(preCost) - parseInt(discount);
                        var finnalDiscount = parseInt(totalDiscount) + parseInt(discount);
                        $('.discount').val(finnalDiscount);
                        $('.discounttext').text('$' + finnalDiscount);
                    }

                    var getTotal = $('.total').val().trim();
                    var currentTotal = parseInt(getTotal) + parseInt(preCost) + parseInt(product_tax);
                    $('.total').val(currentTotal);
                    $('.totaltext').text('$' + currentTotal);

                    $('.pre_product_quantity').val(quantity);
                }


            });

            $(document).on('click', '.edit_schedule', function() {

                var id = $(this).attr('data-id');

                editscheduleButton = $(this).closest('td');

                $.ajax({
                    method: 'get',
                    url: "{{ route('schedule.edit') }}",
                    data: {
                        id: id,
                    },
                    beforeSend: function() {
                        $('.editScheduleData').html('Processing Data...');
                    },
                    success: function(data) {

                        $('.editScheduleData').empty();
                        $('.editScheduleData').html(data);

                        $('.tab-wizard2').steps({
                            headerTag: 'h6',
                            bodyTag: 'section',
                            transitionEffect: 'fade',
                            titleTemplate: '<span class="step">#index#</span> #title#',
                            labels: {
                                finish: 'Update Job',
                            },
                            onStepChanging: function(event, currentIndex, newIndex) {

                                var container = $('#updateScheduleForm').find(
                                    'section[data-step="' + currentIndex +
                                    '"]'
                                );

                                if (newIndex < currentIndex) {
                                    return true;
                                }

                                var edit = 1;

                                var validation = validateForm(currentIndex, edit);

                                if (validation != 0) {
                                    return false;
                                }

                                return true;
                            },
                            onFinished: function(event, currentIndex) {

                                if (!ajaxRequestForupdate) {

                                    var edit = 1;

                                    var validation = validateForm(currentIndex,
                                        edit);

                                    if (validation != 0) {
                                        return false;
                                    }

                                    $('a[href="#finish"]:eq(0)').text(
                                        'updating...');

                                    var form = $('#updateScheduleForm')[0];
                                    var params = new FormData(form);

                                    ajaxRequestForupdate = $.ajax({
                                        url: "{{ route('schedule.update') }}",
                                        data: params,
                                        method: 'post',
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                            'X-CSRF-TOKEN': $(
                                                'meta[name="csrf-token"]'
                                            ).attr('content')
                                        },
                                        success: function(data) {

                                            $('a[href="#finish"]:eq(0)')
                                                .text('Update Job');

                                            ajaxRequestForupdate = '';

                                            $('.btn-close').trigger(
                                                'click');

                                            if (data == 'false') {

                                                Swal.fire({
                                                    icon: "error",
                                                    title: "Error",
                                                    text: "Something went wrong !",
                                                });

                                            } else if (data
                                                .start_date) {

                                                editscheduleButton
                                                    .empty();

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

                                                Swal.fire({
                                                    title: "Success !",
                                                    text: "Job Has Been Updated",
                                                    icon: "success"
                                                });

                                            } else if (data
                                                .start_date == null) {

                                                Swal.fire({
                                                    title: "Success !",
                                                    text: "Job Has Been Updated",
                                                    icon: "success"
                                                });

                                            }

                                        }
                                    });
                                }
                            },
                        });

                        $('.searchtechnician').typeahead({
                            source: function(query4, process4) {
                                return $.get(
                                    "{{ route('autocomplete.technician') }}", {
                                        query: query4
                                    },
                                    function(data4) {
                                        return process4(data4);
                                    });
                            }
                        });

                        $('.searchServices').typeahead({
                            source: function(query5, process5) {
                                return $.get(
                                    "{{ route('autocomplete.services') }}", {
                                        query: query5
                                    },
                                    function(data5) {
                                        return process5(data5);
                                    });
                            }
                        });

                        $('.searchProduct').typeahead({
                            source: function(query6, process6) {
                                return $.get(
                                    "{{ route('autocomplete.product') }}", {
                                        query: query6
                                    },
                                    function(data6) {
                                        return process6(data6);
                                    });
                            }
                        });
                    }
                });


            });

            $(document).on('change', '.service_discount', function() {

                var service_discount = $('.service_discount').val().trim();

                if (service_discount === '') {
                    service_discount = 0;
                }

                if ($('.service_quantity').val().trim() == 0) {
                    return true;
                }

                var pre_service_discount = $('.pre_service_discount').val().trim();

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val(pre_service_discount);
                    return true;
                }

                var totalDiscount = parseInt($('.discount').val()) - parseInt(pre_service_discount);
                var finnalDiscount = parseInt(totalDiscount) + parseInt(service_discount);
                $('.discount').val(finnalDiscount);
                $('.discounttext').text('$' + finnalDiscount);

                var getTotal = parseInt($('.total').val().trim()) + parseInt(pre_service_discount);
                var total = parseInt(getTotal) - parseInt(service_discount);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.pre_service_discount').val(service_discount);


            });

            $(document).on('change', '.product_discount', function() {

                var product_discount = $('.product_discount').val().trim();

                if (product_discount === '') {
                    product_discount = 0;
                }

                if ($('.product_quantity').val().trim() == 0) {
                    return true;
                }

                var pre_product_discount = $('.pre_product_discount').val().trim();

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val(pre_product_discount);
                    return true;
                }

                var totalDiscount = parseInt($('.discount').val()) - parseInt(pre_product_discount);
                var finnalDiscount = parseInt(totalDiscount) + parseInt(product_discount);
                $('.discount').val(finnalDiscount);
                $('.discounttext').text('$' + finnalDiscount);

                var getTotal = parseInt($('.total').val().trim()) + parseInt(pre_product_discount);
                var total = parseInt(getTotal) - parseInt(product_discount);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.pre_product_discount').val(product_discount);


            });

            $(document).on('change', '.service_tax', function() {

                var service_tax = $('.service_tax').val().trim();

                if (service_tax === '') {
                    service_tax = 0;
                }

                if ($('.service_quantity').val().trim() == 0) {
                    return true;
                }

                var pre_service_tax = $('.pre_service_tax').val().trim();

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val(pre_service_tax);
                    return true;
                }

                var getTotal = parseInt($('.total').val().trim()) + parseInt(service_tax) - parseInt(
                    pre_service_tax);
                $('.total').val(getTotal);
                $('.totaltext').text('$' + getTotal);

                $('.pre_service_tax').val(service_tax);

            });

            $(document).on('change', '.product_tax', function() {

                var product_tax = $('.product_tax').val().trim();

                if (product_tax === '') {
                    product_tax = 0;
                }

                if ($('.product_quantity').val().trim() == 0) {
                    return true;
                }

                var pre_product_tax = $('.pre_product_tax').val().trim();

                if (/-/.test($(this).val()) || /\./.test($(this).val())) {
                    $(this).val(pre_product_tax);
                    return true;
                }

                var getTotal = parseInt($('.total').val().trim()) + parseInt(product_tax) - parseInt(
                    pre_product_tax);
                $('.total').val(getTotal);
                $('.totaltext').text('$' + getTotal);

                $('.pre_product_tax').val(product_tax);

            });

            $(document).on('change', 'input[type="radio"][name="radio-stacked"]', function() {

                var checkedValue = $('input[type="radio"][name="radio-stacked"]:checked').val();
                $('#serchOldJobError').text('');

                if (checkedValue == 'new') {
                    $('.searchexistingJob').hide();
                    $('.new_schedule').show();
                } else {
                    $('.searchexistingJob').show();
                    $('.new_schedule').hide();
                }
            });

        });
    </script>
@endsection
@endsection
