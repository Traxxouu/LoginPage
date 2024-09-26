<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ../login/signin/index.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = $_POST['pseudo'];
    $age = $_POST['age'];
    $genre = $_POST['genre'];
    $pays = $_POST['pays'];
    $identifiant = rand(1000000, 9999999);
    $password = substr(md5(rand()), 0, 8);

    // Insertion dans la base de données
    $servername = "localhost"; 
    $username = "root";  
    $passwordDb = "";  
    $dbname = "sslwenow";
    
    $conn = new mysqli($servername, $username, $passwordDb, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $hashedPassword = md5($password);  // Password hash
    $sql = "INSERT INTO users (pseudo, identifiant, password, age, genre, pays) 
            VALUES ('$pseudo', '$identifiant', '$hashedPassword', '$age', '$genre', '$pays')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Compte créé : Identifiant : $identifiant, Password : $password');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #1e1e1e;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px; /* Réduit la largeur maximale pour éviter le dépassement */
            margin: 20px auto;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
            font-size: 22px; /* Ajuste la taille de la police pour s'adapter à la box */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input, select, button {
            padding: 12px; /* Ajustement du padding pour mieux intégrer les champs */
            border: none;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box; /* Permet de respecter les marges intérieures */
        }

        input, select {
            background-color: #3c3c3c;
            color: #fff;
        }

        input::placeholder {
            color: #bbb;
        }

        button {
            background-color: #5b42f3;
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00ddeb;
        }

        .shadow__btn {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(255, 0, 0, 0.5);
            position: absolute;
            top: 20px;
            left: 20px;
            transition: 0.3s ease;
        }

        .shadow__btn:hover {
            box-shadow: 0px 4px 15px rgba(255, 0, 0, 0.7);
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    <button class="shadow__btn" onclick="history.back()">
        Retour
    </button>

    <div class="container">
        <h1>Ajouter un nouvel utilisateur</h1>
        <form action="index.php" method="POST">
            <input type="text" name="pseudo" placeholder="Pseudo" required>
            <input type="number" name="age" placeholder="Âge" required>
            <select name="genre" required>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
                <option value="Autre">Autre</option>
            </select>
            <select name="pays" required>
                <option value="France">France</option>
                <option value="Belgique">Belgique</option>
                <option value="Suisse">Suisse</option>
                <option value="UK">UK</option>
            </select>
            <button type="submit">Générer</button>
        </form>
    </div>
</body>
</html>
