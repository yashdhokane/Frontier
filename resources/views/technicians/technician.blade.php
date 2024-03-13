<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
        }

        #left-panel {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        #map-container {
            flex: 2;
            height: 100%;
            padding: 20px;
        }

        #map {
            height: 100%;
        }

        #technicianList {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .technician-item {
            margin-bottom: 10px;
            cursor: pointer;
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
        }

        .technician-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div id="left-panel">
        <h3>Technicians</h3>
        <ul id="technicianList"></ul>
    </div>
    <div class="card" id="map-container">
        <div id="map"></div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let techniciansData = @json($technicians);
        let markers = [];

        function initMap() {
            map = L.map('map').setView([0, 0], 2);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            populateTechnicianList();
        }

        function populateTechnicianList() {
            const list = document.getElementById('technicianList');
            techniciansData.forEach(technician => {
                const listItem = document.createElement('li');
                listItem.className = 'technician-item';
                listItem.textContent = technician.name;
                listItem.addEventListener('click', () => updateMap(technician));
                list.appendChild(listItem);
            });
        }

        function updateMap(selectedTechnician) {

            markers.forEach(marker => marker.remove());
            markers = [];
            if (selectedTechnician) {
                const coordinates = [selectedTechnician.lat, selectedTechnician.lng];
                map.setView(coordinates, 10);

                const marker = L.marker(coordinates).addTo(map);
                markers.push(marker);
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initMap();
        });
    </script>
</body>

</html>