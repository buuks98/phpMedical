#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - consulta visites</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php
        include 'exPHP_cap.php';        
        echo "<h2>Consulta visites</h2>";
        $sentenciaSQL = "SELECT v.codi, v.ordre, p.nom||' '||p.cognoms AS pacient, 
         TO_CHAR(v.data,'DD/MM/YYYY') as data, v.lloc, f.nom||' '|| f.cognoms as acompanyant
         FROM Visites v JOIN Pacients p ON v.pacient=p.codi JOIN Familiars f ON v.acompanyant=f.codi
         ORDER BY pacient,ordre"; // construim comanda SQL
//        echo $sentenciaSQL."<br>\n";   
        $comanda = oci_parse($connexio, $sentenciaSQL); // traduim comanda SQL
        if ($comanda == false) {
            $_SESSION['ErrorParser'] = $sentenciaSQL;
            header('Location: exPHP_errorParser.php');
        }

        $exit = oci_execute($comanda); // executem la comanda SQL
        if (!$exit) {
            $error = oci_error($comanda);
            $_SESSION['ErrorSentencia'] = $error['sqltext'];
            $_SESSION['ErrorCodi'] = $error['code'];
            $_SESSION['ErrorMissatge'] = $error['message'];
            $_SESSION['ErrorOffset'] = $error['offset'];
            header('Location: exPHP_errorExecucio.php');
        }

echo "<table>\n";
// primer posem les capcaleres de les columnes...
        $columnes = oci_num_fields($comanda); // compta quantes columnes retorna la consulta
        echo "<tr>\n";
        for ($i = 1; $i <= $columnes; $i++) {
            echo "<th>" . htmlentities(oci_field_name($comanda, $i), ENT_QUOTES) . "</th>\n";
        }
        echo "</tr>\n";

        while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            echo "<tr>\n";
            foreach ($fila as $columna) {
                echo "  <td>" . ($columna !== null ? htmlentities($columna, ENT_QUOTES) : "&nbsp;") . "</td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
        oci_free_statement($comanda);
        include 'exPHP_peu.html';
        ?>
    </body>
</html>
