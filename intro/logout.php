#!/usr/bin/php-cgi
<?php
$emmagatzemarSessions="/u/alum/u1946649/public_html/tmp";
ini_set('session.save_path', $emmagatzemarSessions);
session_start();

if(isset($_SESSION["logged"])){
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    unset($_SESSION["logged"]);
}

return header("Location: ./practica_php.html");
?>