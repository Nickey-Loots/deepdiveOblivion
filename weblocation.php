<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Geolocation Example</title>
</head>
<body>
  <h1>Get User Location</h1>
  <button onclick="getLocation()">Get Location</button>
  <p id="location"></p>

  <script>
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      } else {
        document.getElementById("location").innerText = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      document.getElementById("location").innerText =
        `Latitude: ${lat}, Longitude: ${lon}`;
    }

    function showError(error) {
      let message = "";
      switch(error.code) {
        case error.PERMISSION_DENIED:
          message = "User denied the request for Geolocation.";
          break;
        case error.POSITION_UNAVAILABLE:
          message = "Location information is unavailable.";
          break;
        case error.TIMEOUT:
          message = "The request to get user location timed out.";
          break;
        case error.UNKNOWN_ERROR:
          message = "An unknown error occurred.";
          break;
      }
      document.getElementById("location").innerText = message;
    }
  </script>
</body>
</html>