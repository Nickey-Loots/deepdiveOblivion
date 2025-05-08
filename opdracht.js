const locaties = [
    { naam: "Station", lat: 53.21124356149085, lon: 6.564106259019599, opdracht: "ga naar de lantaarnpaal binnen en maak een kring. Maak hier vervolgens een foto van." },
    { naam: "IKEA", lat: 53.21743554020972, lon: 6.587921863354231, opdracht: "Maak een foto van duurste kussen" },
    { naam: "Prinsentuin", lat: 53.22158492618018, lon: 6.5691379420181875, opdracht: "Maak een foto van een symmetrisch patroon in de tuin." },
    { naam: "UMCG", lat: 53.22108957132685, lon: 6.577004649026435, opdracht: "Doe de YMCA maar dan de UMCG. Maak hier een video van." },
    { naam: "Martinitoren", lat: 53.21929986992094, lon: 6.5678987592666696, opdracht: "Maak een foto alsof je de toren vast hebt, net als ze bij de toren van Pisa doen" },
    { naam: "Groninger Forum", lat: 53.21873995007526, lon: 6.5703098768129875, opdracht: "Maak een groepsfoto met de Martinitoren op de achtergrond vanaf het dakterras" }
];

const route = [locaties[0], ...shuffle(locaties.slice(1, -1)), locaties[locaties.length - 1]];

let opgeslagenIndex = parseInt(sessionStorage.getItem("huidigeIndex"), 10);
let huidigeIndex = Number.isInteger(opgeslagenIndex) && opgeslagenIndex >= 0 ? opgeslagenIndex : 0;

if (typeof window !== "undefined") {
    window.randomRoute = route;
}

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

let opLocatie = false;

function toonLocatieInfo() {
    const locatie = route[huidigeIndex];
    document.getElementById("locatiecode").innerText = `Locatie: ${locatie.naam}`;

    navigator.geolocation.getCurrentPosition(pos => {
        const userLat = pos.coords.latitude;
        const userLon = pos.coords.longitude;

        const dichtbij = isDichtbij(userLat, userLon, locatie.lat, locatie.lon);

        if (dichtbij && !opLocatie) {
            opLocatie = true;
            document.getElementById("opdracht").innerText = locatie.opdracht;
            document.getElementById("upload-btn").classList.remove("hidden");
        } else if (!dichtbij && opLocatie) {
            opLocatie = false;
            document.getElementById("opdracht").innerText = "Je bent nog niet op de juiste locatie.";
            document.getElementById("upload-btn").classList.add("hidden");
        } else if (!dichtbij && !opLocatie) {
            document.getElementById("opdracht").innerText = "Je bent nog niet op de juiste locatie.";
            document.getElementById("upload-btn").classList.add("hidden");
        }
    }, () => {
        document.getElementById("opdracht").innerText = "Locatie kon niet worden bepaald.";
        document.getElementById("upload-btn").classList.add("hidden");
        opLocatie = false;
    });
}

function volgende() {
    huidigeIndex++;
    sessionStorage.setItem("huidigeIndex", huidigeIndex);

    if (huidigeIndex >= route.length) {
        document.getElementById("opdracht").innerText = "Route voltooid!";
        document.getElementById("locatiecode").innerText = "";
        document.getElementById("upload-btn").style.display = "none";
    } else {
        toonLocatieInfo();
    }
}

window.onload = () => {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get("reset") === "1") {
        sessionStorage.setItem("huidigeIndex", "0");
        huidigeIndex = 0;
    }

    toonLocatieInfo();

    if (urlParams.get("voltooid") === "1") {
        volgende();
        history.replaceState(null, "", "index.php");
    }

    document.getElementById("upload-btn").classList.add("hidden");

    setInterval(toonLocatieInfo, 60000);
};
