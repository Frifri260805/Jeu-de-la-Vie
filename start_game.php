<?php

    $rows = $_POST['rows'];
    $cols = $_POST['cols'];
    $probability = $_POST['fill'];
    $delay = $_POST['delay'];

    header("Location: jeu.php?rows=$rows&cols=$cols&probability=$probability&delay=$delay");
    exit();
?>