<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: signin/index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<body>
    <header class="header">
        <h1>SoWeNow <span class="username" style="color: blue;"><?php echo $_SESSION['pseudo']; ?></span></h1>
        <p>Bonjour, vous vous êtes connecté à <span class="hour" style="color: blue;"><?php echo $_SESSION['login_time']; ?></span></p>
    </header>
    <?php include 'navbar.php'; ?>

        <p id="welcome-message"></p>


       <!-- Section pour la carte -->
    <div id="map" style="height: 400px; width: 100%;"></div>

<script>
    // Fonction pour obtenir l'heure actuelle
    function getFormattedTime() {
        const date = new Date();
        return date.toLocaleTimeString();
    }

    // Fonction pour initialiser la carte Leaflet
    function initMap(lat, lng) {
    // Initialisation de la carte
    var map = L.map('map').setView([lat, lng], 13); // zoom sur la position de l'utilisateur

    // Chargement des tuiles CartoDB Dark Matter (mode sombre)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors, © CartoDB',
        maxZoom: 19
    }).addTo(map);

    // Marqueur sur la position
    L.marker([lat, lng]).addTo(map)
        .bindPopup('Vous êtes ici.')
        .openPopup();
}


    // Fonction pour obtenir la localisation de l'utilisateur
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Affiche l'heure et la position de connexion
                document.getElementById('welcome-message').textContent = "Vous vous êtes connecté à " + getFormattedTime() + " à cette position : Lat " + lat + ", Lng " + lng;

                // Initialiser la carte
                initMap(lat, lng);
            }, function() {
                alert('Erreur lors de la récupération de la position');
            });
        } else {
            alert("La géolocalisation n'est pas supportée par ce navigateur.");
        }
    }

    // Lancer la fonction de géolocalisation quand la page est chargée
    window.onload = getLocation;
</script>
</body>
</html>
