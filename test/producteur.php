<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>
    <?php
     function dbConnect(){
        $utilisateur = "inf2pj02";
        $serveur = "localhost";
        $motdepasse = "ahV4saerae";
        $basededonnees = "inf2pj_02";
    
        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
      }
      if(!isset($_SESSION)){
        session_start();
        }
      // variable utilisée plusieurs fois par la suite
      $Id_Prod = htmlspecialchars($_GET["Id_Prod"]);

      if (isset($_GET["filtreType"])==true){
        $filtreType=htmlspecialchars($_GET["filtreType"]);
      }
      else{
        $filtreType="TOUT";
      }
      if (isset($_GET["tri"])==true){
        $tri=htmlspecialchars($_GET["tri"]);
      }
      else{
        $tri="No";
      }
      if (isset($_GET["rechercheNom"])==true){
        $rechercheNom=htmlspecialchars($_GET["rechercheNom"]);
      }
      else{
        $rechercheNom="";
      }
    ?>
    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                <!-- some code -->
            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php">Accueil</a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])){
                            echo'<a class="bontonDeNavigation" href="messagerie.php">Messagerie</a>';
                            echo'<a class="bontonDeNavigation" href="achats.php">Achats</a>';
                        }
                        if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                            echo'<a class="bontonDeNavigation" href="produits.php">Produits</a>';
                            echo'<a class="bontonDeNavigation" href="delivery.php">Commandes</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if(!isset($_SESSION)){
                    session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
					<input type="submit" value=<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo '"Se Connecter"';}else {echo '"'.$_SESSION['Mail_Uti'].'"';}?> class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
				</form>
            </div>
            <div class="contenuPage">

               <!-- some code -->

            </div>
            <div class="basDePage">
                <form method="post">
						<input type="submit" value="Signaler un dysfonctionnement" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
						<input type="submit" value="CGU" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>