<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: connexion.php');
    exit();
}

// Récupérer l'ID de l'utilisateur connecté depuis la session
$id_utilisateur = $_SESSION['id_utilisateur'];

// Inclure le fichier de connexion à la base de données
require_once("db1.php");

// Requête pour récupérer les informations de l'utilisateur, y compris le mot de passe haché
$queryUtilisateur = "SELECT PRENOM, NOM, EMAIL, DATE_DE_NAISSANCE, ID_GENRE, MOT_DE_PASSE FROM utilisateur WHERE ID_UTILISATEUR = :id";
$stmtUtilisateur = $connectionbd->prepare($queryUtilisateur);
$stmtUtilisateur->bindParam(':id', $id_utilisateur, PDO::PARAM_INT);
$stmtUtilisateur->execute();

// Initialiser des valeurs par défaut
$prenom = $nom = $email = $genre = $dateNaissanceFormatee = $erreurMotDePasse = '';
$age = null;
$motDePasseHash = '';

// Vérification si les données existent
if ($stmtUtilisateur->rowCount() > 0) {
    $user = $stmtUtilisateur->fetch(PDO::FETCH_ASSOC);

    // Déterminer le genre
    $genre = match ($user['ID_GENRE']) {
        1 => "Homme",
        2 => "Femme",
        default => "Non spécifié"
    };

    // Calculer l'âge à partir de la date de naissance si elle est présente
    if (!empty($user['DATE_DE_NAISSANCE'])) {
        $dateNaissance = new DateTime($user['DATE_DE_NAISSANCE']);
        $aujourdhui = new DateTime();
        $age = $aujourdhui->diff($dateNaissance)->y; // Calculer l'âge
        $dateNaissanceFormatee = $dateNaissance->format('d/m/Y'); // Formater la date de naissance
    }

    // Récupérer les autres informations de l'utilisateur
    $prenom = $user['PRENOM'] ?? '';
    $nom = $user['NOM'] ?? '';
    $email = $user['EMAIL'] ?? '';
    $motDePasseHash = $user['MOT_DE_PASSE'] ?? ''; // Récupérer le mot de passe haché
} else {
    echo "Erreur : Impossible de récupérer les informations de l'utilisateur";
    exit;
}

