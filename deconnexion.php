<?php
session_start(); // Démarrer la session

// Détruire toutes les données de la session
$_SESSION = []; // Réinitialiser les données de session
session_destroy(); // Détruire la session

// Rediriger vers la page de connexion
header('Location: connexion.php');
exit();
?>
