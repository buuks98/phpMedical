#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();

// Comprovem que coincideix l'usuari i el password
if(isset($_POST["username"]) && isset($_POST["password"])){
    $password = $_POST["password"];
    $usuari = $_POST["username"];
    echo $usuari;
    echo $password;
    $conn = oci_connect($usuari, $password, 'ORCLCDB');

    if($conn){
        $_SESSION["password"] = $password;
        $_SESSION["username"] = $usuari;
        $_SESSION["logged"] = true;

        // Login correcte el portem a la pàgina principal
        header("Location: ./home.php");
    } else {

        // Login incorrecte el portem fora
        header("Location: ./practica_php.html");
    }
}
?>