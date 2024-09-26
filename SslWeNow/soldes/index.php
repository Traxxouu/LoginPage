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

// Récupération du solde cumulatif des cartes disponibles
$sql_solde = "SELECT SUM(somme) AS solde_total FROM cartes WHERE user_id=(SELECT id FROM users WHERE pseudo='$pseudo') AND disponible=1";
$result_solde = $conn->query($sql_solde);

if ($result_solde->num_rows > 0) {
    $row_solde = $result_solde->fetch_assoc();
    $solde = $row_solde['solde_total'] ?? 0;
} else {
    $solde = 0;
}

// Récupération des cartes bancaires de l'utilisateur
$sql_cartes = "SELECT id, nom_carte, numero_carte, cvc, date_expiration, disponible, somme FROM cartes WHERE user_id=(SELECT id FROM users WHERE pseudo='$pseudo')";
$result_cartes = $conn->query($sql_cartes);

$cartes = [];
if ($result_cartes->num_rows > 0) {
    while ($row = $result_cartes->fetch_assoc()) {
        $cartes[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soldes</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Bouton Retour (Rouge) -->
    <button class="shadow__btn retour" onclick="history.back()">
        Retour
    </button>

    <!-- Bouton Ajouter une carte (Bleu) -->
    <button class="shadow__btn ajouter-carte" onclick="document.getElementById('add-card-form').style.display='block'">
        Ajouter une carte
    </button>

    <!-- Affichage du solde -->
    <div class="solde-container">
        <h2>Bienvenue <?php echo $_SESSION['pseudo']; ?></h2>
        <p>Votre solde cumulé des cartes disponibles : <strong><?php echo number_format($solde, 2); ?> €</strong></p>
        
        <!-- Section des cartes bancaires -->
        <h3>Vos cartes</h3>
        <div class="cards-container">
            <?php foreach ($cartes as $carte) : ?>
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <p class="heading_8264"><?php echo $carte['nom_carte']; ?></p>
                        <svg class="logo" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 48 48">
                            <path fill="#e0e0e0" d="M32 10A14 14 0 1 0 32 38A14 14 0 1 0 32 10Z"></path>
                            <path fill="#c0c0c0" d="M16 10A14 14 0 1 0 16 38A14 14 0 1 0 16 10Z"></path>
                            <path fill="#d0d0d0" d="M18,24c0,4.755,2.376,8.95,6,11.48c3.624-2.53,6-6.725,6-11.48s-2.376-8.95-6-11.48 C20.376,15.05,18,19.245,18,24z"></path>
                        </svg>
                        <p class="name"><?php echo $pseudo; ?></p>
                    </div>
                    <div class="flip-card-back">
                        <div class="strip"></div>
                        <!-- Affichage complet ou partiel du numéro de la carte selon la disponibilité -->
                        <?php if ($carte['disponible']): ?>
                            <p class="number"><?php echo $carte['numero_carte']; ?></p> <!-- Numéro complet -->
                        <?php else: ?>
                            <p class="number">Carte Indisponible</p>
                        <?php endif; ?>
                        <p class="date_8264">Exp: <?php echo $carte['date_expiration']; ?></p>
                        <p class="cvc_number">CVC: <?php echo $carte['cvc']; ?></p>
                        <p class="status <?php echo $carte['disponible'] ? 'available' : 'unavailable'; ?>">
                            <?php echo $carte['disponible'] ? 'Disponible' : 'Non disponible'; ?>
                        </p>
                        <p>Solde de la carte : <?php echo number_format($carte['somme'], 2); ?> €</p>

                        <!-- Bouton ON/OFF pour changer la disponibilité -->
                        <label class="switch">
                            <input type="checkbox" onchange="toggleDisponibilite(<?php echo $carte['id']; ?>)" <?php echo $carte['disponible'] ? 'checked' : ''; ?>>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Formulaire d'ajout de carte -->
        <div id="add-card-form">
            <form action="ajouter_carte.php" method="POST">
                <input type="text" name="nom_carte" placeholder="Nom sur la carte" required>
                <input type="text" name="numero_carte" placeholder="Numéro de la carte" required>
                <input type="text" name="cvc" placeholder="CVC" required>
                <input type="text" name="date_expiration" placeholder="Date d'expiration (MM/AA)" required>
                <input type="number" name="somme" placeholder="Somme" required>
                <label for="disponible">Disponible :</label>
                <select name="disponible" id="disponible">
                    <option value="1">Disponible</option>
                    <option value="0">Non disponible</option>
                </select>
                <button type="submit">Ajouter la carte</button>
            </form>
        </div>

    </div>

    <script>
        // Fonction pour basculer la disponibilité d'une carte
        function toggleDisponibilite(id) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "modifier_disponibilite.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status == 200) {
                    location.reload(); // Recharge la page après modification
                }
            };
            xhr.send("id=" + id);
        }
    </script>

</body>
</html>
