navigator.geolocation.getCurrentPosition(success, error);

function success(position) {
    const lat = position.coords.latitude;
    const lon = position.coords.longitude;

    let opdracht = 'Geen opdracht gevonden op deze locatie';
    if (lat > 53 && lon < 6.6) {
        opdracht = 'Maak foto van duurste kussen';
    }

    document.getElementById('opdracht').innerHTML = opdracht;
}

function error() {
    document.getElementById('opdracht').innerHTML = 'Locatie kon niet worden opgehaald';
}