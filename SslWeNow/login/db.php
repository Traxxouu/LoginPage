<?php
// Connexion à la base de données MySQL

$servername = "localhost";  // L'hôte (en local, c'est localhost)
$username = "root";         // Nom d'utilisateur (par défaut, c'est root)
$password = "";             // Mot de passe (laisser vide si tu n'as pas défini de mot de passe MySQL)
$dbname = "sslwenow";       // Nom de la base de données (sslwenow)

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
