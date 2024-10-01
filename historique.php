<?php
require_once("db.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id_to_delete'])) {
    $idToDelete = $_POST['id_to_delete'];

    // Préparer la requête pour supprimer l'entrée avec l'ID spécifié
    $stmt = $PDO->prepare("DELETE FROM imc_calculateur WHERE ID_IMC = :id_to_delete");
    $stmt->bindParam(':id_to_delete', $idToDelete);
    $stmt->execute();

    // Rediriger l'utilisateur après la suppression pour éviter de soumettre à nouveau le formulaire en actualisant la page
    header("Location: " . $_SERVER['PHP_SELF']);
}
// Récupérer les résultats d'IMC de l'utilisateur
$utilisateur = 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
if ($searchQuery) {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE id_utilisateur = :utilisateur_id  AND (IMC LIKE :search OR DATE_CALCUL LIKE :search OR POIDS LIKE :search OR TAILLE LIKE :search)ORDER BY date_calcul DESC");
    $searchQuery = '%' . $searchQuery . '%';
    $stmt->bindParam(':search', $searchQuery);
} else {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE id_utilisateur = :utilisateur_id ORDER BY id_imc DESC, date_calcul DESC");
}
$stmt->bindParam(':utilisateur_id', $utilisateur);
$stmt->execute();
$imc_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$imc_results_json = json_encode($imc_results);
// Récupérer les informations de l'utilisateur
$stmt = $PDO->prepare("SELECT prenom,nom, email FROM utilisateur WHERE id_utilisateur = :utilisateur_id");
$stmt->bindParam(':utilisateur_id', $utilisateur);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
        <div id="b1" class="tableau">
        <img class="cl1" src="img/LOGO.png" alt="Logo" height="40px">
        <h2><b>FitTrack</b></h2>
        <h1 id="c"><?php echo $user['prenom'], ' ', $user['nom'] ?></h1>
        <p class="c1"><?php echo $user['email'] ?></p>
        <div class="profile-container">
            <i class="bi bi-person-circle profile-icon" style="color:#fff"></i>
            <ul class="dropdown-menu" id="profileMenu">
                <li><a href="User_Profil.html" id="p1">Mon Profil</a></li>
                <li><a href="home.html" id="p2">Deconnexion </a></li>
            </ul>
        </div>
        </div>
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
    margin-left: 10px;
    margin-top: 30px;
    font-size: 15px;
    font-family: Poppins;

}

li a {
    display: block;
    color: #fff;
    text-align: center;
    padding: 16x;
    margin-right: 650px;
    text-decoration: none;
    font-size: 15px;
}

li a:hover {
    background-color: transparent;
    color: #fff;
    text-decoration: none;
}
</style>
        </section>
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
    </div>
    <div>
    <form method="GET" action="">
        <div class="search-bar">
            <input type="text" name="search" placeholder="Mots-cles">
            <input type="submit" name="valider" value="rechercher">
            </div>
        </form>
        </div>
    <section>

        <div class="container">
            <div class="historique"></div>
            <h2 class="f1">Historiques</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>imc</th>
                        <th>Date</th>
                        <th>Poids</th>
                        <th>Taille</th>
                        <th>Notification</th>
                        <th><a href="creation.php"><img src="img/ajout.png" class="icon" width="35px" height="35px" alt="Ajouter"></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($imc_results as $row => $imc) {
                        if ($imc['IMC'] < 18.5) {
                            $notification = 'sous-poids';
                        } elseif ($imc['IMC'] >= 18.5 && $imc['IMC'] <= 24.9) {
                            $notification = 'normal';
                        } elseif ($imc['IMC'] >= 25 && $imc['IMC'] <= 29.9) {
                            $notification = 'surpoids';
                        } elseif ($imc['IMC'] >= 30 && $imc['IMC'] <= 34.9) {
                            $notification = 'obesite';
                        } else {
                            $notification = 'extreme-obesite';
                        }
                        
                        
                    ?>
                        <tr>

                           <td><span  title="Situation: <?php echo $notification; ?>" class="<?php echo $notification ?>"><?php echo $imc['IMC'] ?></span></td>
                            <td class="date-yellow"><?php echo $imc['DATE_CALCUL'] ?></td>
                            <td><?php echo  $imc['POIDS'] ?></td>
                            <td><?php echo $imc['TAILLE'] ?></td>
                            <td><?php echo $notification; ?></td>

                            <td>
                                <div class="icon-container" style="position: relative;display:flex;align-items:center">
                                <a href="creation.php?id=<?php echo $imc['ID_IMC']; ?>" class="icon1">
                                     <!-- <a href="creation.php?id=<?php echo $imc['ID_IMC']; ?>" class="icon1" style="margin-right:-55px;margin-left:120px"> -->
                                        
                                <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="id_to_delete" value="<?php echo $imc['ID_IMC']; ?> ">
                                    <button type="submit" name="delete" class="icon2" style="border: none; background: none; cursor: pointer;">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                                </div>
                            </td>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <section>
        <div class="graph-container">
        <canvas id="lineChart"></canvas>
        </div>
    </section>
    <script>
        const imcData = <?php echo $imc_results_json; ?>;

        const labels = imcData.map(point => point.DATE_CALCUL);
        const values = imcData.map(point => point.IMC);
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'IMC',
                    data: values,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 0
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false 
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
        });
    </script>
</body>

</html>