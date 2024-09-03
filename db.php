<?php
 
try
{
  $PDO = new PDO('mysql:host=localhost;dbname=imc;port=3307','root','');
  // echo 'connexion etablie !';
}
catch(PDOException $pe)
{
   echo 'ERREUR:'.$pe->getMessage();
}
?>