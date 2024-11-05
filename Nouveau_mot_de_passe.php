<?php
require_once "db.php"; 
$error = ''; 
$success = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Code = $_POST['code'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password']; 

    
    $stmt = $PDO->prepare("SELECT * FROM utilisateur WHERE code_reinitialisation = :code");
    $stmt->execute(['code' => $Code]);
    $user = $stmt->fetch();

    if ($user) {
        
        if ($newPassword === $confirmPassword) {
            $user_id = $user['ID_UTILISATEUR'];
            $user_id=1;$drapeau_reinitialisation=0;$code_reinitialisation=0;
            $hashedPassword = hash('sha256',$newPassword );
            $stmt = $PDO->prepare("UPDATE utilisateur SET MOT_DE_PASSE = :password, code_reinitialisation= :code_reinitialisation, drapeau_reinitialisation= :drapeau_reinitialisation  WHERE ID_UTILISATEUR = :id");
            $stmt->execute([':password' => $hashedPassword, ':id' => $user_id,':code_reinitialisation' => $code_reinitialisation, ':drapeau_reinitialisation'=>$drapeau_reinitialisation]);


            if($stmt->rowCount() > 0){
                header("Location: connexion.php");
            echo "Votre mot de passe a été réinitialisé avec succès.";
            // header("Location: connexion.php");
            // exit();
        } else {
            $error = "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            $error = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Code de validation invalide.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link rel="stylesheet" href="CSS/style.css">
    <script src="JS/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">

</head>
<body>
    <div  id="id1" class="accueil"> 
        <img class="cl1" src="img/battement-de-coeur (3).png" alt="Logo" height="45px"><h2><b>FitTrack</b></h2>
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
        <section>
            <div>
                <h3>Mot de passe oublié</h3>
                <h4>Générer votre mot de passe</h4>
                <div  class="login-formNo">
                    <form id="emailForm1" method="post" action="Nouveau_mot_de_passe.php">
                       <div><label for="code" >code de validation  </label></div> <br>
                       <div> <input type="password" name="code" id="code" required class="ka1"></div> <br>
                       
                       <div> <label for="password">Nouveau mot de passe</label></div> <br>
                       <div> <input type="password" name="password" id="password" required class="ka2"></div> <br>
                       
                       <div> <label for="confirm_password">Confirmation du nouveau  mot de passe</label></div> <br>
                       <div> <input type="password" name="confirm_password" id="confirm_password" required class="ka3"></div><br>
                       
                       <button type="submit">Valider</button>
                        <button type="button" class="bntNavbar" onclick="window.location.href='home.html';">Annuler</button>
                    </form>
                    <?php if (!empty($error)): ?>
                        <div class="message error" style="color: red;"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>
                </div>
            </div>
            
            <div class="cl12"><img class="cl10" src="img/appel-telephonique (1).png" height="30px" alt="tel">123-456-789 </div>
            <div class="cl13"><img class="cl11" src="img/email (1).png" height="30px" alt="adresse">info.fiitrack@gmail.com</div>
        </section>
        
        <body>
    </html>