<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ../login/signin/index.html");
    exit;
}

$servername = "localhost"; 
$username = "root";  
$password = "";  
$dbname = "sslwenow";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carte_id = $_POST['id'];

    // Récupérer la disponibilité actuelle
    $sql = "SELECT disponible FROM cartes WHERE id='$carte_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nouvelle_disponibilite = $row['disponible'] ? 0 : 1; // Inverser la disponibilité

        // Mettre à jour la disponibilité
        $sql_update = "UPDATE cartes SET disponible='$nouvelle_disponibilite' WHERE id='$carte_id'";
        $conn->query($sql_update);
    }
}

$conn->close();
?>
