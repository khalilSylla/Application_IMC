<?php
// Inclure le fichier de connexion à la base de données
require_once("db1.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $idUtilisateur = $_POST['id_utilisateur'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $genre = $_POST['genre'];
    $dateNaissance = $_POST['date_naissance'];

    // Préparer la requête de mise à jour
    $queryUpdate = "UPDATE utilisateur SET PRENOM = :prenom, NOM = :nom, ID_GENRE = :genre, DATE_DE_NAISSANCE = :date_naissance WHERE ID_UTILISATEUR = :id";
    $stmtUpdate = $connectionbd->prepare($queryUpdate);
    $stmtUpdate->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':genre', $genre, PDO::PARAM_INT);
    $stmtUpdate->bindParam(':date_naissance', $dateNaissance, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':id', $idUtilisateur, PDO::PARAM_INT);

    // Exécuter la requête et rediriger avec le message
    if ($stmtUpdate->execute()) {
        header("Location: User_profil.php?message=success");
    } else {
        header("Location: User_profil.php?message=error");
    }
    exit();
}
?>
