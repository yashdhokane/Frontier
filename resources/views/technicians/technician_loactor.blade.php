@if(Route::currentRouteName() != 'dash')



@extends('home')
@section('content')

@endif
<style>
    .technician-item:hover {
        background-color: #e8e8e8;
        /* Change the background color to your preferred color */
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col bg-light py-2 px-3 border">
                    <h4 style="margin-left:5px;">Technicians List</h4>
                    <ul class="list-group" id="technicianList">
                        @foreach($technicians as $technician)
                        <li class="list-group-item technician-item" style="cursor: pointer;"
                            data-lat="{{ $technician->lat }}" data-lng="{{ $technician->lng }}">
                            <span style="font-size: 15px;font-weight: 700;letter-spacing: 1px;" onclick="showTechnicianLocation({
                                name: '{{ $technician->name }}',
                                designation: '{{ $technician->designation }}',
                                task_assign: '{{ $technician->task_assign }}',
                                lat: {{ $technician->lat }},
                                lng: {{ $technician->lng }}
                            })">
                                <i class="fas fa-map-marker-alt"></i> {{ $technician->name }}
                            </span><br />
                            <span style="font-size: 13px;font-weight: 700;letter-spacing: 1px;">
                                {{ $technician->designation }}
                            </span><br />
                            <span style="font-size: 11px;">{{ $technician->task_assign }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-9 bg-light py-2 px-3 border">
                    <div id="mapContainer">
                        {{-- Map will be rendered here --}}
                        <iframe id="mapIframe" width="100%" height="400" frameborder="0" scrolling="no" marginheight="0"
                            marginwidth="0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=68.1868%2C7.9651%2C97.3956%2C35.8215&layer=mapnik"></iframe>
                        <br /><small><a
                                href="https://www.openstreetmap.org/?mlat=20.5937&mlon=78.9629#map=5/20.5937/78.9629">View
                                Larger Map</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ... Your existing HTML code ... -->

<script>
    function closeTechnicianDetailsModal() {
        $('#technicianDetailsModal').modal('hide');
    }

    function showTechnicianLocation(technician) {
        const mapIframe = document.getElementById('mapIframe');
        const mapUrl = `https://www.openstreetmap.org/export/embed.html?bbox=${technician.lng - 0.01}%2C${technician.lat -
            0.01}%2C${technician.lng + 0.01}%2C${technician.lat + 0.01}&layer=mapnik&marker=${technician.lat}%2C${technician.lng}`;

        // Set the technician details in the modal body
        $('#technicianName').text(technician.name);
        $('#technicianDesignation').text(technician.designation);
        $('#technicianTaskAssign').text(technician.task_assign);

        // Show the modal
        $('#technicianDetailsModal').modal('show');

        // Set the map URL
        mapIframe.src = mapUrl;
    }

    function setDefaultMapLocation() {
        const mapIframe = document.getElementById('mapIframe');
        const defaultMapUrl = 'https://www.openstreetmap.org/export/embed.html?bbox=68.1868%2C7.9651%2C97.3956%2C35.8215&layer=mapnik';

        // Set the default map URL
        mapIframe.src = defaultMapUrl;
    }

    // Call setDefaultMapLocation when the page loads to set the default map view for India
    document.addEventListener('DOMContentLoaded', setDefaultMapLocation);
</script>

<!-- ... Your existing HTML code ... -->

@if(Route::currentRouteName() != 'dash')

@stop
@endif