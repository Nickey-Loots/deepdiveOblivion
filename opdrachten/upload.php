<?php
ob_start(); // Start output buffering om headers later te kunnen gebruiken

// Zorg dat de map bestaat
$uploadMap = 'uploads/';
if (!is_dir($uploadMap)) {
    mkdir($uploadMap, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bestand'])) {
    $bestand = $_FILES['bestand'];
    $bestandsNaam = basename($bestand['name']);
    $doelPad = $uploadMap . time() . '-' . $bestandsNaam;

    if (move_uploaded_file($bestand['tmp_name'], $doelPad)) {
        // ✅ Upload gelukt, redirect naar volgende opdracht
        header("Location: opdracht.php?voltooid=1");
        exit;
    } else {
        echo "Er is iets misgegaan bij het uploaden.";
    }
}

ob_end_flush(); // Stuur output nu naar de browser
?>


<!-- === HTML FORMULIER MET LOCATIE === -->
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Foto inleverformulier met GPS</title>
    <script>
        // Vraag locatie op bij laden van de pagina
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                }, function(error) {
                    console.warn("Locatie niet beschikbaar: ", error.message);
                });
            } else {
                alert("Je browser ondersteunt geen geolocatie.");
            }
        };
    </script>
</head>
<body>
    <h1>Lever je opdrachtfoto in</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="naam">Je naam:</label><br>
        <input type="text" name="naam" id="naam" required><br><br>

        <label for="opdracht">Opdracht:</label><br>
        <input type="text" name="opdracht" id="opdracht" required><br><br>

        <label for="foto">Upload je foto:</label><br>
        <input type="file" name="bestand" id="foto" accept="image/*" required><br><br>

        <!-- Verborgen velden voor GPS -->
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <input type="submit" value="Verzenden">
    </form>
</body>
</html>