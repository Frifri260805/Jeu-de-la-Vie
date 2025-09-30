<?php

    $rows = $_POST['rows'];
    $cols = $_POST['cols'];
    $probability = $_POST['fill'];
    $delay = $_POST['delay'];
    $Choix = $_POST['choix'];

    header("Location: jeu.php?rows=$rows&cols=$cols&probability=$probability&delay=$delay&Choix=$Choix");
    exit();
?>