#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sentenciaTaula = "Select p.nom, p.cognoms, v.data, f.nom, f.cognoms, v.lloc from visites v join familiars f on acompanyant = f.codi 
    join pacients p on pacient = p.codi where pacient like'" . $_POST['pacient'] . "%'";
    if ($_POST['pacient'] == "TOTS") {
        $sentenciaTaula = "Select p.nom, p.cognoms, v.data, f.nom, f.cognoms, v.lloc from visites v join familiars f on acompanyant = f.codi join pacients p on pacient = p.codi";
    }

    $comandaTaula = oci_parse($conn, $sentenciaTaula);
    oci_execute($comandaTaula);
}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Llistat visites</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut" style="width: 80%!important;">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Llistat visites</h1>
        </div>
        <?php
        if(isset($msg)){
            echo "<p class='error'>" . $msg . "</p>";
        }
        ?>
        <div class="opcions center">
            <form class="center-block" method="POST">
                <div class="espai-dades">
                    <label for="pacient">Pacient:</label>
                    <select name="pacient">
                        <?php
                        $tipologies = 'SELECT codi, nom, cognoms FROM pacients';
                        $comanda = oci_parse($conn, $tipologies);
                        oci_execute($comanda);
                        while (($fila = oci_fetch_array($comanda)) != false) {
                            echo "<option "  . ($fila['0'] == $_POST["pacient"] ? "selected='selected'" : "") . "value=\"" . $fila['0'] . "\">" . $fila['1'] . " " . $fila['2'] . "</option>\n";
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
                <th>Pacient</th>
                <th>Data</th>
                <th>Acompanyant</th>
                <th>Lloc</th>
            </tr>
            <?php
            if(isset($comandaTaula)){
                while(($row = oci_fetch_array($comandaTaula)) != false) {
                    echo '<tr>';
                    echo '<td>' . $row['0'] . " " . $row['1']  . '</td>';
                    echo '<td>' . $row['2'] . '</td>';
                    echo '<td>' . $row['3'] . " " . $row['4']  . '</td>';
                    echo '<td>' . $row['5'] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>


