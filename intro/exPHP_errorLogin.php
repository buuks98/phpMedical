#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple HTML (Bases de Dades, Enginyeria Informàtica UdG)</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <p class="capcalera">Error de connexió</p>
        <?php
// emmagatzem usuari i password en una sessió (a bas.udg.edu hem de tenir una carpeta pròpia on desar les sessions)
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();

        echo "<p>No m'he pogut connectar a la Base de Dades.</p> <p>Repassa usuari/password. He rebut (" . $_SESSION['usuari'] . "/" . $_SESSION['password'] . ")</p>";
        ?>
        <p><a href="practica_php.html"> Torna a la pàgina de login</p>
    </body>
</html>
