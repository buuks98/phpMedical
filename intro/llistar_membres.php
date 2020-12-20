#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){ // Comprovem si es una solicitud POST
    $sentenciaTaula = "SELECT dni, genere, nom, cognoms, adreca, codipostal, poblacio, email, telefon, tipologiaprofessional, sou FROM personalmedic WHERE tipologiaprofessional LIKE '".$_POST['tipologia']."%'";
    if($_POST['tipologia'] == "TOTS"){
        $sentenciaTaula = "SELECT dni, genere, nom, cognoms, adreca, codipostal, poblacio, email, telefon, tipologiaprofessional, sou FROM personalmedic";
    }
    $comandaTaula = oci_parse($conn, $sentenciaTaula);
    oci_execute($comandaTaula);
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nou personal mèdic</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut" style="width: 80%!important;">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Llistat membres del personal</h1>
        </div>
        <?php
        if(isset($msg)){
            echo "<p class='error'>" . $msg . "</p>";
        }
        ?>
        <div class="opcions center">
            <form class="center-block" method="POST">
                <div class="espai-dades">
                    <label for="tipologia">Tipologia professional:</label>
                    <select name="tipologia">
                        <?php
                        $tipologies = 'SELECT codi, nomGeneric FROM tipologiespersonal';
                        $comanda = oci_parse($conn, $tipologies);
                        oci_execute($comanda);
                        while (($fila = oci_fetch_array($comanda)) != false) {
                            echo "<option value=\"" . $fila['0'] . "\">" . strtoupper($fila['1']) . "</option>\n";
                        }
                        ?>
                        <option value="TOTS">TOTS</option>
                    </select>
                    <input type="submit" class="center-block botright" value="Veure llistat"/>
                </div>
            </form>
        </div>
        <table border="1" style="margin: 0 auto 0 auto; display=block">
            <tr>
                <th>DNI</th>
                <th>Genere</th>
                <th>Nom</th>
                <th>Cognoms</th>
                <th>Adreca</th>
                <th>Codi Postal</th>
                <th>Poblacio</th>
                <th>Email</th>
                <th>Telefon</th>
                <th>Tipus</th>
                <th>Sou</th>
            </tr>
            <?php
            if(isset($comandaTaula)){
                while(($row = oci_fetch_array($comandaTaula)) != false) {
                    echo '<tr>';
                    echo '<td>' . $row['DNI'] . '</td>';
                    if($row['GENERE'] == 'H'){
                        echo '<td>Home</td>';
                    } else {
                        echo '<td>Dona</td>';
                    }
                    echo '<td>' . $row['NOM'] . '</td>';
                    echo '<td>' . $row['COGNOMS'] . '</td>';
                    echo '<td>' . $row['ADRECA'] . '</td>';
                    echo '<td>' . $row['CODIPOSTAL'] . '</td>';
                    echo '<td>' . $row['POBLACIO'] . '</td>';
                    echo '<td>' . $row['EMAIL'] . '</td>';
                    echo '<td>' . $row['TELEFON'] . '</td>';
                    echo '<td>' . $row['TIPOLOGIAPROFESSIONAL'] . '</td>';
                    echo '<td>' . $row['SOU'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
<?php
if(isset($comandaTaula)){
    oci_free_statement($comandaTaula);
}
oci_free_statement($comanda);
oci_close($conn);
?>
