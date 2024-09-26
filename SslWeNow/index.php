<?php
session_start();

// Si l'utilisateur est déjà connecté, on le redirige directement vers le tableau de bord
if (isset($_SESSION['pseudo'])) {
    header("Location: login/dashboard.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost"; 
$username = "root";  
$password = "";  
$dbname = "sslwenow";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $password = md5($_POST['password']);  // MD5 hash du mot de passe

    // Requête pour vérifier les identifiants
    $sql = "SELECT pseudo FROM users WHERE identifiant='$identifiant' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si la connexion est réussie
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['pseudo'] = $row['pseudo'];
        $_SESSION['login_time'] = date("H:i:s");
        header("Location: login/dashboard.php");
        exit;
    } else {
        // Message d'erreur si les identifiants sont incorrects
        $error_message = "Identifiant ou mot de passe incorrect.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login wrap">
        <div class="h1">Login</div>
        <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
        <form action="index.php" method="POST">
            <input placeholder="Identifiant" id="identifiant" name="identifiant" type="text" required>
            <input placeholder="Password" id="password" name="password" type="password" required>
            <input value="Login" class="btn" type="submit">
        </form>
    </div>
</body>
</html>
