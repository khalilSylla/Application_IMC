<?php
require_once("db1.php");
if (isset($_POST["envoyer"])) {
    $nom = $_POST["Nom"];
    $prenom = $_POST["Prenom"];
    $email = $_POST["Email"];
    $mdp=$_POST["Mot_de_passe"] ;
    $mdph = hash('sha256',$mdp);
    $genre= $_POST["Genre"];

    // Vérifier si l'email existe déjà
    $checkUser = $connectionbd->prepare("SELECT * FROM utilisateur WHERE EMAIL = :email");
    $checkUser->bindParam(':email', $email);
    $checkUser->execute();
    
    if ($checkUser->rowCount() > 0) {
        echo "Cet utilisateur existe déjà ";
    } else {
    $sql = ("INSERT INTO `utilisateur`(`NOM`, `PRENOM`,`EMAIL`, `MOTS_DE_PASSE`,`ID_GENRE`)
               VALUES (:nom, :prenom, :email, :mdph , :genre)");
    $stml = $connectionbd->prepare($sql);
    
    $stml->bindParam(':nom', $nom);
    $stml->bindParam(':prenom', $prenom);
    $stml->bindParam(':email', $email);
    $stml->bindParam(':mdph', $mdph);
    $stml->bindParam(':genre', $genre);

    if ($stml->execute()) {
        echo"Inscription réussie. Redirection vers la page de connexion dans 10 secondes...";
        header("Location:connexion.php?inscription=ok");
    } else {
        echo "L'inscription a échoué.";
    }
}
}
?>
