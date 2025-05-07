
const TESTMODUS = new URLSearchParams(window.location.search).get("test") === "1";

// ------------------------------------------

const locaties = [
    { naam: "Station", lat: 53.2106, lon: 6.5666, opdracht: "ga naar de lantaarnpaal binnen en maak een kring. Maak hier vervolgens een foto van." },
    { naam: "IKEA", lat: 53.2400, lon: 6.6006, opdracht: "Maak een foto van duurste kussen" },
    { naam: "Prinsentuin", lat: 53.2211, lon: 6.5735, opdracht: "Maak een foto van een symmetrisch patroon in de tuin." },
    { naam: "UMCG", lat: 53.2196, lon: 6.5744, opdracht: "Doe de YMCA maar dan de UMCG. Maak hier een video van." },
    { naam: "Martinitoren", lat: 53.2194, lon: 6.5683, opdracht: "Maak een foto alsof je de toren vast hebt, net als ze bij de toren van Pisa doen" },
    { naam: "Groninger Forum", lat: 53.2180, lon: 6.5681, opdracht: "Maak een groepsfoto met de Martinitoren op de achtergrond vanaf het dakterras" }
];

const route = [locaties[0], ...shuffle(locaties.slice(1, -1)), locaties[locaties.length - 1]];
let huidigeIndex = 0;

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function isDichtbij(lat1, lon1, lat2, lon2, maxAfstand = 100) {
    const R = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) ** 2 +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c < maxAfstand;
}

function toonLocatieInfo() {
    const locatie = route[huidigeIndex];

    document.getElementById("locatiecode").innerText = `Locatie: ${locatie.naam}`;
    document.getElementById("opdracht").innerText = "Wachten op juiste locatie...";

    if (TESTMODUS) {
        document.getElementById("opdracht").innerText = locatie.opdracht + " (testmodus)";
        document.getElementById("upload-btn").style.display = "inline-block";
        return;
    }

    navigator.geolocation.getCurrentPosition(pos => {
        const userLat = pos.coords.latitude;
        const userLon = pos.coords.longitude;

        if (isDichtbij(userLat, userLon, locatie.lat, locatie.lon)) {
            document.getElementById("opdracht").innerText = locatie.opdracht;
            document.getElementById("upload-btn").style.display = "inline-block";
        } else {
            document.getElementById("opdracht").innerText = "Je bent nog niet op de juiste locatie.";
            document.getElementById("upload-btn").style.display = "none";
        }
    }, () => {
        document.getElementById("opdracht").innerText = "Locatie kon niet worden bepaald.";
    });
}

function volgende() {
    huidigeIndex++;
    if (huidigeIndex >= route.length) {
        document.getElementById("opdracht").innerText = "Route voltooid!";
        document.getElementById("locatiecode").innerText = "";
        document.getElementById("upload-btn").style.display = "none";
    } else {
        toonLocatieInfo();
    }
}

window.onload = () => {
    if (new URLSearchParams(window.location.search).get("voltooid") === "1") {
        volgende();
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    toonLocatieInfo();
    setInterval(toonLocatieInfo, 5000);
};
