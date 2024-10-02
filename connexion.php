<!-- <?php
require_once('db.php');

if (isset($_POST['email'], $_POST['password'])) {
    $stmt = $db->prepare('SELECT MOT_DE_PASSE FROM utilisateur WHERE email= ?');
    $stmt->execute([$_POST['email']]);
    $hashedPassword = $stmt->fetchColumn();
    if ($hashedPassword) {
        if (password_verify($_POST['password'], $hashedPassword)) {
            echo "Connexion réussie";
        } else {
            echo "Mot de passe incorrect";
        }
    } else {
        echo "Email non trouvé dans la base de données";
    }
}
?> -->


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
<title>FitTrack</title>
</head>
<body>
    <div  id="id1" class="accueil"> 
            <img class="cl1" src="img/LOGO.png" alt="Logo" height="40px"><h2><b>FitTrack</b></h2>
            <div class="menu">
                <ul>
                    <li><a href="home.html"><b>Accueil</b></a></li>
                    <li><a href="connexion.html"><b>Se connecter</b></a></li>
                     
                      <li><a href="home.html">Se Déconnecter</a></li>
                </ul>
            </div>
            <style>
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
                    padding: 16x;
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
               <main>
                <section class="desc">
                    <div class="heroNo">
                        <h3>Connecter Vous</h3>
                        <h4>Connaitre son IMC</h4>
                        <p class="lole"> Découvrez FitTrack , notre application innovante
                            qui calcule votre IMC , suit votre sante et vous
                            aide à atteindre vos objectifs de bien-être en
                            toute simplicité 
                            S'inscrire
                            Mot de passe oublier
                            FitTrack
                        </p>
                    </div>
                </section>
                <section class="login-formNo">
                    <div>
                        <form method="post" action="connexion.php">
                            <label for="email">Email</label><br>
                            <input type="email" name="email" required>
                            <label for="password">Entre votre Mot de passe</label><br>
                            <input type="password" name="password" required>
                            <button type="submit" class="bntNavbars">Se Connecter</button>
                            <button type="button" class="bntNavbar" onclick="window.location.href='home.html';">Annuler</button>
                        </form>
                        <div class="btn">
                       <div class="forget-pass">
                            <a href="Mot_de_passe_oublie.html"> Mot de passe oublier ?</a>
                        </div>
                    </div>
                </section>
            
            </main>
        </body>
        </html>
   
