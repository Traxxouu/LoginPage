<?php
// Connexion à la BDD
$servername = "localhost"; 
$username = "root";  
$password = "";  
$dbname = "sslwenow";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$identifiant = $_POST['identifiant'];
$password = md5($_POST['password']);  // MD5 hash for password

$sql = "SELECT pseudo FROM users WHERE identifiant='$identifiant' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['pseudo'] = $row['pseudo'];
    $_SESSION['login_time'] = date("H:i:s");
    header("Location: ../dashboard.php");
} else {
    echo "Identifiant ou mot de passe incorrect";
}
$conn->close();
?>
