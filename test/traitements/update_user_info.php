
<?php
$adr = $_POST['rue'] .", ". $_POST['code']. " ".mb_strtoupper($_POST['ville']);

$utilisateur = "inf2pj02";
$serveur = "localhost";
$motdepasse = "ahV4saerae";
$basededonnees = "inf2pj_02";
$bdd = new PDO('mysql:host='.$serveur.';dbname='.$basededonnees,$utilisateur,$motdepasse);
session_start();
// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité

$update="UPDATE UTILISATEUR SET Nom_Uti = '".$_POST["new_nom"]."',". "Prenom_Uti = '".$_POST["new_prenom"]."',". "Adr_Uti = '".$adr."' WHERE Mail_Uti = '".$_SESSION["Mail_Uti"] ."';";

echo ($update);
$bdd->exec($update);
header('Location: user_informations.php');    
?>