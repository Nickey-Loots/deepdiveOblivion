<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Noorderpoort Bingo</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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

    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-xl shadow space-y-4 border border-yellow-300">
        <h1 class="text-2xl font-bold text-black">Opdracht op jouw locatie</h1>
        <p class="text-gray-700">Zodra je locatie is bepaald verschijnt hier jouw opdracht</p>

        <div id="opdracht-container" class="mt-4 p-4 bg-yellow-100 border-2 border-yellow-300 rounded">
            <div id="locatie" class="font-bold text-black text-xl mb-2"></div>
            <p id="locatiecode" class="text-lg text-black font-semibold mt-2">Locatie wordt geladen...</p>
            <p id="opdracht" class="text-lg font-semibold text-black">Laden...</p>
        </div>
        <a id="upload-btn" href="./upload.php"
            class="text-white bg-yellow-300 hover:bg-yellow-400 focus:outline-none font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2">Naar
            inleverformulier</a>
    </div>

    

    <script src="opdracht.js"></script>

    <?php require_once 'kaart.php' ?>
</body>

</html>