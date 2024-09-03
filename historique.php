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
    // exit();
}
// Récupérer les résultats d'IMC de l'utilisateur
$utilisateur = 1;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
if ($searchQuery) {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE id_utilisateur = :utilisateur_id  AND (IMC LIKE :search OR DATE_CALCUL LIKE :search OR POIDS LIKE :search OR TAILLE LIKE :search)ORDER BY date_calcul DESC");
    $searchQuery = '%' . $searchQuery . '%';
    $stmt->bindParam(':search', $searchQuery);
} else {
    $stmt = $PDO->prepare("SELECT * FROM imc_calculateur WHERE id_utilisateur = :utilisateur_id ORDER BY date_calcul DESC");
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
    <script src="JS/script.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div id="b1" class="tableau">
        <img class="cl1" src="img/LOGO.png" alt="Logo" height="40px">
        <h2><b>FitTrack</b></h2>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Mots-cles" class="se1">
            <input type="submit" name="valider" value="rechercher">
        </form>
        <h1 id=se><?php echo $user['prenom'], ' ', $user['nom'] ?></h1>
        <p class="s4"><?php echo $user['email'] ?></p>

        <div class="menu">
            <ul>
                <li><a href="home.html"><b>Accueil</b></a></li>
                <li><a href="home.html"><b>Se deconnecter</b></a></li>
                <li><a href="#"><b>A propos</b></a></li>
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
                font: size 15px;
            }

            li a:hover {
                background-color: transparent;
                color: #fff;
                text-decoration: none;
            }
        </style>
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
                        <th><a href="creation.php"><img src="img/ajout.png" class="icon" width="35px" height="35px" alt="Ajouter"></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($imc_results as $row => $imc) {
                    ?>
                        <tr>

                            <td><?php echo $imc['IMC'] ?></td>
                            <td><?php echo $imc['DATE_CALCUL'] ?></td>
                            <td><?php echo  $imc['POIDS'] ?></td>
                            <td><?php echo $imc['TAILLE'] ?></td>

                            <td>
                                <a href="creation.php?id=<?php echo $imc['ID_IMC']; ?>" class="icon1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?');" style="display:inline;">
                                    <input type="hidden" name="id_to_delete" value="<?php echo $imc['ID_IMC']; ?>">
                                    <button type="submit" name="delete" class="icon2" style="border: none; background: none; cursor: pointer;">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
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
        <canvas id="lineChart"></canvas>
    </section>
    <p class="graph">Graphique IMC </p>
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
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>