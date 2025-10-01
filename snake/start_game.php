<?php

    $Choix = $_POST['choix'];

    header("Location: jeu.php?Choix=$Choix");
    exit();
?>