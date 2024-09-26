<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    echo json_encode(['error' => 'Non connecté']);
    exit;
}

// Connexion à la base de données
$servername = "localhost"; 
$username = "root";  
$passwordDb = "";  
$dbname = "sslwenow";

$conn = new mysqli($servername, $username, $passwordDb, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pseudo = $_SESSION['pseudo'];
$sql = "SELECT COUNT(*) AS nb_cartes FROM cartes WHERE pseudo = '$pseudo' AND disponible = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nb_cartes = $row['nb_cartes'];
    echo json_encode(['nb_cartes' => $nb_cartes]);
} else {
    echo json_encode(['nb_cartes' => 0]);
}

$conn->close();
?>
