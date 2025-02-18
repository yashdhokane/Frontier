<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/popper.js/dist/umd/popper.min.js') }}"></script>

<!-- Bootstrap tether Core JavaScript -->
<!-- Include the CSS for bootstrap-switch -->


<script src="{{ asset('public/admin/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- Theme Required Js -->

<script src="{{ asset('public/admin/dist/js/app.min.js') }}"></script>

{{-- @if (request()->routeIs('map'))
    <script src="{{ asset('public/admin/dist/js/app.init.mini-sidebar.js') }}"></script>
@else --}}
<script src="{{ asset('public/admin/dist/js/app.init.js') }}"></script>
{{-- @endif --}}


<script src="{{ asset('public/admin/dist/js/app-style-switcher.js') }}"></script>

<!-- perfect scrollbar JavaScript -->

<script src="{{ asset('public/admin/dist/libs/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

<!--Wave Effects -->

<script src="{{ asset('public/admin/dist/js/waves.js') }}"></script>

<!--Menu sidebar -->

<script src="{{ asset('public/admin/dist/js/sidebarmenu.js') }}"></script>

<!--Custom JavaScript -->

<script src="{{ asset('public/admin/dist/js/feather.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/js/custom.min.js') }}"></script>

<script src="{{ asset('public/admin/stickynotes/script.js') }}"></script>

<!-- --------------------------------------------------------------- -->

<!-- This page JavaScript -->

<!-- --------------------------------------------------------------- -->
<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
-->



<!-- working pages  JavaScript by yd -->
<script src="{{ asset('public/admin/dist/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/js/pages/forms/select2/select2.init.js') }}"></script>

<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/js/pages/contact/contact.js') }}"></script>
-->

<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/js/pages/dashboards/dashboard1.js') }}"></script>
-->

<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/js/pages/apex-chart/apex.pie.init.js') }}"></script>
-->

<script src="{{ asset('public/admin/dist/libs/moment/min/moment.min.js') }}"></script>

<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/libs/fullcalendar/index.global.min.js') }}"></script>
-->

<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/js/pages/calendar/cal-init.js') }}"></script>
-->



<?php
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
         $url = "https://";
    else
         $url = "http://";
    // Append the host(domain name, ip) to the URL.
    $url.= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $url.= $_SERVER['REQUEST_URI'];


	if($url == "https://dispatchannel.com/portal/home") {
		//echo $url." home page";
		?>
<script src="{{ asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<!-- <script src="{{ asset('public/admin/dist/js/pages/dashboards/dashboard1.js') }}"></script> -->
<script src="{{ asset('public/admin/dist/js/pages/apex-chart/apex.pie.init.js') }}"></script>
<?php
	}
  ?>




<script src="{{ asset('public/admin/dist/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<!-- working pages  JavaScript by (st) -->

<script src="{{ asset('public/admin/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>

<script src="{{ asset('public/admin/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/dragula/dist/dragula.min.js') }}"></script>

<script>
    $(document).on('click', '.viewinfo', function() {
        $("#add-contact1").modal({
            backdrop: "static",
            keyboard: false,
        });
        var entry_id = $(this).attr('id');
        $("#appendbody").empty();
        $.ajax({
            url: '{{ route('getStoryDetails') }}',
            type: 'get',
            data: {
                entry_id: entry_id

            },
            dataType: 'json',
            success: function(data) {
                $("#appendbody").html(data);
            }
        });
    });
</script>

<script>
    $(document).on('click', '.viewinfo', function() {
        $("#add-contact1").modal({
            backdrop: "static",
            keyboard: false,
        });
        var entry_id = $(this).attr('id');
        $("#appendbody").empty();
        $.ajax({
            url: '{{ route('editproduct') }}',
            type: 'get',
            data: {
                entry_id: entry_id

            },
            dataType: 'json',
            success: function(data) {
                $("#appendbody").html(data);
            }
        });
    });
</script>



<script>
    $(document).on('click', '.viewinfo', function() {
        $("#add-contact2").modal({
            backdrop: "static",
            keyboard: false,
        });
        var entry_id = $(this).attr('id');
        // console.log(entry_id);
        $("#appendbody1").empty();
        $.ajax({
            url: '{{ route('estimateDetails') }}',
            type: 'get',
            data: {
                entry_id: entry_id

            },
            dataType: 'json',
            success: function(data) {
                $("#appendbody1").html(data);
            }
        });
    });
</script>
<script>
    function showImagePreview() {
        var input = document.getElementById('file');
        var imagePreview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreview.src = ''; // Clear the image source if no file is selected.
        }
    }
</script>





<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css" />
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<script src="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>

<script>
    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();

    var radioswitch = (function() {
        var bt = function() {
            $('.radio-switch').on('switch-change', function() {
                    $('.radio-switch').bootstrapSwitch('toggleRadioState');
                }),
                $('.radio-switch').on('switch-change', function() {
                    $('.radio-switch').bootstrapSwitch('toggleRadioStateAllowUncheck');
                }),
                $('.radio-switch').on('switch-change', function() {
                    $('.radio-switch').bootstrapSwitch('toggleRadioStateAllowUncheck', false);
                });
        };
        return {
            init: function() {
                bt();
            },
        };
    })();

    $(document).ready(function() {
        radioswitch.init();
    });
</script>

<script>
    function toggleTimeInput(checkbox, targetId) {
        const parentRow = checkbox.closest('.row'); // Find the parent row
        const targetRow = document.getElementById(targetId); // Find the target row to toggle

        if (checkbox.checked) {
            // If the checkbox is checked
            parentRow.classList.remove('border-btm'); // Remove the 'border-btm' class
            targetRow.classList.remove('d-none');
            // targetRow.classList.add('border-btm');// Show the target row
        } else {
            // If the checkbox is unchecked
            parentRow.classList.add('border-btm'); // Add back the 'border-btm' class
            targetRow.classList.add('d-none');
            //targetRow.classList.remove('border-btm'); // Hide the target row
        }
    }
</script>