// Vérification des modifications et mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Préparer la mise à jour sélective
    $champsModifies = [];
    $params = [];

    // Vérifier si chaque champ a été modifié par rapport aux valeurs actuelles
    if (isset($_POST['prenom']) && $_POST['prenom'] !== $prenom) {
        $champsModifies[] = "PRENOM = :prenom";
        $params[':prenom'] = $_POST['prenom'];
    }

    if (isset($_POST['nom']) && $_POST['nom'] !== $nom) {
        $champsModifies[] = "NOM = :nom";
        $params[':nom'] = $_POST['nom'];
    }

    if (isset($_POST['email']) && $_POST['email'] !== $email) {
        $champsModifies[] = "EMAIL = :email";
        $params[':email'] = $_POST['email'];
    }

    if (isset($_POST['genre']) && $_POST['genre'] != $user['ID_GENRE']) {
        $champsModifies[] = "ID_GENRE = :genre";
        $params[':genre'] = $_POST['genre'];
    }

    if (isset($_POST['date_naissance']) && $_POST['date_naissance'] !== $user['DATE_DE_NAISSANCE']) {
        $champsModifies[] = "DATE_DE_NAISSANCE = :dateNaissance";
        $params[':dateNaissance'] = $_POST['date_naissance'];
    }

    // Si des champs ont été modifiés, exécuter la requête d'UPDATE
    if (!empty($champsModifies)) {
        $queryUpdateUser = "UPDATE utilisateur SET " . implode(', ', $champsModifies) . " WHERE ID_UTILISATEUR = :id";
        $params[':id'] = $id_utilisateur;
        var_dump($id_utilisateur);

        $stmtUpdateUser = $connectionbd->prepare($queryUpdateUser);
        foreach ($params as $param => $value) {
            $stmtUpdateUser->bindValue($param, $value);
        }

        // Exécuter la mise à jour des données utilisateur
        $stmtUpdateUser->execute();
    }

    // Vérification du changement de mot de passe si les champs sont remplis
    $ancienMotDePasse = $_POST['ancien_mot_de_passe'] ?? '';
    $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'] ?? '';

    if (!empty($ancienMotDePasse) && !empty($nouveauMotDePasse)) {
        // Hacher l'ancien mot de passe pour comparaison
        $ancienMotDePasseHash = hash('sha256', $ancienMotDePasse);

        // Vérifier que l'ancien mot de passe haché correspond à celui stocké dans la base de données
        if ($ancienMotDePasseHash === $motDePasseHash) {
            // Hacher et mettre à jour le nouveau mot de passe
            $nouveauMotDePasseHash = hash('sha256', $nouveauMotDePasse);
            $queryUpdatePassword = "UPDATE utilisateur SET MOT_DE_PASSE = :newPassword WHERE ID_UTILISATEUR = :id";
            $stmtUpdatePassword = $connectionbd->prepare($queryUpdatePassword);
            $stmtUpdatePassword->bindParam(':newPassword', $nouveauMotDePasseHash, PDO::PARAM_STR);
            $stmtUpdatePassword->bindParam(':id', $id_utilisateur, PDO::PARAM_INT);
            $stmtUpdatePassword->execute();

            // Redirection après mise à jour réussie
            header('Location: User_Profil.php?message=success');
            exit;
        } else {
            // Message d'erreur si l'ancien mot de passe est incorrect
            $erreurMotDePasse = "Ancien mot de passe incorrect.";
        }
    } else {
        // Si pas de changement de mot de passe, rediriger avec un succès de mise à jour des infos
        header('Location: User_Profil.php?message=success');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <link rel="stylesheet" href="CSS/User_profil_Design.css">
    <!-- Lien vers Google Fonts pour les polices Alfa Slab One et Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <script>
    // Fonction pour activer/désactiver les champs du formulaire
    function activerModification() {
        document.getElementById('prenom').disabled = false;
        document.getElementById('nom').disabled = false;
        document.getElementById('genre').disabled = false;
        document.getElementById('date_naissance').disabled = false;
        document.getElementById('ancien_mot_de_passe').disabled = false; // Activer le champ ancien mot de passe
        document.getElementById('modifier').style.display = 'none';
        document.getElementById('sauvegarder').style.display = 'inline';
        
        afficherNouveauMotDePasse();
    }

    // Fonction pour afficher le champ "Nouveau mot de passe" lorsque l'ancien est rempli
    function afficherNouveauMotDePasse() {
        var ancienMotDePasse = document.getElementById('ancien_mot_de_passe').value;
        var nouveauMotDePasseDiv = document.getElementById('nouveau_mot_de_passe_div');

        if (ancienMotDePasse.length > 0) {
            nouveauMotDePasseDiv.style.display = 'block';
            document.getElementById('nouveau_mot_de_passe').disabled = false;
        } else {
            nouveauMotDePasseDiv.style.display = 'none';
            document.getElementById('nouveau_mot_de_passe').disabled = true;
        }
    }

    // Fonction de validation avant la soumission du formulaire
    // Fonction de validation avant la soumission du formulaire
    function validerFormulaire() {
        var ancienMotDePasse = document.getElementById('ancien_mot_de_passe').value;
        var nouveauMotDePasse = document.getElementById('nouveau_mot_de_passe').value;
        var erreurLongueurMotDePasse = document.getElementById('erreurLongueurMotDePasse');
    
    // Réinitialiser le message d'erreur
        erreurLongueurMotDePasse.textContent = "";

    // Si l'ancien mot de passe est rempli mais pas le nouveau, afficher une alerte
        if (ancienMotDePasse.length > 0 && nouveauMotDePasse.length === 0) {
            alert("Veuillez remplir le champ 'Nouveau mot de passe'.");
        return false; // Empêcher la soumission du formulaire
    }

    // Vérifier le nombre minimum de caractères pour le nouveau mot de passe
    if (nouveauMotDePasse.length > 0 && nouveauMotDePasse.length < 8) {
        erreurLongueurMotDePasse.textContent = "Le mot de passe doit contenir au minimum huit caractères";
        return false; // Empêcher la soumission du formulaire
    }

    return true; // Permettre la soumission du formulaire
}

</script>


</head>
<body>
    <div id="id1">
    <img class="cl12" src="img/battement-de-coeur (3).png" alt="Logo" height="45px"><h2>FitTrack</h2>
        <header>
            <a class="home" href="historique.php">Accueil</a>
            <a class="con" href="deconnexion.php">Se Déconnecter</a>
            <a class="about" href="#">À Propos</a>
        </header>
    </div>
 <section class="position_profil">
 <form class="profile-container" method="post">
        <h3 style="font-family: 'Alfa Slab One', cursive;">Mon profil</h3>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" disabled>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" disabled>

        <label for="email">Email</label>
        <input type="email" id="email" value="<?= htmlspecialchars($email) ?>" disabled>

        <label for="genre">Genre</label>
        <select class="sexe_disign" id="genre" name="genre" disabled>
            <option value="1" <?= $genre === "Homme" ? 'selected' : '' ?>>Homme</option>
            <option value="2" <?= $genre === "Femme" ? 'selected' : '' ?>>Femme</option>
            <option value="0" <?= $genre === "Non spécifié" ? 'selected' : '' ?>>Non spécifié</option>
        </select>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($user['DATE_DE_NAISSANCE'] ?? '') ?>" disabled>

        <label for="age">Âge</label>
        <input type="text" id="age" value="<?= $age !== null ? $age . ' ans' : 'Non spécifié' ?>" disabled>

        <h4 style="font-family: 'Alfa Slab One', cursive;">Changer de mot de passe</h4>

            <label for="ancien_mot_de_passe">Ancien mot de passe</label>
            <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" oninput="afficherNouveauMotDePasse()" disabled>
            <!-- Message d'erreur pour l'ancien mot de passe -->
        <?php if (!empty($erreurMotDePasse)): ?>
            <p class="ereur_mdpa" style="color:red;"><?= htmlspecialchars($erreurMotDePasse) ?></p>
        <?php endif; ?>
            <div id="nouveau_mot_de_passe_div" class="nouveau_mot_de_passe_div" style="display:none;">
            <label for="nouveau_mot_de_passe">Nouveau mot de passe</label>
            <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe"  disabled>
            <span id="erreurLongueurMotDePasse" style="color:red;"></span>
           </div>
        <div class="center">
            <button type="button" class="cl6" id="modifier" onclick="activerModification()">Modifier</button>
            <button type="submit" class="cl6" id="sauvegarder" style="display:none;" onclick="return validerFormulaire()">Sauvegarder</button>
            <button type="button" class="cl6" onclick="window.location.href='historique.php';" id="Page_historique" >Historique IMC</button>
        </div>
    </form>
 </section>

</body>
</html>
