<?php
$serveurname = "localhost" ;
$username = "root" ;
$password = "" ;
$dbname = "imc" ;

//Etablir la connection a la base de donnes
try {
   $connectionbd = new PDO("mysql:host=$serveurname;dbname=$dbname;port=3307", $username, $password);
   $connectionbd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
   //echo "Connection etablit";
} catch (PDOException $e) {
    echo "la connection a echoue:". $e->getMessage();
}
?>