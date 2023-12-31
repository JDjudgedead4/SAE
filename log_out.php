<?php

// Démarrez la session
session_start();

// Détruisez toutes les variables de session
$_SESSION = array();

// Effacez le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruisez la session
session_destroy();

header('Location: index.php');
?>