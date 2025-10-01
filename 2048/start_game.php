<?php

    $rows = $_POST['rows'];
    $cols = $_POST['cols'];


    header("Location: jeu.php?rows=$rows&cols=$cols");
    exit();
?>