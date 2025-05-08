<?php
ob_start();

$uploadMap = 'uploads/';
if (!is_dir($uploadMap)) {
    mkdir($uploadMap, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $bestand = $_FILES['foto'];
    $bestandsNaam = basename($bestand['name']);
    $doelPad = $uploadMap . time() . '-' . $bestandsNaam;

    if (move_uploaded_file($bestand['tmp_name'], $doelPad)) {
        header("Location: index.php?voltooid=1");
        exit;
    } else {
        echo "Er is iets misgegaan bij het uploaden.";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Foto inleverformulier met GPS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script>
        window.onload = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (pos) {
                    document.getElementById('latitude').value = pos.coords.latitude;
                    document.getElementById('longitude').value = pos.coords.longitude;
                }, function (error) {
                    console.warn("Locatie niet beschikbaar: ", error.message);
                });
            } else {
                alert("Je browser ondersteunt geen geolocatie.");
            }
        };
    </script>
    <style>
        body {
            background-color: #ffffff;
            color: #000000;
        }
    </style>
</head>
<body>

<nav class="bg-black">
    <div class="max-w-7xl mx-auto px-4 py-4 text-center">
        <div class="text-xl font-bold text-yellow-300">Noorderpoort Stadsbingo</div>
    </div>
</nav>

<div class="w-full px-4 py-6 bg-white border border-yellow-400 rounded-lg shadow space-y-6">
    <h1 class="text-2xl font-bold text-black">Lever je opdrachtfoto in</h1>

    <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="naam" class="block font-medium text-black">Je naam:</label>
            <input type="text" name="naam" id="naam" required
                class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
        </div>

        <div>
            <label for="opdracht" class="block font-medium text-black">Opdracht:</label>
            <input type="text" name="opdracht" id="opdracht" required
                class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-yellow-400">
        </div>

        <div>
            <label for="foto" class="block font-medium text-black">Upload je foto:</label>
            <input type="file" name="foto" id="foto" accept="image/*" required
                class="mt-1 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-yellow-400 file:text-black hover:file:bg-yellow-500">
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <button type="submit"
            class="bg-yellow-400 text-black font-semibold py-2 px-4 rounded hover:bg-yellow-500 transition cursor-pointer">
            Verzenden
        </button>
    </form>
</div>

</body>
</html>
