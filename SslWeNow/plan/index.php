<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: signin/index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Scam - CHAT • <?php echo $_SESSION['pseudo']; ?></title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background-color: #000000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .telegram-container {
            background-color: #2A2F33;
            width: 90%;
            max-width: 700px;
            height: 80vh;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }

        .telegram-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #3A3F45;
        }

        .header-title {
            font-size: 1.5em;
            color: #00A4E4;
            font-weight: bold;
        }

        .header-status {
            font-size: 0.9em;
            color: #7C8999;
        }

        .message-container {
            margin-top: 20px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .message {
            display: flex; /* change display from 'none' to 'flex' */
            margin-bottom: 15px;
            opacity: 0; /* start invisible */
            transition: opacity 0.3s ease-in-out; /* smooth transition for appearing */
        }

        .message.show {
            opacity: 1; /* make visible when class 'show' is added */
        }


        .message p {
            background-color: #3A3F45;
            border-radius: 10px;
            padding: 10px 15px;
            max-width: 70%;
        }

        .message.bot p {
            background-color: #363636;
            color: #FFD700;
        }

        .message.user p {
            background-color: #2D88FF;
            color: #fff;
            margin-left: auto;
        }

        .message .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #50555A;
            margin-right: 10px;
        }

        .input-container {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-top: 1px solid #3A3F45;
        }

        .input-container input {
            width: 100%;
            padding: 10px;
            border-radius: 20px;
            border: none;
            background-color: #363636;
            color: #fff;
            font-size: 1em;
        }

        .input-container button {
            margin-left: 10px;
            background-color: #2D88FF;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 1em;
            cursor: pointer;
        }

        .input-container button:hover {
            background-color: #1c6cd3;
        }
    </style>
</head>
<body>
    <button class="shadow__btn" onclick="history.back()">
        Retour
    </button>

    <div class="telegram-container">
        <!-- Header -->
        <div class="telegram-header">
            <div class="header-title">Scam - Connect</div>
            <div class="header-status">En ligne</div>
        </div>

        <!-- Messages -->
        <div class="message-container" id="messages">
            <!-- Les messages seront générés dynamiquement ici -->
        </div>

        <!-- Input -->
        <div class="input-container">
            <input type="text" id="chatInput" placeholder="Écrivez un message...">
            <button id="sendBtn">➤</button>
        </div>
    </div>

    <script>
        const messageContainer = document.getElementById('messages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');

        function appendMessage(content, isBot = false) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', isBot ? 'bot' : 'user');
            messageDiv.innerHTML = `<p>${content}</p>`;
            messageContainer.appendChild(messageDiv);
            
            // Ajoute un délai pour la transition d'affichage
            setTimeout(() => {
                messageDiv.classList.add('show');  // Affiche le message
                messageContainer.scrollTop = messageContainer.scrollHeight;  // Scroll automatiquement vers le bas
            }, 10);
        }

        function handleCommand(command) {
            if (command === '/carte') {
                // Requête AJAX pour obtenir les informations des cartes
                fetch('get_cards.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur réseau');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);  // Ajoute cette ligne pour déboguer
                        if (data.nb_cartes !== undefined) {
                            appendMessage(`Vous avez ${data.nb_cartes} carte(s) disponible(s).`, true);
                        } else if (data.error) {
                            appendMessage(data.error, true);
                        } else {
                            appendMessage('Erreur lors de la récupération des cartes.', true);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la requête:', error);
                        appendMessage('Erreur lors de la requête.', true);
                    });
            } else {
                appendMessage(`Commande "${command}" non reconnue.`, true);
            }
        }

        // Gestion du clic sur le bouton d'envoi
        sendBtn.addEventListener('click', () => {
            const message = chatInput.value.trim();
            if (message) {
                appendMessage(message);  // Affiche le message de l'utilisateur
                if (message.startsWith('/')) {
                    handleCommand(message);  // Si c'est une commande, on la traite
                }
                chatInput.value = '';  // Réinitialise le champ
            }
        });

        // Gestion de la touche "Entrée" pour envoyer le message
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                sendBtn.click();
            }
        });
    </script>
</body>
</html>
