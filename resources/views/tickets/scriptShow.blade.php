@section('script')
    <!-- This page JavaScript -->
    <!-- --------------------------------------------------------------- -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/tinymce/tinymce.min.js"></script>
    <!--c3 charts -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/d3-3.5.6.js"></script>


    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/c3-0.4.9.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#manufacturer_ids').select2();


            $('.addnotes').click(function() {
                $('.shownotes').toggle('fade', function() {
                    if ($(this).is(':visible')) { // Check if the element is visible after toggle
                        $('html, body').animate({
                            scrollTop: $(this).offset()
                                .top // Scroll to the top position of the element
                        }, 'fast');
                    }
                });
            });
            $('.addCustomerTags').click(function() {
                $('.showCustomerTags').toggle('fade');

            });
            $('.addJobTags').click(function() {
                $('.showJobTags').toggle('fade');

            });
            $('.addAttachment').click(function() {
                $('.showAttachment').toggle('fade');

            });
            $('.addSource').click(function() {
                $('.showSource').toggle('fade');

            });
        });
    </script>

    <script>
        $(function() {
            tinymce.init({
                selector: 'textarea#mymce'
            });
            $('#submitBtn').click(function() {
                // Check if the TinyMCE textarea is empty
                if (tinymce.activeEditor.getContent().trim() === '') {
                    // If textarea is empty, prevent form submission
                    alert('Please enter a Job note.');
                    return false;
                }
            });
            // ==============================================================
            // Our Visitor
            // ==============================================================

            var chart = c3.generate({
                bindto: '#visitor',
                data: {
                    columns: [
                        ['Open', 4],
                        ['Closed', 2],
                        ['In progress', 2],
                        ['Other', 0],
                    ],

                    type: 'donut',
                    tooltip: {
                        show: true,
                    },
                },
                donut: {
                    label: {
                        show: false,
                    },
                    title: 'Tickets',
                    width: 35,
                },

                legend: {
                    hide: true,
                    //or hide: 'data1'
                    //or hide: ['data1', 'data2']
                },
                color: {
                    pattern: ['#40c4ff', '#2961ff', '#ff821c', '#7e74fb'],
                },
            });
        });
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicianlocation->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicianlocation->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=13';

        document.getElementById('map').src = mapUrl;
        // var streetViewUrl =
        //   'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
        //   latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        //  document.getElementById('map').src = streetViewUrl;
    </script>
    <script>
        setTimeout(function() {
            $('#successMessage').fadeOut('fast');
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicians->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicians->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        // var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
        //     latitude + ',' + longitude + '&zoom=18';
        var streetViewUrl =
            'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
            latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        document.getElementById('map238').src = streetViewUrl;

        // document.getElementById('map238').src = mapUrl;
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {!! isset($technicians->addresscustomer->latitude) ? $technicians->addresscustomer->latitude : 'null' !!}; // Example latitude
        var longitude = {!! isset($technicians->addresscustomer->longitude) ? $technicians->addresscustomer->longitude : 'null' !!}; // Example longitude


        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=18';
        //  var streetViewUrl =
        //    'https://www.google.com/maps/embed/v1/streetview?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&location=' +
        //    latitude + ',' + longitude + '&heading=210&pitch=10&fov=35';

        // Set the source of the iframe to the Street View URL
        //document.getElementById('map').src = streetViewUrl;
        document.getElementById('map').src = mapUrl;

        // document.getElementById('map238').src = mapUrl;
    </script>
@endsection
