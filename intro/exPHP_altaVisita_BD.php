#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - alta visites BD</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php
        include 'exPHP_cap.php';
        $sentenciaSQL = "INSERT INTO Visites
          (codi, ordre, pacient, data, lloc, acompanyant) 
          VALUES (" . $_POST['codi'] . "," . $_POST['ordre'] . ", '" . $_POST['pacient']. 
            "', TO_DATE('" . $_POST['data'] . "','YYYY-MM-DD'),'" . $_POST['lloc'] . 
             "','" . $_POST['acompanyant'] . "')";
//        echo "Només per debug: <tt>" . $sentenciaSQL . "</tt><br>\n";
        $comanda = oci_parse($connexio, $sentenciaSQL);
        $exit = oci_execute($comanda);
        if ($exit) {
            echo "<p>Nova visita amb codi " . $_POST['codi'] . " inserida</p>\n";
        } else {
            $error = oci_error($comanda);
            $_SESSION['ErrorSentencia'] = $error['sqltext'];
            $_SESSION['ErrorCodi'] = $error[code];
            $_SESSION['ErrorMissatge'] = $error['message'];
            $_SESSION['ErrorOffset'] = $error['offset'];
            header('Location: exPHP_errorExecucio.php');
        }
        include 'exPHP_peu.html';
        ?>
    </body>
</html>
