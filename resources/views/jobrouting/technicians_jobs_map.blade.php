@extends('home')

@section('content')

<style>
#newEntryFields .card-body {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Space between items */
}

#newEntryFields .col-md-2 {
    margin-top: 5px; /* Margin at the top for spacing */
}

.bold {
    font-weight: 600;
}

.form-select, .form-check-input {
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

#submitButton {
    padding: 0.5rem 1.5rem;
}

.select2-container {
    width: 100% !important;
}



//above is style for map above section



  .full-width-map {
    width: 100%;
    height: 500px; 
}

.select2{
    width: 10.75em;
}

#map{
        width: 100%;

}
</style>
<!---
<div class="container-fluid col-md-12" >
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="technicianSelect" class="control-label bold mb-2">Technician</label>
            <select id="technicianSelect" class="form-select">
                <option value="">-- Select Technician --</option>
                @foreach($tech as $technician)
                    <option value="{{ $technician->id }}">
                        {{ $technician->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 align-items-center justify-content-end">
                    <label for="selectedDateselect" class="control-label bold mb-2">Select Date</label>
@php
use Carbon\Carbon;

if (!function_exists('getTimeZoneDate')) {
    function getTimeZoneDate($date, $timezone = null, $format = 'm-d-Y H:i:s')
    {
        if ($date) {
            $timezone = $timezone ?: session('timezone_name', 'UTC');
            return Carbon::parse($date)->setTimezone($timezone)->format($format);
        }
        return null;
    }
}
@endphp


<input type="date" id="selectedDate" class="form-control me-2" style="font-size: 13px;" 
       value="{{ getTimeZoneDate(now(), session('timezone_name', 'UTC'), 'Y-m-d') }}">

            <a id="fetchRoutesButton" class="btn btn-link" style="font-size: 13px; cursor: pointer;"></a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="map" class="full-width-map"></div>
        </div>
    </div>
</div>
 -->


<div class="container-fluid col-md-12">
    <!-- Technician Multi-Select Box and Days Multi-Select in One Row -->
      <!--  <div class="row mb-3">
     
        <div class="col-md-4">
            <label for="technicianSelect" class="control-label bold mb-2">Technicians</label>
            <select id="technicianSelected" name="technician[]" class="form-select select2" multiple>
                <option value="">-- Select Technicians --</option>
                @foreach($tech as $technician)
                    <option value="{{ $technician->id }}">
                        {{ $technician->name }}
                    </option>
                @endforeach
            </select>
        </div>
       <input type="hidden" id="selectedDatehidden" name="date" class="form-control me-2" style="font-size: 13px;" 
       value="{{ getTimeZoneDate(now(), session('timezone_name', 'UTC'), 'Y-m-d') }}">
        <!-- Days Multi-Select -->
         <!--    <div class="col-md-4 align-items-center justify-content-end">
            <label for="daysSelect" class="control-label bold mb-2">Select Days</label>
            <select id="daysSelected" name="days[]" class="form-select select2" multiple>
                <option value="0">Monday</option>
                <option value="1">Tuesday</option>
                <option value="2">Wednesday</option>
                <option value="3">Thursday</option>
                <option value="4">Friday</option>
                <option value="5">Saturday</option>
                <option value="6">Sunday</option>
            </select>
        </div>  -->
      <!--   <div class="col-md-4 align-items-center justify-content-start d-flex">
            <button id="submitButton" type="button" class="btn btn-primary">Submit</button>
        </div>   -->
<div class="row mb-3">
    <!-- Routing Trigger Dropdown -->
    <div class="col-md-6">
    <!--<label for="routingTriggerSelect" class="control-label bold mb-2">Current Routing</label> -->
        <select id="routingTriggerSelect" name="routing_id" class="form-select select">
            <option value="">-- Select Current Routing --</option>
            @foreach($routingTriggers as $routing)
                <option value="{{ $routing->routing_id }}">
                    {{ $routing->routing_title }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Set New Button -->
  <div class="col-md-2 d-flex align-items-end">
    <a href="javascript:void(0);" id="setNewButton" class="text-decoration-none" style="color:black;">Set New Routing</a>
</div>

</div>

<!-- Additional Fields (Initially Hidden) -->
<div id="newEntryFields" class="card mt-4 shadow-sm" style="display: none;">
    <div class="row align-items-center card-body card-border">
        <!-- Territory Select Box -->
        <div class="col-md-2 mb-3">
            <label for="territorySelect" class="control-label bold">Select Territory</label>
            <select id="territorySelect" name="territory" class="form-select select">
                <option value="">-- Select Territory --</option>
                @foreach($location as $routing)
                    <option value="{{ $routing->area_id }}">
                        {{ $routing->area_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Routing Title (Hidden Field) -->
        <input type="hidden" id="routingTitleInput" value="Antanio & Sarah (Fri, Wed, Thu)" name="routing_title">

        <!-- Technician Multi-Select -->
        <div class="col-md-2 mb-3">
            <label for="technicianSelect" class="control-label bold">Technicians</label>
            <select id="technicianSelect" name="technician[]" class="form-select select2" multiple>
                <option value="">-Select Technicians-</option>
                @foreach($tech as $technician)
                    <option value="{{ $technician->id }}">
                        {{ $technician->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Days Multi-Select -->
        <div class="col-md-2 mb-3">
            <label for="daysSelect" class="control-label bold">Select Days</label>
            <select id="daysSelect" name="days[]" class="form-select select2" multiple>
                <option value="0">Monday</option>
                <option value="1">Tuesday</option>
                <option value="2">Wednesday</option>
                <option value="3">Thursday</option>
                <option value="4">Friday</option>
                <option value="5">Saturday</option>
                <option value="6">Sunday</option>
            </select>
        </div>

        <!-- Job Confirmed Radio Buttons -->
        <div class="col-md-2 mb-3">
            <label class="control-label bold d-block">Job Confirmed</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="job_confirmed" id="jobConfirmedYes" value="1">
                <label class="form-check-label" for="jobConfirmedYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="job_confirmed" id="jobConfirmedNo" value="0" checked>
                <label class="form-check-label" for="jobConfirmedNo">No</label>
            </div>
        </div>

        <!-- Parts Available Radio Buttons -->
        <div class="col-md-2 mb-3">
            <label class="control-label bold d-block">Parts Available</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="parts_available" id="partsAvailableYes" value="1">
                <label class="form-check-label" for="partsAvailableYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="parts_available" id="partsAvailableNo" value="0" checked>
                <label class="form-check-label" for="partsAvailableNo">No</label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-md-1 mb-3 text-start">
            <button id="submitButton" type="button" class="btn btn-primary ">Submit</button>
        </div>
    </div>
</div>

<script>
// Toggle visibility of new entry fields when "Set New" button is clicked
document.getElementById('setNewButton').addEventListener('click', function() {
    const newEntryFields = document.getElementById('newEntryFields');
    newEntryFields.style.display = (newEntryFields.style.display === 'none' || newEntryFields.style.display === '') ? 'block' : 'none';
});

// Handle form submission and reset form after success
document.getElementById('submitButton').addEventListener('click', function() {
    const routingTitle = document.getElementById('routingTitleInput').value;
    const territory = document.getElementById('territorySelect').value;
    const technicians = Array.from(document.getElementById('technicianSelect').selectedOptions).map(opt => opt.value);
    const days = Array.from(document.getElementById('daysSelect').selectedOptions).map(opt => opt.value);
    const jobConfirmed = document.querySelector('input[name="job_confirmed"]:checked').value;
    const partsAvailable = document.querySelector('input[name="parts_available"]:checked').value;

    // AJAX request to submit data (modify as needed)
    $.ajax({
        url: "{{ route('map.routingrurl') }}", // Replace with your route
        method: "POST",
        data: {
            routing_title: routingTitle,
            territory: territory,
            technicians: technicians,
            days: days,
            job_confirmed: jobConfirmed,
            parts_available: partsAvailable,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            alert('Data saved successfully!');
            
            // Reset form fields after successful submission
            document.getElementById('territorySelect').value = ''; // Reset Territory
            document.getElementById('routingTitleInput').value = ''; // Reset Routing Title

            // Clear Technician Select2 dropdown
            const technicianSelect = document.getElementById('technicianSelect');
            $(technicianSelect).val([]).trigger('change'); // Clear using Select2

            // Clear Days Select2 dropdown
            const daysSelect = document.getElementById('daysSelect');
            $(daysSelect).val([]).trigger('change'); // Clear using Select2

            // Reset radio buttons
            document.getElementById('jobConfirmedNo').checked = true; // Set default to "No"
            document.getElementById('partsAvailableNo').checked = true; // Set default to "No"

            // Hide the new entry fields again
            document.getElementById('newEntryFields').style.display = 'none';
        },
        error: function(error) {
            console.error(error);
            alert('Error saving data.');
        }
    });
});

</script>





    <!-- Map Display -->
    <div class="row">
        <div class="col-md-12">
            <div id="map" class="full-width-map"></div>
        </div>
    </div>
</div>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap" async defer></script>


<script>
    const techniciansData = {!! $responseJson !!};  // Ensure this JSON data is correct and available

    function initMap() {
        // Create the map centered on a default location
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: { lat: 40.7128, lng: -74.0060 },  // Center on an initial location (NYC in this example)
        });

        // Create a unique color generator
        const getRandomColor = () => '#' + Math.floor(Math.random()*16777215).toString(16);

        // Iterate over each technician's data
        techniciansData.forEach((technicianData) => {
            const technicianLatitude = parseFloat(technicianData.technician.latitude);
            const technicianLongitude = parseFloat(technicianData.technician.longitude);
            
            // Check if technician has valid coordinates
            if (!technicianLatitude || !technicianLongitude) {
                console.log(`Technician ${technicianData.technician.name} has no valid location data.`);
                return;
            }

            // Create technician marker
            const technicianMarker = new google.maps.Marker({
                position: { lat: technicianLatitude, lng: technicianLongitude },
                map: map,
                title: technicianData.technician.name,
                label: 'T',  // Label for technician
            });

            // Info window for technician
            const technicianInfoWindow = new google.maps.InfoWindow({
                content: `<h4>${technicianData.technician.name}</h4><p>${technicianData.technician.full_address}</p>`,
            });

            technicianMarker.addListener("click", () => {
                technicianInfoWindow.open(map, technicianMarker);
            });

            // Prepare waypoints for routing
            const waypoints = technicianData.jobs.map(job => {
                if (job.customer.latitude && job.customer.longitude) {
                    return {
                        location: new google.maps.LatLng(parseFloat(job.customer.latitude), parseFloat(job.customer.longitude)),
                        stopover: true,
                    };
                }
            }).filter(Boolean); // Filter out undefined waypoints

            // Draw the route if there are jobs
            if (waypoints.length > 0) {
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    polylineOptions: {
                        strokeColor: getRandomColor(), // Use a unique color for each technician
                        strokeWeight: 5,
                    },
                    suppressMarkers: true,  // Prevent automatic markers from being added
                });

                const request = {
                    origin: { lat: technicianLatitude, lng: technicianLongitude },
                    destination: waypoints[waypoints.length - 1].location,  // Last customer
                    waypoints: waypoints,
                    travelMode: google.maps.TravelMode.DRIVING,
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error('Directions request failed due to ' + status);
                    }
                });

                // Create markers for each customer
                waypoints.forEach((waypoint, index) => {
                    const customerMarker = new google.maps.Marker({
                        position: waypoint.location,
                        map: map,
                        title: `Customer ${index + 1}`, // Example title
                        label: `${index + 1}`,  // Numbering starts from 1
                    });

                    // Info window for customer
                    const customerInfoWindow = new google.maps.InfoWindow({
                        content: `<h4>Customer ${index + 1}</h4><p>Name: ${technicianData.jobs[index].customer.name}</p><p>Address: ${technicianData.jobs[index].customer.full_address}</p>`,
                    });

                    customerMarker.addListener("click", () => {
                        customerInfoWindow.open(map, customerMarker);
                    });
                });
            } else {
                // If there are no jobs, we stop the route and show the technician's info only
               // const noJobInfoWindow = new google.maps.InfoWindow({
               //     content: `<h4>${technicianData.technician.name}</h4><p>${technicianData.technician.full_address}</p><p>No jobs available for this technician.</p>`,
              //  });

                technicianMarker.addListener("click", () => {
                    noJobInfoWindow.open(map, technicianMarker);
                });
            }
        });
    }
</script>

<script>
    document.getElementById('selectedDate').addEventListener('change', function() {
        const selectedDate = this.value; // Get the selected date

        if (!selectedDate) {
            alert("Please select a date.");
            return;
        }

        // Make the AJAX call to your route
        fetch(`/portal/map-raute-date?date=${selectedDate}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if using Laravel
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse the JSON from the response
        })
        .then(data => {
            // Update the map div with the response data
            const mapDiv = document.getElementById('map'); // Assuming your map div has id 'map'
            const techniciansData = data; // Use the response data directly

            // Clear the existing map content (if needed)
            mapDiv.innerHTML = '';

            // Call the function to initialize the map with fetched data
            initMap1(techniciansData);
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    });

    function initMap1(techniciansData) {
        // Create the map centered on a default location
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: { lat: 40.7128, lng: -74.0060 },  // Center on an initial location (NYC in this example)
        });

        // Create a unique color generator
        const getRandomColor = () => '#' + Math.floor(Math.random()*16777215).toString(16);

        // Iterate over each technician's data
        techniciansData.forEach((technicianData) => {
            const technicianLatitude = parseFloat(technicianData.technician.latitude);
            const technicianLongitude = parseFloat(technicianData.technician.longitude);
            
            // Check if technician has valid coordinates
            if (!technicianLatitude || !technicianLongitude) {
                console.log(`Technician ${technicianData.technician.name} has no valid location data.`);
                return;
            }

            // Create technician marker
            const technicianMarker = new google.maps.Marker({
                position: { lat: technicianLatitude, lng: technicianLongitude },
                map: map,
                title: technicianData.technician.name,
                label: 'T',  // Label for technician
            });

            // Info window for technician
            const technicianInfoWindow = new google.maps.InfoWindow({
                content: `<h4>${technicianData.technician.name}</h4><p>${technicianData.technician.full_address}</p>`,
            });

            technicianMarker.addListener("click", () => {
                technicianInfoWindow.open(map, technicianMarker);
            });

            // Prepare waypoints for routing
            const waypoints = technicianData.jobs.map(job => {
                if (job.customer.latitude && job.customer.longitude) {
                    return {
                        location: new google.maps.LatLng(parseFloat(job.customer.latitude), parseFloat(job.customer.longitude)),
                        stopover: true,
                    };
                }
            }).filter(Boolean); // Filter out undefined waypoints

            // Draw the route if there are jobs
            if (waypoints.length > 0) {
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    polylineOptions: {
                        strokeColor: getRandomColor(), // Use a unique color for each technician
                        strokeWeight: 5,
                    },
                    suppressMarkers: true,  // Prevent automatic markers from being added
                });

                const request = {
                    origin: { lat: technicianLatitude, lng: technicianLongitude },
                    destination: waypoints[waypoints.length - 1].location,  // Last customer
                    waypoints: waypoints,
                    travelMode: google.maps.TravelMode.DRIVING,
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error('Directions request failed due to ' + status);
                    }
                });

                // Create markers for each customer
                waypoints.forEach((waypoint, index) => {
                    const customerMarker = new google.maps.Marker({
                        position: waypoint.location,
                        map: map,
                        title: `Customer ${index + 1}`, // Example title
                        label: `${index + 1}`,  // Numbering starts from 1
                    });

                    // Info window for customer
                    const customerInfoWindow = new google.maps.InfoWindow({
                        content: `<h4>Customer ${index + 1}</h4><p>Name: ${technicianData.jobs[index].customer.name}</p><p>Address: ${technicianData.jobs[index].customer.full_address}</p>`,
                    });

                    customerMarker.addListener("click", () => {
                        customerInfoWindow.open(map, customerMarker);
                    });
                });
            } else {
                // If there are no jobs, we stop the route and show the technician's info only
                technicianMarker.addListener("click", () => {
                    technicianInfoWindow.open(map, technicianMarker);
                });
            }
        });
    }
</script>
 <script>
// document.getElementById('submitButton').addEventListener('click', function () {
//     const technicianIds = $('#technicianSelected').val();
//     const daysIds = $('#daysSelected').val();
//         const date = $('#selectedDatehidden').val();


//     $.ajax({
//         url: '{{ route("map.routing.store") }}',
//         method: 'POST',
//         data: {
//             technician: technicianIds,
//             days: daysIds,
//                         date: date,

//             _token: '{{ csrf_token() }}'
//         },
//         success: function (response) {
//             alert(response.success);
//         },
//         error: function (xhr) {
//             alert('An error occurred');
//         }
//     });
// });

</script>

@endsection