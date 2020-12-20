#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

$pacients = 'SELECT codi, nom, cognoms FROM pacients';
$comanda = oci_parse($conn, $pacients);
oci_execute($comanda);

if (isset($_GET["acompanyant"])) {
    echo $_GET["acompanyant"];
    $sentenciaGetAcompanyantsDelPacient = "SELECT familiar, nom, cognoms from acompanyants join familiars f on familiar = f.codi where pacient like '". $_GET["acompanyant"]."%'";
    $comanda_acompanyant = oci_parse($conn, $sentenciaGetAcompanyantsDelPacient);
    oci_execute($comanda_acompanyant);
}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Programar una visita</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Programar una visita</h1>
        </div>
        <?php
        if(isset($msg)){
            echo "<p class='error'>" . $msg . "</p>";
        }
        ?>
        <div class="opcions center">
            <form class="center-block" method="post">
                <div class="espai-dades">
                    <label for="pacient">Pacient</label>
                    <select name="pacient" onchange="window.location = window.location.pathname + '?acompanyant=' + this.value">
                        <?php
                        while (($fila = oci_fetch_array($comanda)) != false) {
                            echo "<option " . ($fila['0'] == $_GET["acompanyant"] ? "selected='selected'" : "") . " value=\"" . $fila['0']  . "\">"  . $fila['1'] . " " . $fila['2'] . "</option>\n";
                        }
                        ?>
                    </select>
                </div>
                <div class="espai-dades">
                    <label for="acompanyant">Acompanyant:</label>
                    <select name="acompanyant">
                        <?php
                        if(isset($comanda_acompanyant)){
                            while(($row = oci_fetch_array($comanda_acompanyant)) != false) {
                                echo "<option value=\"" . $row['0'] . "\">" . $row['1'] . " ". $row['2'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="espai-dades">
                    <label for="data">Data de la visita</label>
                    <input type="date" name="data" />
                </div>
                <div class="espai-dades">
                    <label for="hora">Hora de la visita</label>
                    <input type="time" min="08:00" max="20:00" name="hora" /> <small>Les hores de visita comencen a les 8:00 i acaben a les 20:00</small>
                </div>
                <div class="center">
                    <input type="submit" class="center-block botright" value="Guardar"/>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
