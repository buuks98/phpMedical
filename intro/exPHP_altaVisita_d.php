#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
      <title>Exemple molt simple PHP/Oracle - alta visites desplegable codis, codi automàtic</title>
      <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
    <?php include 'exPHP_cap.php'; ?>
      <form action="exPHP_altaVisita_OCI_BIND_BD_b.php" method="post">
      <label>Pacient (codi):</label><select name="pacient">
    <?php $usuaris = 'SELECT codi, nom || \' \' || cognoms as "Nom" FROM Pacients order by "Nom"';
      $tab="        ";
      $comanda = oci_parse($connexio, $usuaris);
      oci_execute($comanda);
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
          echo $tab.$tab."<option value=\"" . $fila['CODI'] . "\">" . $fila['Nom'] . "</option>\n";
      }
      unset($fila);
      echo $tab."</select><br>\n";
?>      
      <label>Data: </label><input type = "date" name="data"><br>
      <label>Lloc:</label><input type="text" name="lloc"><br>      
      <label>Acompanyant (codi):</label><select name="acompanyant"><br>      
<?php
      $tipus = 'SELECT codi, nom || \' \' || cognoms as "Nom" FROM Familiars order by "Nom"';
      $comanda = oci_parse($connexio, $tipus);
      oci_execute($comanda);
      while (($fila = oci_fetch_array($comanda, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
          echo $tab.$tab."<option value=\"" . $fila['CODI'] . "\">" . $fila['Nom'] . "</option>\n";
      }
    ?>
      </select><br>
      <input type = "submit" value="Inserir">
      </form>
<?php
include 'exPHP_peu.html';
?>
</body>
</html>
