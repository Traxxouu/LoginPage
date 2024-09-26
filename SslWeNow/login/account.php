<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: signin/index.html");
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
$sql = "SELECT pseudo, age, genre, pays FROM users WHERE pseudo='$pseudo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Erreur lors de la récupération des données.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="account-info">
        <h1>Informations de votre compte</h1>
        <p><strong>Pseudo:</strong> <?php echo $row['pseudo']; ?></p>
        <p><strong>Âge:</strong> <?php echo $row['age']; ?></p>
        <p><strong>Genre:</strong> <?php echo $row['genre']; ?></p>
        <p><strong>Pays:</strong> <?php echo $row['pays']; ?></p>

        <a href="logout.php" class="btn">Déconnexion</a>
    </div>
</body>
</html>
