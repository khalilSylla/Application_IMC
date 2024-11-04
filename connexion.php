<?php
session_start();
require_once('db1.php'); // Connexion à la base de données

$error = ''; // Initialiser la variable pour le message d'erreur

if (isset($_POST['email'], $_POST['password'])) {
    // Préparer la requête pour récupérer le mot de passe haché
    $stmt = $connectionbd->prepare('SELECT ID_UTILISATEUR, MOT_DE_PASSE FROM utilisateur WHERE email = :email');
    $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Hacher le mot de passe saisi en utilisant SHA-256
        $hashedPasswordInput = hash('sha256', $_POST['password']);
        
        // Comparer le mot de passe haché saisi avec celui dans la base de données
        if ($hashedPasswordInput === $user['MOT_DE_PASSE']) {
            // Enregistrer l'ID de l'utilisateur dans la session
            $_SESSION['id_utilisateur'] = $user['ID_UTILISATEUR'];
            // Rediriger vers la page de profil utilisateur
            header('Location:User_profil.php');
            exit();
        } else {
            $error = "Mot de passe incorrect";
        }
    } else {
        $error = "Email non trouvé dans la base de données";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <title>FitTrack</title>
    <style>
        label {
            display: block;
            margin-bottom: 10px;
            font-family: Poppins;
            font-size: 14px;
        }

        input[type="email"], input[type="password"] {
            margin-bottom: 20px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .login-form {
            max-width: 400px;
            margin: auto;
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

        .error-message {
            color: red;
            font-size: 13px;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div id="id1" class="accueil"> 
    <img class="cl1" src="img/battement-de-coeur (3).png" alt="Logo" height="45px"><h2><b>FitTrack</b></h2>
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
                        <?php if ($error): ?>
                            <p class="error-message"><?= $error; ?></p>
                        <?php endif; ?>
                        <button type="submit" class="bntNavbars">Se Connecter</button>
                        <button type="button" class="bntNavbar" onclick="window.location.href='home.html';">Annuler</button>
                    </form>
                    <div class="btn">
                        <div class="forget-pass">
                            <a href="Mot_de_passe_oublie.php">Mot de passe oublié ?</a>
                        </div>
                    </div>
                    
                </section>
            
            </main>
            <div class="cl12"><img class="cl10" src="img/appel-telephonique (1).png" height="30px" alt="tel">123-456-789 </div>
            <div class="cl13"><img class="cl11" src="img/email (1).png" height="30px" alt="adresse">info.fiitrack@gmail.com</div>
        </body>
        </html>
   
