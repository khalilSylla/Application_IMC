<?php

require_once('db.php');
// Initialisation des variables
$id = $poids = $taille = $imc = $date = '';
$id_user = 1;
$stmt_user = $PDO->prepare('SELECT prenom, nom, email FROM utilisateur WHERE ID_UTILISATEUR = :id_user');
$stmt_user->execute([':id_user' => $id_user]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
// var_dump($user);

// Assurez-vous que les informations de l'utilisateur sont récupérées correctement
if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}
// Vérifiez si un ID est passé dans l'URL pour la modification
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Requête SQL pour récupérer les valeurs
    $stmt = $PDO->prepare('SELECT * FROM imc_calculateur WHERE ID_IMC  = :id AND ID_UTILISATEUR=:id_user');
    $stmt->execute([':id' => $id, ':id_user' => $id_user]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $poids = $row['POIDS'];
        $taille = $row['TAILLE'];
        $imc = $row['IMC'];
        $date = $row['DATE_CALCUL'];
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 1;
    $poids = $_POST['POIDS'];
    $taille = $_POST['TAILLE'];
    $imc = $_POST['IMC'];
    $date = $_POST['DATE_CALCUL'];
    // var_dump($_POST);
    echo $id;

    if ($id ) {
        // Modification
        $stmt = $PDO->prepare('UPDATE imc_calculateur SET POIDS = :poids, TAILLE = :taille, IMC = :imc, DATE_CALCUL = :date_calcul WHERE ID_IMC = :id AND ID_UTILISATEUR=:id_user');
        $stmt->execute([
            ':poids' => $poids,
            ':taille' => $taille,
            ':imc' => $imc,
            ':date_calcul' => $date,
            // ':notification' => $notification,
            ':id' => $id,
            ':id_user' => $id_user
        ]);
    } else {
        // Ajout
        $stmt = $PDO->prepare('INSERT INTO imc_calculateur(POIDS, TAILLE, IMC, DATE_CALCUL,ID_UTILISATEUR) VALUES (:poids, :taille, :imc, :date_calcul,:id)');
        $stmt->execute([
            ':poids' => $poids,
            ':taille' => $taille,
            ':imc' => $imc,
            ':date_calcul' => $date,
            ':id' => $id_user,
            // ':notification' => $notification
        ]);
        $id = $PDO->lastInsertId();
    }
function deduireNotificationParId($id) {
    global $PDO;

    $stmt = $PDO->prepare("SELECT IMC FROM imc_calculateur WHERE ID_IMC = ?");
    $stmt->execute([$id]);
    $entry = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($entry === false) {
        throw new Exception("Aucune entrée trouvée pour cet ID.");
    }

    $imc = $entry['IMC'];
    if ($imc < 18.5) {
        $notification = 'sous-poids';
    } elseif ($imc >= 18.5 && $imc <= 24.9) {
        $notification = 'normal';
    } elseif ($imc >= 25 && $imc <= 29.9) {
        $notification = 'surpoids';
    } elseif ($imc>= 30 && $imc <= 34.9) {
        $notification = 'obesite';
    } else {
        $notification = 'extreme-obesite';
    }
    

    $updateStmt = $PDO->prepare("UPDATE imc_calculateur SET notification = ? WHERE ID_IMC = ?");
    $updateStmt->execute([$notification, $id]);

    return "La notification a été mise à jour : " . $notification;
}

try {
    $resultat = deduireNotificationParId($id);
    echo $resultat;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

header('Location: historique.php');
exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter ou Modifier IMC</title>
    <base href="Application_IMC">
    <link rel="stylesheet" href="/CSS/style.css">
    <script src="JS/script.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
</head>

<body>
    <div id="b1" class="tableau">
        <img class="cl1" src="/img/battement-de-coeur (3).png" alt="Logo" height="45px">
        <h2><b>FitTrack</b></h2>
        <h1 id="c"><?php echo $user['prenom'], ' ', $user['nom'] ?></h1>
        <p class="c1"><?php echo $user['email'] ?></p>
        <div class="profile-container">
            <img src="img/utilisateur (3).png" alt="Profil" class="profile-icon" style="width: 64px; height: 64px; color: #fff;color:#fff">
            <ul class="dropdown-menu" id="profileMenu">
                <li><a href="User_Profil.html" id="p1">Mon Profil</a></li>
                <li><a href="home.html" id="p2">Deconnexion </a></li>
            </ul>
        </div>
        </div>
        <script>
document.addEventListener('DOMContentLoaded', function () {
    const profileIcon = document.querySelector('.profile-icon');
    const profileMenu = document.getElementById('profileMenu');

    profileIcon.addEventListener('click', function (event) {
        event.stopPropagation();
        // Affiche ou masque le menu au clic
        if (profileMenu.style.display === 'block') {
            profileMenu.style.display = 'none';
        } else {
            profileMenu.style.display = 'block';
        }
    });

    // Ferme le menu si l'utilisateur clique en dehors
    document.addEventListener('click', function (e) {
        if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.style.display = 'none';
        }
    });
});
</script>
        <div class="menu">
            <ul>
                <li><a href="home.html"><b>Accueil</b></a></li>
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
                margin-right: 430px;
                text-decoration: none;
                font: size 15px;
            }

            li a:hover {
                background-color: transparent;
                color: #fff;
                text-decoration: none;
            }
        </style>
    </div>
    <div class="container2">
        <h2><?php echo $id ? "Modifier" : "Ajouter"; ?> un Calcul IMC</h2>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
            <!-- <button type="button" class="cancel-btn" onclick="window.location.href='historique.php'">Annuler</button> -->

            <?php //if ($id): 
            ?>
            <label for="imc">IMC:</label><br>
            <!-- <input type="text1" id="imc" name="imc" placeholder="IMC sera calculé automatiquement" readonly > -->
            <!-- <input type="text1" id="iIMC" name="IMC" value="<?php echo $imc; ?>" readonly><br> -->
            <?php // endif; 
            ?>
            <input type="<?php echo $id ? 'text1' : 'hidden'; ?> " id="IMC" name="IMC" value="<?php echo $imc; ?>" readonly><br>
            <label for="poids">Poids (kg):</label><br>
            <!-- <input type="text1" id="Poids" name="poids" required> -->
            <input type="text1" id="POIDS" name="POIDS" value="<?php echo $poids; ?>" required><br>

            <label for="taille">Taille (cm):</label><br>
            <!-- <input type="text1" step="0.01" id="Taille" name="taille" required> -->
            <input type="text1" id="TAILLE" name="TAILLE" value="<?php echo $taille; ?>" required><br>

            <label for="date">Date:</label><br>
            <!-- <input type="date" id="date" name="date" required><br> -->
            <input type="date" id="DATE_CALCUL" name="DATE_CALCUL" value="<?php echo $date; ?>" required><br>
            <!-- <button type="submit"><strong>Ajouter/Modifier</strong></button> -->
            <button type="submit"><?php echo $id ? "Modifier" : "Ajouter"; ?></button>
            <button type="button" class="cancel-btn" onclick="window.location.href='historique.php'">Annuler</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const poidsInput = document.getElementById('POIDS');
            const tailleInput = document.getElementById('TAILLE');
            const imcInput = document.getElementById('IMC');


            function calculateIMC() {
                const poids = parseFloat(poidsInput.value);
                const tailleCm = parseFloat(tailleInput.value);
                const taille = tailleCm / 100;

                if (!isNaN(poids) && !isNaN(taille) && taille > 0) {
                    const imc = (poids / (taille * taille)).toFixed(2);
                    imcInput.value = imc;
                    debugger;
                } else {
                    if (!imcInput || imcInput.readOnly === false) {
                        imcInput.value = ''; // Vide si les valeurs sont invalides
                    }
                }
            }

            poidsInput.addEventListener('input', calculateIMC);
            tailleInput.addEventListener('input', calculateIMC);
        });
    </script>
</body>

</html>