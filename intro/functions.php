<?php

/* Inicia per treballar amb sessions */
function start_session(){
    $emmagatzemarSessions = exec("pwd") . "/tmp";
    ini_set('session.save_path', $emmagatzemarSessions);
    session_start();
}

/* Comprova si l'usuari esta loggejat
 * si no ho esta el fa fora
 */
function check_login(){
    if(!isset($_SESSION["username"])){
        return header("Location: ./practica_php.html");
    }
}

function connect(){
    return oci_connect($_SESSION["username"], $_SESSION["password"], 'oracleps');
}

?>
