<script src="{{ asset('public/admin/dist/libs/jquery/dist/jquery.min.js') }}"></script>

<script src="{{ asset('public/admin/assets/extra-libs/taskboard/js/jquery-ui.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/popper.js/dist/umd/popper.min.js') }}"></script>

<!-- Bootstrap tether Core JavaScript -->

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
		<script src="{{ asset('public/admin/dist/js/pages/dashboards/dashboard1.js') }}"></script>
		<script src="{{ asset('public/admin/dist/js/pages/apex-chart/apex.pie.init.js') }}"></script>
		<?php 
	}
  ?>  
  
  
  
  
<script src="{{ asset('public/admin/dist/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>

<script src="{{ asset('public/admin/dist/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<!-- working pages  JavaScript by (st) -->

<script src="{{ asset('public/admin/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

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


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to check if URL contains "header=off" and "sidebar=off"
        function shouldApplyScript() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('header') === 'off' && urlParams.get('sidebar') === 'off';
        }

        // Apply the script only if the conditions are met
        if (shouldApplyScript()) {
            // Hide the header
            const header = document.querySelector('header');
            if (header) {
                header.style.display = 'none';
            }

            // Hide the sidebar and remove its space
            const sidebar = document.querySelector('.left-sidebar');
            if (sidebar) {
                sidebar.style.display = 'none';
            }

            // Adjust the main content width if necessary
            const mainWrapper = document.querySelector('#main-wrapper');
            if (mainWrapper) {
                mainWrapper.style.marginLeft = '0';
            }

            // Hide the footer and remove its space
            const footer = document.querySelector('.footer');
            if (footer) {
                footer.style.display = 'none';
            }

            // Inject additional CSS for other functionalities
            const style = document.createElement('style');
            style.type = 'text/css';
            style.innerHTML = `
                /* Hide header, aside, and footer elements */
                header { display: none !important; }
                aside { display: none !important; }
                footer { display: none !important; }

                /* Adjust layout and overflow */
                #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
                    display: none;
                }
                #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
                    display: none;
                }
                #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
                    margin-left: 10px;
                }
                #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
                    padding-top: 10px;
                }

                /* Make content scrollable */
                html, body {
                    overflow: auto !important;
                    margin: 0;
                    padding: 0;
                }

                /* Specific section overflow */
                #scheduleSection1 {
                    overflow: auto !important;
                }
            `;
            document.head.appendChild(style);
        }
    });
</script>



    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css" />
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>





