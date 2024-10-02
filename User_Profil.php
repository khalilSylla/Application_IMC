<?php
// Inclure le fichier de connexion à la base de données
require_once("db1.php");

// ID de l'utilisateur (à remplacer par l'ID actuel ou à récupérer dynamiquement)
$idUtilisateur = 17; // Remplacez par l'ID actuel de l'utilisateur

// Requête pour récupérer les informations de l'utilisateur, y compris le mot de passe haché
$queryUtilisateur = "SELECT PRENOM, NOM, EMAIL, DATE_DE_NAISSANCE, ID_GENRE, MOTS_DE_PASSE FROM utilisateur WHERE ID_UTILISATEUR = :id";
$stmtUtilisateur = $connectionbd->prepare($queryUtilisateur);
$stmtUtilisateur->bindParam(':id', $idUtilisateur, PDO::PARAM_INT);
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
        $params[':id'] = $idUtilisateur;

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
        // Vérifier que l'ancien mot de passe correspond
        if (hash('sha256', $ancienMotDePasse) === $motDePasseHash) {
            // Hacher et mettre à jour le nouveau mot de passe
            $nouveauMotDePasseHash = hash('sha256', $nouveauMotDePasse);
            $queryUpdatePassword = "UPDATE utilisateur SET MOT_DE_PASSE = :newPassword WHERE ID_UTILISATEUR = :id";
            $stmtUpdatePassword = $connectionbd->prepare($queryUpdatePassword);
            $stmtUpdatePassword->bindParam(':newPassword', $nouveauMotDePasseHash, PDO::PARAM_STR);
            $stmtUpdatePassword->bindParam(':id', $idUtilisateur, PDO::PARAM_INT);
            $stmtUpdatePassword->execute();

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
    <link rel="stylesheet" href="CSS/Design_Inscription_IMC.css">
    <script>
        // Fonction pour activer/désactiver les champs du formulaire
        function activerModification() {
            document.getElementById('prenom').disabled = false;
            document.getElementById('nom').disabled = false;
            document.getElementById('genre').disabled = false;
            document.getElementById('date_naissance').disabled = false;
            document.getElementById('ancien_mot_de_passe').disabled = false;
            document.getElementById('modifier').style.display = 'none';
            document.getElementById('sauvegarder').style.display = 'inline';
        }

        // Fonction pour afficher le champ "Nouveau mot de passe" lorsque l'ancien est rempli
        function afficherNouveauMotDePasse() {
            var ancienMotDePasse = document.getElementById('ancien_mot_de_passe').value;
            var nouveauMotDePasseDiv = document.getElementById('nouveau_mot_de_passe_div');

            // Si l'ancien mot de passe est rempli, afficher le champ pour le nouveau mot de passe
            if (ancienMotDePasse.length > 0) {
                nouveauMotDePasseDiv.style.display = 'block';
            } else {
                nouveauMotDePasseDiv.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div id="id1">
        <img class="cl12" src="img/LOGO.png" alt="Logo" height="50px">
        <h3><b>FitTrack</b></h3>
        <header>
            <a class="home" href="Application_IMC/home.html">Accueil</a>
            <a class="con" href="Application_IMC/connexion.html">Se Déconnecter</a>
            <a class="about" href="#">À Propos</a>
        </header>
    </div>

    <div class="profile-container">
        <!-- Mon profil -->
        <div class="mon_profil">
            <h3>Mon profil</h3>
        </div>

        <!-- Formulaire pré-rempli -->
        <form action="User_Profil.php" method="POST" id="id3">
            <input type="hidden" name="id_utilisateur" value="<?php echo htmlspecialchars($idUtilisateur); ?>">

            <div class="personal-info">
                <label>Prénom </label><br>
                <input type="text" id="prenom" name="prenom" class="cl3" value="<?php echo htmlspecialchars($prenom); ?>" disabled><br>

                <label>Nom </label><br>
                <input type="text" id="nom" name="nom" class="cl3" value="<?php echo htmlspecialchars($nom); ?>" disabled><br>

                <label>Email </label><br>
                <input type="email" id="email" name="email" class="cl3" value="<?php echo htmlspecialchars($email); ?>" disabled><br>

                <label>Genre </label><br>
                <select id="genre" class="cl3" name="genre" disabled>
                    <option value="1" <?php if ($user['ID_GENRE'] == 1) echo 'selected'; ?>>Homme</option>
                    <option value="2" <?php if ($user['ID_GENRE'] == 2) echo 'selected'; ?>>Femme</option>
                </select><br>

                <label>Date de naissance </label><br>
                <input type="date" id="date_naissance" class="cl3" name="date_naissance" value="<?php echo htmlspecialchars($user['DATE_DE_NAISSANCE'] ?? ''); ?>" disabled><br>

                <?php if (isset($age)) : ?>
                <label>Âge </label><br>
                <input type="text" class="cl3" value="<?php echo htmlspecialchars($age); ?> ans" disabled><br>
                <?php endif; ?>
                
                <h4>Modifier mon mot de passe</h4>

                <!-- Champ pour l'ancien mot de passe -->
                <label>Ancien mot de passe</label><br>
                <input type="password" id="ancien_mot_de_passe" placeholder="Entrer votre ancien mot de passe" name="ancien_mot_de_passe" class="cl3" oninput="afficherNouveauMotDePasse()" disabled><br>

                <!-- Message d'erreur en rouge -->
                <?php if (!empty($erreurMotDePasse)): ?>
                    <span style="color: red;"><?php echo htmlspecialchars($erreurMotDePasse); ?></span><br>
                <?php endif; ?>

                <!-- Champ pour le nouveau mot de passe, caché initialement -->
                <div id="nouveau_mot_de_passe_div" style="display:none;">
                    <label>Nouveau mot de passe</label><br>
                    <input type="password" id="nouveau_mot_de_passe" placeholder="Entrer votre nouveau mot de passe"  name="nouveau_mot_de_passe" class="cl3"><br>
                </div>
            </div><br>

            <!-- Boutons -->
            <div class="action-buttons">
                <button type="button" class="cl6" id="modifier" onclick="activerModification()">Modifier le profil</button>
                <button type="submit" class="cl6" id="sauvegarder" style="display:none;">Sauvegarder</button>
                <button type="button" class="cl6" onclick="window.location.href='historique.php'">Historique</button>
            </div>
        </form>
    </div>
</body>
</html>
