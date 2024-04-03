<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/popper.js/dist/umd/popper.min.js') }}"></script>

<!-- Bootstrap tether Core JavaScript -->

<script src="{{ asset('public/admin/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- Theme Required Js -->

<script src="{{ asset('public/admin/dist/js/app.min.js') }}"></script>

@if (request()->routeIs('map'))
    <script src="{{ asset('public/admin/dist/js/app.init.mini-sidebar.js') }}"></script>
@else
    <script src="{{ asset('public/admin/dist/js/app.init.js') }}"></script>
@endif


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

<!-- --------------------------------------------------------------- -->

<!-- This page JavaScript -->

<!-- --------------------------------------------------------------- -->
<!-- COMMENTED BY SR
<script src="{{ asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
<script src="{{ asset('public/admin/dist/js/pages/dashboards/dashboard1.js') }}"></script>
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

<script src="{{ asset('public/admin/dist/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<!-- working pages  JavaScript by (st) -->

<script src="{{ asset('public/admin/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/js/pages/datatable/datatable-basic.init.js') }}"></script>
<script>
    $(document).on('click', '.viewinfo', function() {
        $("#add-contact1").modal({
            backdrop: "static",
            keyboard: false,
        });
        var entry_id = $(this).attr('id');
        $("#appendbody").empty();
        $.ajax({
            url: '{{ route("getStoryDetails") }}',
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
    $(document).on('click', '.viewinfo', function() { $("#add-contact1").modal({
    backdrop: "static",
    keyboard: false,
    });
    var entry_id = $(this).attr('id');
    $("#appendbody").empty();
    $.ajax({
   url: '{{ route("editproduct") }}',
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
    function toggleForm() {
        // Get the form container element
        var businessInfo = document.getElementById("businessInfo");
        businessInfo.style.display = businessInfo.style.display === "none" ? "block" : "none";
        var formContainer = document.querySelector(".form-container");
        
        // Toggle the display property
        formContainer.style.display = formContainer.style.display === "none" ? "block" : "none";
    }
</script>
<script>
    function companydescription() {
              var businessInfo = document.getElementById("businessInfo-one");
        businessInfo.style.display = businessInfo.style.display === "none" ? "block" : "none";
        var x = document.querySelector(".form-group1");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<script>
    function messageondocs() {
        var businessInfo = document.getElementById("businessInfo-two");
        businessInfo.style.display = businessInfo.style.display === "none" ? "block" : "none";
        var x = document.querySelector(".form-group2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<script>
    function termsandcondition() {
             var businessInfo = document.getElementById("businessInfo-three");
        businessInfo.style.display = businessInfo.style.display === "none" ? "block" : "none";
        var x = document.querySelector(".form-group3");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<script>
    $(document).on('click', '.viewinfo', function() { $("#add-contact2").modal({
    backdrop: "static",
    keyboard: false,
    });
    var entry_id = $(this).attr('id');
    // console.log(entry_id);
    $("#appendbody1").empty();
    $.ajax({
   url: '{{ route("estimateDetails") }}',
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
