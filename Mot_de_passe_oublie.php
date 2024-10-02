<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";
require_once "PHPMailer/src/Exception.php";
// require_once 'vendor/autoload.php'; 
require_once "db.php";

$debug = true;

try {
    $mail = new PHPMailer($debug);

    if ($debug) {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    }


    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->Username = "info.fittrack@gmail.com";
    $mail->Password = "projet2024";
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    
    $mail->setFrom('info.fittrack@gmail.com', 'FitTrack');
    $mail->addAddress('seye10bineta@gmail.com', 'Binetou Rassoul Seye');

    
    $mail->isHTML(true);
    $mail->Subject = 'Objet de votre email';
    $mail->Body    = 'Le texte de votre email en HTML.';
    $mail->AltBody = 'Le texte comme simple élément textuel';

    $mail->send();
    echo "Message has been sent successfully";
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
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
        <img class="cl1" src="img/LOGO.png" alt="Logo" height="40px"><h2><b>FitTrack</b></h2>
    <h3>Mot de passe oublié</h3>
    <h4>Générer votre mot de passe</h4>
    <div>
        <form id="emailForm" method="post" action="">
            <label for="email">Email</label><br>
            <input name="email" type="email" id="email" required>
            <button type="submit">Valider</button>
            <button type="button" class="bntNavbar" onclick="window.location.href='home.html';">Annuler</button>
        </form>
        <p class="pp2" style="margin-top: -150px;margin-right: 880px;font-size: 15px;">Veuillez entrer une adresse mail valide pour recevoir le code de verification</p>
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
        <style>
            #emailError {
            color: red;
            font-size: 10px;
            margin-top: 20px;
            margin-left: 30px;
        }
        
        </style>
    </div>
</body>