<?php
$uploadDir = 'uploads/';
$maxFileSize = 16 * 1024 * 1024; // 16 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = htmlspecialchars($_POST['naam']);
    $opdracht = htmlspecialchars($_POST['opdracht']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['foto']['tmp_name'];
        $fileName = basename($_FILES['foto']['name']);
        $fileSize = $_FILES['foto']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileExt, $allowedExt)) {
            echo "❌ Ongeldig bestandstype.";
            die("</p><br><a href='upload.php'>Probeer het opnieuw</a>");
        }

        if ($fileSize > $maxFileSize) {
            echo "❌ Bestand is te groot (max. 5 MB).";
            die("</p><br><a href='upload.php'>Probeer het opnieuw</a>");
        }

        $safeFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '_', $fileName);
        $destination = $uploadDir . $safeFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($fileTmp, $destination)) {
            echo "<h2>✅ Upload succesvol!</h2>";
            echo "<p><strong>Naam:</strong> " . htmlentities($naam) . "</p>";
            echo "<p><strong>Opdracht:</strong> " . htmlentities($opdracht) . "</p>";
            echo "<p><strong>Foto:</strong><br><img src='$destination' width='300'></p>";
            echo "<p><strong>Locatie:</strong> ";
            if ($latitude && $longitude) {
                echo "Lat: $latitude, Lng: $longitude<br>";
                echo "<a href='https://www.google.com/maps?q=$latitude,$longitude' target='_blank'>📍 Bekijk op Google Maps</a>";
            } else {
                echo "Niet beschikbaar";
            }
            echo "</p><br><a href='upload.php'>Nog een upload doen</a>";
            exit;
        }
    }
}
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
        <input type="file" name="foto" id="foto" accept="image/*" required><br><br>

        <!-- Verborgen velden voor GPS -->
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <input type="submit" value="Verzenden">
    </form>
</body>
</html>