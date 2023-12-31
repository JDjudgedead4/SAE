<?php


// Error handling with try-catch block
try {
    // Retrieve form data
    $pwd = $_POST['pwd'];
    $Mail_Uti = $_POST['mail'];

    // Start session
    session_start();

    // Handle password attempts
    if (!isset($_SESSION['test_pwd'])) {
        $_SESSION['test_pwd'] = 5;
    }

    // Database connection
    $utilisateur = "root";
    $serveur = "localhost";
    $motdepasse = "";
    $basededonnees = "sae3";

    // Connect to database
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

    // Check if user email exists
    $queryIdUti = $bdd->query('SELECT Id_Uti FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=\'' . $Mail_Uti . '\'');
    $returnQueryIdUti = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);

    // Handle invalid email
    if ($returnQueryIdUti == NULL) {
        unset($Id_Uti);
        header('Location: form_sign_in.php?mail=adresse mail invalide');
        exit();
    } else {

    // Extract user ID
    $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];
    echo $Id_Uti;
    echo '<Br>';
    
    // Verify password using stored procedure
    //echo('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\');');
    $query = $bdd->query('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\')');

    $test = $query->fetchAll(PDO::FETCH_ASSOC);

    // Handle password verification
    if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > 1) {
        if ($test[0][1] == 1 ) {
            echo "Le mot de passe correspond. vous allez etre redirigé vers la page d'accueil";
            $_SESSION['Mail_Uti'] = $Mail_Uti;
            $_SESSION['Id_Uti'] = $Id_Uti;
            echo $_SESSION['Id_Uti'];

            $bdd2 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $isProducteur = $bdd2->query('CALL isProducteur('.$Id_Uti.');');
            echo '<br>';
            var_dump($isProducteur);
            $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
            echo '<br>';
            var_dump($returnIsProducteur);
            $reponse=$returnIsProducteur[0]["result"];
            if ($reponse!=NULL){
                echo 'producteur';
                $_SESSION["isProd"]=true;
                var_dump($_SESSION);
            }
            header('Location: index.php');
        } else {
            $_SESSION['test_pwd']--;
            header('Location: form_sign_in.php?pwd=mauvais mot de passe il vous restes ' . $_SESSION['test_pwd'] . ' tentative(s)');
        }
    }else {
        header('Location: form_sign_in.php?pwd=vous avez épuisé toutes vos tentatives de connection');
    }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
    exit();
}