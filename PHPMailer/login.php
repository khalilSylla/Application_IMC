
    <?php
// Démarrer la session
session_start();
require_once("db1.php");
// On récupère tout le contenu de la table user
$sqlQuery = 'SELECT * FROM user';

$userStatement = $mysqlClient->prepare($sqlQuery);
$userStatement->execute();
$user= $userStatement->fetchAll();
// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hachage du mot de passe avec SHA-256
    $hashed_password = hash('sha256', $password);

    // Requête pour vérifier les identifiants
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email= :email AND password = :password");
    $stmt->execute(['email' => $email, 'password' => $hashed_password]);

    // Vérifier si l'utilisateur existe
    if ($stmt->rowCount() > 0) {
        echo "Connexion réussie !";
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

