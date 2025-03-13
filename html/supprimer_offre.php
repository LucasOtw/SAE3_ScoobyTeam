<?php

ob_start();
session_start();

if(isset($_POST['uneOffre'])){
    $uneOffre = unserialize($_POST['uneOffre']);

    echo "<pre>";
    var_dump($uneOffre);
    echo "</pre>";
} else {
    // sinon il n'a rien à faire ici
    header('location: mes_offres.php');
    exit;
}

?>
