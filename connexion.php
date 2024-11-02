<?php
session_start(); // Démarrer la session
require_once('db1.php'); // Utiliser la même connexion que User_profil.php

if (isset($_POST['email'], $_POST['password'])) {
    // Préparer la requête pour récupérer le mot de passe haché
    $stmt = $connectionbd->prepare('SELECT ID_UTILISATEUR, MOTS_DE_PASSE FROM utilisateur WHERE email = :email');
    $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Hacher le mot de passe saisi en utilisant SHA-256
        $hashedPasswordInput = hash('sha256', $_POST['password']);
        
        // Comparer le mot de passe haché saisi avec celui dans la base de données
        if ($hashedPasswordInput === $user['MOTS_DE_PASSE']) {
            // Enregistrer l'ID de l'utilisateur dans la session
            $_SESSION['idUtilisateur'] = $user['ID_UTILISATEUR'];
            // Rediriger vers la page de profil utilisateur
            header('Location: User_profil.php');
            exit();
        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Email non trouvé dans la base de données";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <title>FitTrack</title>
    <style>
        label {
            display: block; /* Ensures each label takes a full line */
            margin-bottom: 10px; /* Adds space below each label */
            font-family: Poppins; /* Consistent font style */
            font-size: 14px; /* Adjust font size if needed */
        }

        input[type="email"], input[type="password"] {
            margin-bottom: 20px; /* Adds space below each input field */
            padding: 8px; /* Adds padding inside input fields */
            width: 100%; /* Make input fields take full width */
            box-sizing: border-box; /* Ensures padding is included in width */
        }

        .login-formNo {
            max-width: 400px; /* Limits the width of the form */
            margin: auto; /* Centers the form */
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            float: left;
        }

        ul li {
            float: left;
            list-style: none;
            margin-left: 60px;
            margin-top: 27px;
            font-size: 15px;
            font-family: Poppins;
        }

        li a {
            display: block;
            color: #fff;
            text-align: center;
            padding: 16px;
            margin-right: 30px;
            text-decoration: none;
            font-size: 15px; 
            display: inline-block;
        }

        li a:hover {
            background-color: transparent;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div id="id1" class="accueil"> 
        <img class="cl1" src="img/LOGO.png" alt="Logo" height="40px"><h2><b>FitTrack</b></h2>
        <div class="menu">
            <ul>
                <li><a href="home.html"><b>Accueil</b></a></li>
                <li><a href="inscription.php"><b>S'inscrire</b></a></li>
            </ul>
        </div>
        
        <main>
            <section class="desc">
                <div class="heroNo">
                    <h3>Connectez-vous</h3>
                    <h4>Connaître son IMC</h4>
                    <p class="lole"> Découvrez FitTrack, notre application innovante <br>
                        qui calcule votre IMC, suit votre santé et vous
                        aide <br>à atteindre vos objectifs de bien-être en <br>
                        toute simplicité 
                    </p>
                </div>
            </section>
            <section class="login-formNo">
                <div>
                    <form method="post" action="connexion.php">
                        <label for="email">Email</label>
                        <input type="email" name="email" required>
                        <label for="password">Entrez votre Mot de passe</label>
                        <input type="password" name="password" required>
                        <button type="submit" class="bntNavbars">Se Connecter</button>
                        <button type="button" class="bntNavbar" onclick="window.location.href='home.html';">Annuler</button>
                    </form>
                    <div class="btn">
                        <div class="forget-pass">
                            <a href="Mot_de_passe_oublie.html">Mot de passe oublié ?</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
