#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple PHP/Oracle - consulta tipologies pròtesis</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php
        include 'exPHP_cap.php';        
        echo "<h2>Relació de totes les tipologies de pròtesis</h2>";
        $sentenciaSQL = "SELECT tp.codi,tp.descripcio,pm.nom || ' ' || pm.cognoms AS Responsable,tp.foto  
         FROM TipologiesProtesis tp JOIN PersonalMedic pm ON tp.expert=pm.codi ORDER BY descripcio"; // construim comanda SQL
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
            echo "  <td>" . ($fila['CODI'] != null ? htmlentities($fila['CODI'], ENT_QUOTES) : "&nbsp;") . "</td>\n";
            echo "  <td>" . ($fila['DESCRIPCIO'] != null ? htmlentities($fila['DESCRIPCIO'], ENT_QUOTES) : "&nbsp;") . "</td>\n";
            echo "  <td>" . ($fila['RESPONSABLE'] != null ? htmlentities($fila['RESPONSABLE'], ENT_QUOTES) : "&nbsp;") . "</td>\n";
            if ($fila['FOTO']==null){
              echo "<td><strong>?</strong></td>";
            } else {
              echo '<td align="center"><img src="data:image/png;base64,'.$fila['FOTO'].'"></td>';
            }
            echo "</tr>\n";
        }
        echo "</table>\n";
        oci_free_statement($comanda);
        include 'exPHP_peu.html';
        ?>
    </body>
</html>
