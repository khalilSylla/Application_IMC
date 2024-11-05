<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php"; 
require_once "db.php";
$error = "";
$debug = true;

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $Code = rand(100000, 999999);
    $stmt = $PDO->prepare("UPDATE utilisateur SET code_reinitialisation = ?, drapeau_reinitialisation = 1 WHERE email = ?");
    $stmt->execute([$Code, $email]);
    
    if ($stmt->rowCount() > 0) {
        $mail = new PHPMailer(true);
        try {
    
    $mail->isSMTP();
    $mail->CharSet = "utf-8";
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->isHTML(true);
    
    $mail->Username = 'info.fittrack@gmail.com';
    $mail->Password = 'vrpwksseuslhvars';
    
    $mail->setFrom('info.fittrack@gmail.com', 'Fittrack');
    $mail->Subject = 'Réinitialisation de votre mot de passe';
    $mail->MsgHTML("
        <p>Bonjour,</p>
        <p>Nous avons reçu une demande de réinitialisation de votre mot de passe. <br> Utilisez le code suivant pour réinitialiser votre mot de passe :</p>
        <h3 style='color: blue;'>$Code</h3>
        <p>Veuillez entrer ce code sur la page de réinitialisation pour continuer le processus.</p>
        <p>Si vous n'avez pas demandé de réinitialisation, vous pouvez ignorer cet email.</p>
        <p>Cordialement,<br>L'équipe Fittrack</p>
    ");
    $mail->addAddress($email);
    
    $mail->send();
            header("Location:Nouveau_mot_de_passe");
            exit();
        } catch (Exception $e) {
           
            $error = "Erreur d'envoi de mail : " . $mail->ErrorInfo;
        }
    } else {
       
        $error = "Aucun utilisateur trouvé avec cet e-mail.";
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
    <h3>Mot de passe oublié</h3>
    <h4>Générer votre mot de passe</h4>
    <div>
        <!-- <section class="messmdp" >Veuillez saisir l'adresse email associée à votre compte. <br> Nous vous enverrons un lien pour réinitialiser votre mot de passe.</section> -->
        <form id="emailForm" method="post" action="">
            <label for="email">Email</label><br>
            <input name="email" type="email" id="email " required>
            <button type="submit">Valider</button>
            <button type="button" class="bntNavbar2" onclick="window.location.href='home.html';">Annuler</button>
        </form>

        
<div class="error-message">
            <?php echo $error; ?>
        <!-- <div class="error-message"> <?php echo $error; ?> </div> -->
        <!-- <p class="pp2" style="margin-top: 70px;margin-right: 19%;font-size: 15px;">Veuillez entrer une adresse mail valide <br> pour recevoir le code de verification</p> -->
        <div class="menu">
            <ul>
                <li><a href="home.html"><b>Accueil</b></a></li>
                <li><a href="connexion.php"><b>Se connecter</b></a></li>
                 
                  <li><a href="deconnexion.php">Se Déconnecter</a></li>
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

        .error-message {
            color: red;
            font-size: 16px;
            margin-top: 20px;
            display: <?php echo empty($error) ? 'none' : 'block'; ?>;
        }
        </style>
        <style>
            #emailError {
            color: red;
            font-size: 10px;
            margin-top: 20px;
            margin-left: 30px;
        }
        
        
        </style>
    </div>
    <div class="cl12"><img class="cl10" src="img/appel-telephonique (1).png" height="30px" alt="tel">123-456-789 </div>
    <div class="cl13"><img class="cl11" src="img/email (1).png" height="30px" alt="adresse">info.fiitrack@gmail.com</div>
</body>
</html>