<?php
    session_start();
    $rol = $_SESSION['rol'];
    $_SESSION = array();
    unset($_SESSION);
    session_destroy();

    header('Location: index2.php?i=1');

?>