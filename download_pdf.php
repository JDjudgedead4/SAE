<?php

function dbConnect(){
    $host = 'localhost';
    $dbname = 'inf2pj_02';
    $user = 'inf2pj02';
    $password = 'ahV4saerae';
    return new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
}

$bdd = dbConnect();
$Id_Commande = $_POST["idCommande"];

$queryGetCommande = $bdd->query(('SELECT Desc_Statut, COMMANDE.Id_Prod, COMMANDE.Id_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Prenom_Uti, COMMANDE.Id_Statut, UTILISATEUR.Adr_Uti, UTILISATEUR.Mail_Uti  FROM COMMANDE INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut INNER JOIN UTILISATEUR ON COMMANDE.Id_Uti=UTILISATEUR.Id_Uti WHERE COMMANDE.Id_Commande='.$Id_Commande.';'));
$returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);

$Id_Prod = $returnQueryGetCommande[0]["Id_Prod"];
$Desc_Statut = $returnQueryGetCommande[0]["Desc_Statut"];
$Nom_Uti = $returnQueryGetCommande[0]["Nom_Uti"];
$Nom_Uti = mb_strtoupper($Nom_Uti);
$Prenom_Uti = $returnQueryGetCommande[0]["Prenom_Uti"];
$Id_Statut = $returnQueryGetCommande[0]["Id_Statut"];
$Mail_Uti = $returnQueryGetCommande[0]["Mail_Uti"];
$Adr_Uti = $returnQueryGetCommande[0]["Adr_Uti"];

$bdd = dbConnect();
$queryGetProducteur = $bdd->query(('SELECT Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti, Prof_Prod FROM info_producteur WHERE Id_Prod='.$Id_Prod.';'));
$returnQueryGetProducteur = $queryGetProducteur->fetchAll(PDO::FETCH_ASSOC);

$Nom_Prod = $returnQueryGetProducteur[0]["Nom_Uti"];
$Nom_Prod = mb_strtoupper($Nom_Prod);
$Prenom_Prod = $returnQueryGetProducteur[0]["Prenom_Uti"];
$Adr_Prod = $returnQueryGetProducteur[0]["Adr_Uti"];
$Prof_Prod = $returnQueryGetProducteur[0]["Prof_Prod"];

require('fpdf/fpdf.php'); // Assurez-vous d'ajuster le chemin vers le fichier FPDF

class MonPDF extends FPDF
{
    // En-tête
    function Header()
    {
        // Titre
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'Bon de commande', 0, 1, 'C');

        // Ligne de séparation
        $this->Cell(0, 0, '', 'T');
        $this->Ln(5); // Saut de ligne réduit
    }

    // Pied de page
    function Footer()
    {
        // Numéro de page
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Créer une instance de MonPDF
$pdf = new MonPDF();
$pdf->AddPage();

// Ajouter les valeurs
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 5, $Nom_Prod.$Prenom_Prod, 0, 1);
$pdf->Cell(0, 5, $Prof_Prod, 0, 1);
$pdf->Cell(0, 5, $Adr_Prod, 0, 1);

// Informations sur le client
$pdf->Cell(0, 5, $Nom_Uti.$Prenom_Uti, 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(0, 5, $Adr_Uti, 0, 0, 'R');
$pdf->Ln(5); // Sauts de ligne réduits

// Informations sur la commande
$pdf->Cell(0, 5, 'COMMANDE XXX :', 0, 1);

// Tableau avec des produits récupérés depuis la base de données
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Produit', 1);
$pdf->Cell(40, 8, 'Prix Unitaire', 1);
$pdf->Cell(30, 8, 'Quantité', 1);
$pdf->Cell(40, 8, 'Prix', 1);
$pdf->Ln();

// Tableau avec des produits récupérés depuis la base de données
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Produit', 1);
$pdf->Cell(40, 8, 'Prix Unitaire', 1);
$pdf->Cell(30, 8, 'Quantité', 1);
$pdf->Cell(40, 8, 'Prix', 1);
$pdf->Ln();

// Tableau avec des produits récupérés depuis la base de données
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, 'Produit', 1);
$pdf->Cell(40, 8, 'Prix Unitaire', 1);
$pdf->Cell(30, 8, 'Quantité', 1);
$pdf->Cell(40, 8, 'Prix', 1);
$pdf->Ln();

// Récupération des produits depuis la base de données
$queryGetProduitCommande = $bdd->query(('SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix FROM produits_commandes  WHERE Id_Commande =' . $Id_Commande . ';'));
$returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
$iterateurProduit = 0;
$nbProduit = count($returnQueryGetProduitCommande);

$pdf->SetFont('Arial', '', 12); // Ajout de cette ligne pour réinitialiser la police après le titre du tableau

while ($iterateurProduit < $nbProduit) {
    $Nom_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
    $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
    $Nom_Unite_Prix = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
    $Prix_Produit_Unitaire = $returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];

    // Ajout des produits récupérés dans le tableau
    $pdf->Cell(40, 8, $Nom_Produit, 1);
    $pdf->Cell(40, 8, '$' . number_format($Prix_Produit_Unitaire, 2, '.', ''), 1);
    $pdf->Cell(30, 8, $Qte_Produit_Commande, 1);
    $pdf->Cell(40, 8, '$' . number_format($Prix_Produit_Unitaire * $Qte_Produit_Commande, 2, '.', ''), 1);
    $pdf->Ln();

    // Calcul du total
    $total = $total + intval($Prix_Produit_Unitaire) * intval($Qte_Produit_Commande);

    $iterateurProduit++;
}

$pdf->Ln(5); // Saut de ligne réduit

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(110, 8, 'TOTAL', 1);
$pdf->Cell(40, 8, '$' . number_format($total, 2, '.', ''), 1);
$pdf->Ln(); // Saut de ligne

// Impression
$pdf->Ln(5); // Saut de ligne réduit
$pdf->Cell(0, 5, 'IMPRESSION', 0, 1);

// Enregistrer le PDF dans un fichier temporaire
$nom_fichier = tempnam(sys_get_temp_dir(), 'pdf');
$pdf->Output($nom_fichier, 'F');

// Envoi des en-têtes pour le téléchargement
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="exemple.pdf"');
header('Content-Length: ' . filesize($nom_fichier));

// Envoyer le contenu du fichier
readfile($nom_fichier);

// Supprimer le fichier temporaire
unlink($nom_fichier);
?>