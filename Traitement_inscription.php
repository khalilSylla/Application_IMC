<?php
require_once("db1.php");

if (isset($_POST["envoyer"])) {
    $nom = $_POST["Nom"];
    $prenom = $_POST["Prenom"];
    $email = $_POST["Email"];
    $mdp = $_POST["Mot_de_passe"];
    $mdph = hash('sha256', $mdp);
    $genre = $_POST["Genre"];

    // Vérifier si l'email existe déjà
    $checkUser = $connectionbd->prepare("SELECT * FROM utilisateur WHERE EMAIL = :email");
    $checkUser->bindParam(':email', $email);
    $checkUser->execute();
    
    if ($checkUser->rowCount() > 0) {
        // Utilisateur existe déjà : affiche une alerte et redirige
        echo "<script>
                alert('Cet utilisateur existe déjà. Veuillez vous connecter');
                window.location.href = 'connexion.php';
              </script>";
        exit; // Arrête le script après l'alerte
    } else {
        // Insérer les données dans la table utilisateur
        $sql = "INSERT INTO `utilisateur`(`NOM`, `PRENOM`, `EMAIL`, `MOT_DE_PASSE`, `ID_GENRE`)
                VALUES (:nom, :prenom, :email, :mdph , :genre)";
        $stml = $connectionbd->prepare($sql);
        
        $stml->bindParam(':nom', $nom);
        $stml->bindParam(':prenom', $prenom);
        $stml->bindParam(':email', $email);
        $stml->bindParam(':mdph', $mdph);
        $stml->bindParam(':genre', $genre);

        if ($stml->execute()) {
            // Alerte de succès et redirection immédiate
            echo "<script>
                    alert('Inscription réussie. Vous allez être redirigé vers la page de connexion.');
                    window.location.href = 'connexion.php?inscription=ok';
                  </script>";
            exit; // Arrête le script après l'alerte
        } else {
            echo "L'inscription a échoué.";
        }
    }
}
?>
