<?php
session_start();
$newurl = 'Location: ' . $_SESSION["account"]["AccountLink"];
unset($_SESSION["account"]);
header($newurl);
//exit();
?>
