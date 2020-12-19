#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - alta personatges</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php include 'exPHP_cap.php';
        $sentenciaSQL = "INSERT INTO personatges
 (alias,despesaMensual,dataCreacio,usuari,tipusPersonatge) 
	VALUES (:alias,:despesa,TO_DATE(:data,'DD/MM/YYYY'),:usuari, :tipus)";
        $comanda = oci_parse($connexio, $sentenciaSQL);
        oci_bind_by_name($comanda, ":alias", $_POST["alias"]);
        oci_bind_by_name($comanda, ":despesa", $_POST["despesa"]);
        oci_bind_by_name($comanda, ":data", $_POST["data"]);
        oci_bind_by_name($comanda, ":usuari", $_POST["usuari"]);
        oci_bind_by_name($comanda, ":tipus", $_POST["tipus"]);
        $exit = oci_execute($comanda);
        if ($exit) {
            echo "<p>Nou personatge " . $_POST['alias'] . " inserit</p>\n";
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
