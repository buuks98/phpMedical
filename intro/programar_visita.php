#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

$pacients = 'SELECT codi, nom, cognoms FROM pacients';
$comanda = oci_parse($conn, $pacients);
oci_execute($comanda);

$sentenciaMetges = "SELECT codi, nom, cognoms FROM personalmedic";
$comandaMetges = oci_parse($conn, $sentenciaMetges);
oci_execute($comandaMetges);

if (isset($_GET["acompanyant"])) {
    $sentenciaGetAcompanyantsDelPacient = "SELECT familiar, nom, cognoms from acompanyants join familiars f on familiar = f.codi where pacient like '". $_GET["acompanyant"]."%'";
    $comanda_acompanyant = oci_parse($conn, $sentenciaGetAcompanyantsDelPacient);
    oci_execute($comanda_acompanyant);
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $sentenciaCodi = "SELECT codi from visites order by codi desc";
    $comandaCodi = oci_parse($conn, $sentenciaCodi);
    oci_execute($comandaCodi);
    $row = oci_fetch_array($comandaCodi, OCI_ASSOC + OCI_RETURN_NULLS);
    $higherNumber = '';
    foreach ($row as $columna) {
        $higherNumber = intval($columna);
    }

    $finalCode = $higherNumber + 1;

    $sentenciaNOrdre = "SELECT COUNT(*) from visites where pacient like '". $_POST['pacient']."%'";
    $comandaNOrdre = oci_parse($conn, $sentenciaNOrdre);
    oci_execute($comandaNOrdre);
    $rowA = oci_fetch_array($comandaNOrdre, OCI_ASSOC + OCI_RETURN_NULLS);
    $higherNumberA = '';
    foreach ($rowA as $columnaA) {
        $higherNumberA = intval($columnaA);
    }

    $finalOrder = $higherNumberA + 1;

    $sentenciaSQL = "INSERT INTO Visites
    (codi, ordre, pacient, data, lloc, acompanyant) 
	VALUES (:codi, :ordre, :pacient, TO_DATE(:data,'YYYY-MM-DD'),:lloc, :acompanyant)";

    $insertVisitaSentencia = "INSERT INTO visites (codi, ordre, pacient, data, lloc, acompanyant) VALUES 
    (:codi, :ordre, :pacient, TO_DATE(:data, 'YYYY-MM-DD'), :lloc, :acompanyant)";
    $insertVisitaComanda = oci_parse($conn, $insertVisitaSentencia);
    oci_bind_by_name($insertVisitaComanda, ":codi", $finalCode);
    oci_bind_by_name($insertVisitaComanda, ":pacient", $_POST['pacient']);
    oci_bind_by_name($insertVisitaComanda, ":ordre", $finalOrder);
    oci_bind_by_name($insertVisitaComanda, ":data", $_POST['data']);
    oci_bind_by_name($insertVisitaComanda, ":lloc", $_POST['lloc']);
    oci_bind_by_name($insertVisitaComanda, ":acompanyant", $_POST['acompanyant']);
    $exit1 = oci_execute($insertVisitaComanda);


    $insertProfessionalVisitaSentencia = "INSERT INTO professionalsvisita (personal, visita) VALUES (:personal, :visita)";
    $insertProfessionalVisitaComanda = oci_parse($conn, $insertProfessionalVisitaSentencia);
    oci_bind_by_name($insertProfessionalVisitaComanda, ":personal", $_POST['metge']);
    oci_bind_by_name($insertProfessionalVisitaComanda, ":visita", $finalCode);
    $exit2 = oci_execute($insertProfessionalVisitaComanda);
    $msg = "";

    if(!$exit1){
        $msg = "No s'ha pogut guardar correctament la visita";
    } else if(!$exit2){
        $msg = "No s'ha pogut guardar correctament el professional visita";
    } else {
        if(!empty($_POST["pacient"])){
            $msg = "<p>Nova visita " . $finalCode . " inserida</p>\n";
            $msg .= "<p>Amb ordre " . $finalOrder . "</p>\n";
        }
    }
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
                    <label for="metge">Metge que m'atendrà:</label>
                    <select name="metge">
                        <?php
                            while(($row = oci_fetch_array($comandaMetges)) != false) {
                                echo "<option value=\"" . $row['0'] . "\">" . $row['1'] . " ". $row['2'] . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="espai-dades">
                    <label for="data">Data de la visita</label>
                    <input type="date" name="data" />
                </div>
                <div class="espai-dades">
                    <label for="lloc">Lloc de la visita</label>
                    <input type="text" name="lloc" />
                </div>
                <div class="center">
                    <input type="submit" class="center-block botright" value="Guardar visita"/>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
