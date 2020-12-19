#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/ORACLE - comanda amb errors SQL </title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <p class="capcalera">Error amb el Parser de la comanda</p>
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();

//print_r($_SESSION);
        echo "<p>Oracle informa d'un error al passar pel parser la següent comanda:<p>";
        echo "<hr>\n";
        echo "<p><tt>" . $_SESSION['ErrorParser'] . "</tt></p>\n";
        echo "<hr>";
        include 'exPHP_peu.html';
        ?>
    </body>
</html>
