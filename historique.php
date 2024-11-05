
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà active
}

require_once("db.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Utiliser l'ID de l'utilisateur connecté
$id_utilisateur = $_SESSION['id_utilisateur'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id_to_delete'])) {
    $idToDelete = $_POST['id_to_delete'];

    // Préparer la requête pour supprimer l'entrée avec l'ID spécifié
    $stmt = $PDO->prepare("DELETE FROM imc_calculateur WHERE ID_IMC = :id_to_delete AND ID_UTILISATEUR = :utilisateur_id");
    $stmt->bindParam(':id_to_delete', $idToDelete);
    $stmt->bindParam(':utilisateur_id', $id_utilisateur); // S'assurer que l'IMC appartient à l'utilisateur connecté
    $stmt->execute();

    // Rediriger l'utilisateur après la suppression
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Récupérer les résultats d'IMC de l'utilisateur
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
if ($searchQuery) {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE ID_UTILISATEUR = :utilisateur_id AND (IMC LIKE :search OR DATE_CALCUL LIKE :search OR POIDS LIKE :search OR TAILLE LIKE :search) ORDER BY DATE_CALCUL DESC");
    $searchQuery = '%' . $searchQuery . '%';
    $stmt->bindParam(':search', $searchQuery);
} else {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE ID_UTILISATEUR = :utilisateur_id ORDER BY ID_IMC DESC, DATE_CALCUL DESC");
}
$stmt->bindParam(':utilisateur_id', $id_utilisateur);
$stmt->execute();
$imc_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les informations de l'utilisateur
$stmt = $PDO->prepare("SELECT prenom, nom, email FROM utilisateur WHERE ID_UTILISATEUR = :utilisateur_id");
$stmt->bindParam(':utilisateur_id', $id_utilisateur);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <base href="Application_IMC">
    <link rel="stylesheet" href="/CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
        <div id="b1" class="tableau">
        <img class="cl1" src="img/battement-de-coeur (3).png" alt="Logo" height="45px">
        <h2><b>FitTrack</b></h2>
        <h1 id="c"><?php echo $user['prenom'], ' ', $user['nom'] ?></h1>
        <p class="c1"><?php echo $user['email'] ?></p>
        <div class="profile-container">
            <img src="img/utilisateur (3).png" alt="Profil" class="profile-icon" style="width: 64px; height: 64px; color: #fff;color:#fff">

            <ul class="dropdown-menu" id="profileMenu">
                <li><a href="User_Profil.html" id="p1">Mon Profil</a></li>
                <li><a href="deconnexion.php" id="p2">Deconnexion </a></li>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
    const searchQuery = "<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>";
    
    if (searchQuery) {
        const rows = document.querySelectorAll('.table tbody tr');
        
        rows.forEach(row => {
            row.querySelectorAll('td').forEach(cell => {
                // Exclure les cellules qui contiennent des icônes ou autres éléments non textuels
                if (!cell.querySelector('i') && !cell.querySelector('button') && !cell.querySelector('a')) {
                    const originalText = cell.textContent; // Récupérer uniquement le texte brut
                    const regex = new RegExp(searchQuery, 'gi');
                    const highlightedText = originalText.replace(regex, (match) => `<span class="highlight">${match}</span>`);
                    cell.innerHTML = highlightedText; // Réinsérer le texte modifié sans toucher aux éléments HTML
                }
            });
        });
    }
});

        </script>    
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
                                <!-- <a href="creation/<?php echo $imc['ID_IMC']; ?>" class="icon1"> -->
                                

                                     <a href="creation.php?id=<?php echo $imc['ID_IMC']; ?>" class="icon1" style="margin-right:-55px;margin-left:120px">
                                        
                               
                                <img src="img/crayon (4).png" alt="Modifier" style="width: 24px; height: 24px;">
                               
                                </a>
                                <form method="POST" action="" style="display: inline;">
                                    <input type="hidden" name="id_to_delete" value="<?php echo $imc['ID_IMC']; ?> ">
                                    <button type="submit" name="delete" class="icon2" style="border: none; background: none; cursor: pointer;">
                                      
                                        <img src="img/poubelle-de-recyclage (2).png" alt="Supprimer" style="width: 24px; height: 24px;">
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