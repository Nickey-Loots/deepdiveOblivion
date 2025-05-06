<?php
// pas hier locaties aan
$waypoints = [
    [53.21124356149085, 6.564106259019599],
    [53.21743554020972, 6.587921863354231],
    [53.22108957132685, 6.577004649026435],
    [53.22158492618018, 6.5691379420181875],
    [53.21929986992094, 6.5678987592666696],
    [53.21873995007526, 6.5703098768129875]
];
$apiKey = '5b3ce3597851110001cf6248f327d64ee5e8476b8d77831febc00fd0';
?>
<!DOCTYPE html>
<html>
<head>
    <title>ORS Walking Route</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 100vh; }
    </style>
</head>
<body>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([53.2197, 6.5681], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const coordinates = <?php echo json_encode($waypoints); ?>;
    const orsApiKey = '<?php echo $apiKey; ?>';

    coordinates.forEach((coord, index) => {
        L.marker([coord[0], coord[1]])
         .addTo(map)
         .bindPopup("Waypoint " + (index + 1));
    });

    const orsCoords = coordinates.map(c => [c[1], c[0]]);

    async function fetchRoute() {
        try {
            const response = await fetch('https://api.openrouteservice.org/v2/directions/foot-walking/geojson', {
                method: 'POST',
                headers: {
                    'Authorization': orsApiKey,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    coordinates: orsCoords
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            const geojson = L.geoJSON(data, {
                style: { color: 'blue', weight: 4 }
            }).addTo(map);
            map.fitBounds(geojson.getBounds());
        } catch (err) {
            console.error('ORS Error:', err);
        }
    }

    fetchRoute();
</script>

</body>
</html>