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

$pseudo = $_SESSION['pseudo'];

// Récupérer l'ID de l'utilisateur
$sql_user_id = "SELECT id FROM users WHERE pseudo='$pseudo'";
$result_user_id = $conn->query($sql_user_id);
if ($result_user_id->num_rows > 0) {
    $row = $result_user_id->fetch_assoc();
    $user_id = $row['id'];
} else {
    echo "Erreur utilisateur non trouvé.";
    exit;
}

// Insérer la nouvelle carte
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_carte = $_POST['nom_carte'];
    $numero_carte = $_POST['numero_carte'];
    $cvc = $_POST['cvc'];
    $date_expiration = $_POST['date_expiration'];
    $disponible = $_POST['disponible'];
    $somme = $_POST['somme'];

    $sql_insert_carte = "INSERT INTO cartes (user_id, nom_carte, numero_carte, cvc, date_expiration, disponible, somme) 
                         VALUES ('$user_id', '$nom_carte', '$numero_carte', '$cvc', '$date_expiration', '$disponible', '$somme')";

    if ($conn->query($sql_insert_carte) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Erreur: " . $sql_insert_carte . "<br>" . $conn->error;
    }
}

$conn->close();
?>
