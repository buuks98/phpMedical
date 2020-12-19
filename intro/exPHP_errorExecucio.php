#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/ORACLE - comanda amb errors SQL </title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <p class="capcalera">Error amb l'execució de la comanda</p>
        <?php
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();

        echo "<p>Oracle informa del següent error:<p>";
        echo "<hr>\n";
        echo "<p>Codi error: <tt>" . $_SESSION['ErrorCodi'] . "</tt></p>\n";
        echo "<p>Missatge error: <tt>" . $_SESSION['ErrorMissatge'] . "</tt></p>\n";
        echo "<p>Sentència que ha provocat aquest error: </p>\n<hr>\n";
        echo "<p><tt>" . $_SESSION['ErrorSentencia'] . "</tt></p>\n";
        echo "<p><tt>";
        for ($i = 1; $i <= $_SESSION['ErrorOffset']; $i++) {
            echo ($i % 2 ? "&nbsp;" : " ");
        }
        echo "^</tt></p>\n";
        echo "<tt><p>";
        for ($i = 5; $i <= $_SESSION['ErrorOffset']; $i++) {
            echo ($i % 2 ? "&nbsp;" : " ");
        }
        echo "ERROR</tt></p>\n";
        echo "<hr>\n";
        echo "<p>Posició error: <tt>" . $_SESSION['ErrorOffset'] . "</tt></p>\n";
        include 'exPHP_peu.html';
        ?>
    </body>
</html>
