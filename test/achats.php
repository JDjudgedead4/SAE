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
    if(!isset($_SESSION)){
        session_start();
        }

        function dbConnect(){
            $utilisateur = "inf2pj02";
            $serveur = "localhost";
            $motdepasse = "ahV4saerae";
            $basededonnees = "inf2pj_02";
            // Connect to database
            return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
        }

        $bdd=dbConnect();
        $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
        
        $filtreCategorie=0;
        if (isset($_POST["typeCategorie"])==true){
            $filtreCategorie=htmlspecialchars($_POST["typeCategorie"]);
        }
    
    ?>

    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                
            
            <center>
                <p><strong>Filtrer par :</strong></p>
                <br>
            </center>
            Statut 
            <br>
            
            <form action="achats.php" method="post">
                <label>
                    <input type="radio" name="typeCategorie" value="0" <?php if($filtreCategorie==0) echo 'checked="true"';?>> TOUT
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="1" <?php if($filtreCategorie==1) echo 'checked="true"';?>> EN COURS
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="2"<?php if($filtreCategorie==2) echo 'checked="true"';?>> PRÊTE
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="4" <?php if($filtreCategorie==4) echo 'checked="true"';?>> LIVRÉE
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="3" <?php if($filtreCategorie==3) echo 'checked="true"';?>> ANNULÉE
                </label>

                <br>
                <br>
                <center>
                    <input type="submit" value="Filtrer">
                </center>
            </form>

            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php">Accueil</a>
                    <a class="bontonDeNavigation" href="messagerie.php">Messagerie</a>
                    <a class="bontonDeNavigation" href="achats.php">Achats</a>
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

               
            <?php
				$query='SELECT Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut FROM COMMANDE INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut WHERE COMMANDE.Id_Uti= :utilisateur';
				if ($filtreCategorie!=0){
					$query=$query.' AND COMMANDE.Id_Statut= :filtreCategorie ;';
				}
				$queryGetCommande = $bdd->prepare($query);
				$queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
				if ($filtreCategorie!=0){
					$queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_STR);
				}
				$queryGetCommande->execute();
                $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
                $iterateurCommande=0;
				if(count($returnQueryGetCommande)==0 and ($filtreCategorie==0)){
                    echo "Aucune commande pour le moment";
					?>
					<br>
					<input type="button" onclick="window.location.href='index.php'" value="Découvrez nos producteurs">
					<?php
                }
                elseif(count($returnQueryGetCommande)==0){
                    echo "Aucune commande ne correspond à ces critères";
                }
                else{
                    while ($iterateurCommande<count($returnQueryGetCommande)){
						$Id_Commande = $returnQueryGetCommande[$iterateurCommande]["Id_Commande"];
						$Nom_Prod = $returnQueryGetCommande[$iterateurCommande]["Nom_Uti"];
						$Nom_Prod = mb_strtoupper($Nom_Prod);
						$Prenom_Prod = $returnQueryGetCommande[$iterateurCommande]["Prenom_Uti"];
						$Adr_Uti = $returnQueryGetCommande[$iterateurCommande]["Adr_Uti"];
						$Desc_Statut = $returnQueryGetCommande[$iterateurCommande]["Desc_Statut"];
						$Desc_Statut = mb_strtoupper($Desc_Statut);
						$Id_Statut = $returnQueryGetCommande[$iterateurCommande]["Id_Statut"];


						$total=0;
						$queryGetProduitCommande = $bdd->prepare('SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix FROM produits_commandes  WHERE Id_Commande = :Id_Commande;');
						$queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_STR);
						$queryGetProduitCommande->execute();
						$returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
						$iterateurProduit=0;
						$nbProduit=count($returnQueryGetProduitCommande);

						if ($nbProduit>0){
							echo '<div class="commande" >';
							echo "Commande n°" . $iterateurCommande+1 ." : Chez ".$Prenom_Prod.' '.$Nom_Prod.' - '.$Adr_Uti;
							echo '</br>';
							echo $Desc_Statut;
							echo '</br>';
							if ($Id_Statut!=3 and $Id_Statut!=4){
							echo '<form action="delete_commande.php" method="post">';
							echo '<input type="hidden" name="deleteValeur" value="'.$Id_Commande.'">';
							
							echo '<button type="submit">Annuler commande</button>';
							echo '</form>';
							}
						}

						while ($iterateurProduit<$nbProduit){
							$Nom_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
							$Qte_Produit_Commande=$returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
							$Nom_Unite_Prix=$returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
							$Prix_Produit_Unitaire=$returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];
							echo "- " . $Nom_Produit ." - ".$Qte_Produit_Commande.' '.$Nom_Unite_Prix.' * '.$Prix_Produit_Unitaire.'€ = '.intval($Prix_Produit_Unitaire)*intval($Qte_Produit_Commande).'€';
							echo "</br>";
							$total=$total+intval($Prix_Produit_Unitaire)*intval($Qte_Produit_Commande);
							$iterateurProduit++;
						}
                        $iterateurCommande++;
						if ($nbProduit>0){
							echo '<div class="aDroite">Total : '.$total.'€</div>';
							echo '</div> '; 
						}
                    }
                }
            ?>


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