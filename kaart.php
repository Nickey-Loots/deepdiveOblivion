<?php
// pas hier locaties aan
$waypoints = [
    [53.21124356149085, 6.564106259019599, "Hoofdstation"],
    [53.21743554020972, 6.587921863354231, "IKEA"],
    [53.22108957132685, 6.577004649026435, "UMCG"],
    [53.22158492618018, 6.5691379420181875, "Prinsentuin"],
    [53.21929986992094, 6.5678987592666696, "Martinitoren"],
    [53.21873995007526, 6.5703098768129875, "Het Forum"]
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
        #map { 
            height: 50vh; /* Verlaag de hoogte naar 50% van de viewport */
            width: 80%;   /* Stel de breedte in op 80% van de pagina */
            margin: 20px auto; /* Centreer de kaart horizontaal en voeg wat marge toe */
            border: 2px solid #ccc; /* Voeg een rand toe voor een nette uitstraling */
            border-radius: 8px; /* Maak de hoeken afgerond */
        }
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

    const randomRoute = window.randomRoute || <?php echo json_encode($waypoints); ?>;

    randomRoute.forEach((locatie, index) => {
        L.marker([locatie.lat, locatie.lon])
         .addTo(map)
         .bindPopup((locatie.naam));
    });

    const orsCoords = randomRoute.map(loc => [loc.lon, loc.lat]);

    async function fetchRoute() {
        try {
            const response = await fetch('https://api.openrouteservice.org/v2/directions/foot-walking/geojson', {
                method: 'POST',
                headers: {
                    'Authorization': '<?php echo $apiKey; ?>',
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

        let userMarker;

    function onLocationFound(e) {
        const radius = e.accuracy / 2;

        if (!userMarker) {
            userMarker = L.circleMarker(e.latlng, {
                radius: 8,
                fillColor: "#3388ff",
                color: "#fff",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map).bindPopup("Je bent hier").openPopup();
        } else {
            userMarker.setLatLng(e.latlng);
        }
        }

        function onLocationError(e) {
        console.error("Locatie fout:", e.message);
        }

            map.locate({watch: true, setView: false, enableHighAccuracy: true});
            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);
    }

    fetchRoute();
</script>

</body>
</html>