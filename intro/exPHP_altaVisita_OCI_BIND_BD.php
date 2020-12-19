#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - alta visita amb OCI_BIND</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php include 'exPHP_cap.php';
        $sentenciaSQL = "INSERT INTO Visites
    (codi, ordre, pacient, data, lloc, acompanyant) 
	VALUES (:codi, :ordre, :pacient, TO_DATE(:data,'YYYY-MM-DD'),:lloc, :acompanyant)";
        $comanda = oci_parse($connexio, $sentenciaSQL);
        oci_bind_by_name($comanda, ":codi", $_POST["codi"]);
        oci_bind_by_name($comanda, ":ordre", $_POST["ordre"]);
        oci_bind_by_name($comanda, ":pacient", $_POST["pacient"]);
        oci_bind_by_name($comanda, ":data", $_POST["data"]);
        oci_bind_by_name($comanda, ":lloc", $_POST["lloc"]);
        oci_bind_by_name($comanda, ":acompanyant", $_POST["acompanyant"]);
        $exit = oci_execute($comanda);
        if ($exit) {
            echo "<p>Inserida nova visita amb codi " . $_POST['codi'] . "</p>\n";
        } else {
            $error = oci_error($comanda);
            $_SESSION['ErrorSentencia'] = $error['sqltext'];
            $_SESSION['ErrorCodi'] = $error[code];
            $_SESSION['ErrorMissatge'] = $error['message'];
            $_SESSION['ErrorOffset'] = $error['offset'];
            header('Location: exPHP_errorExecucio.php');
        }
        include 'exPHP_peu.html'; ?>
    </body>
</html>
