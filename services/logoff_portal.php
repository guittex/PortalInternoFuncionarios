<?php
session_start();
if (!empty($_SESSION["timer_portal"])){
    unset($_SESSION["timer_portal"]);
    header('Location: ../login.php');    
}

?>

