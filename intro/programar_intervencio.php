#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

$pacients = "SELECT  pacients.codi, pacients.nom, pacients.cognoms FROM pacients, visites where pacients.codi = visites.pacient GROUP BY pacients.codi, visites.pacient, pacients.nom, pacients.cognoms"; //seleccionem només els pacients que tinguin visites
$comanda = oci_parse($conn, $pacients);
oci_execute($comanda);

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $sentenciaVisita = "SELECT personal, pacient, acompanyant, visita FROM visites v JOIN professionalsvisita pv ON v.codi = pv.visita 
    JOIN personalmedic pm ON pv.personal = pm.codi WHERE pacient LIKE '". $_POST['pacient']."%' ORDER BY data desc, dataAlta asc";
    $comandaVisita = oci_parse($conn, $sentenciaVisita);
    oci_execute($comandaVisita);

    $row = oci_fetch_array($comandaVisita);
    $personal = $row['0'];
    $pacient = $row['1'];
    $acompanyant = $row['2'];
    $visita = $row['3'];


    $sentenciaInsertIntervencio = "INSERT INTO intervencions (codi, responsable, visitaDecisio, pacient, protesi, acompanyant, data) VALUES 
    (:codi, :responsable, :visitaDecisio, :pacient, :protesi, :acompanyant, TO_DATE(:data, 'YYYY-MM-DD'))";
    $insertIntervencioComanda = oci_parse($conn, $sentenciaInsertIntervencio);

    $sentenciaCodiIntervencio = "SELECT codi from intervencions where codi like 'M%' order by codi desc"; //posem codi amb m pk es el que em surt de la polla
    $comandaCodiIntervencio = oci_parse($conn,$sentenciaCodiIntervencio);
    oci_execute($comandaCodiIntervencio);

    $fila = oci_fetch_array($comandaCodiIntervencio);
    $codi = intval(substr($fila['0'], 1, 3)) + 1;
    $finalCodi = "M".str_pad($codi, 3, "0", STR_PAD_LEFT);

    oci_bind_by_name($insertIntervencioComanda, ":codi", $finalCodi);
    oci_bind_by_name($insertIntervencioComanda, ":responsable", $personal);
    oci_bind_by_name($insertIntervencioComanda, ":visitaDecisio", $visita);
    oci_bind_by_name($insertIntervencioComanda, ":pacient", $pacient);
    $protesi = "M62437"; //no la deixem en blanc pk es fk
    oci_bind_by_name($insertIntervencioComanda, ":protesi", $protesi);
    oci_bind_by_name($insertIntervencioComanda, ":acompanyant", $acompanyant);
    oci_bind_by_name($insertIntervencioComanda, ":data", $_POST['data']);
    $exit = oci_execute($insertIntervencioComanda);

    $insertProfessionalIntervencio = "INSERT INTO professionalsintervencio (personal, intervencio) VALUES (:personal, :intervencio)";
    $comandaProfessionalIntervencio = oci_parse($conn,$insertProfessionalIntervencio);
    oci_bind_by_name($comandaProfessionalIntervencio, ":personal", $personal);
    oci_bind_by_name($comandaProfessionalIntervencio, ":intervencio", $finalCodi);
    $exit1 = oci_execute($comandaProfessionalIntervencio);

    if(!$exit){
        $msg = "No s'ha pogut guardar correctament la intervenció";
    } else if(!$exit1){
        $msg = "No s'ha pogut guardar correctament el professional intervencio";
    } else {
        if(!empty($_POST["pacient"])){
            $msg = "<p>Nova intervenció " . $finalCodi . " inserida</p>\n";
        }
    }
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Programar una intervenció</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Programar una intervenció</h1>
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
                    <select name="pacient">
                        <?php
                        while (($fila = oci_fetch_array($comanda)) != false) {
                            echo "<option " . ($fila['0'] == $_POST["pacient"] ? "selected='selected'" : "") . " value=\"" . $fila['0']  . "\">"  . $fila['1'] . " " . $fila['2'] . "</option>\n";
                        }
                        ?>
                    </select>
                </div>
                <div class="espai-dades">
                    <label for="data">Data de la intervenció</label>
                    <input type="date" name="data" />
                </div>
                <div class="espai-dades">
                    <label for="hora">Hora de la intervenció</label>
                    <input type="time" name="hora" />
                </div>
                <div class="center">
                    <input type="submit" class="center-block botright" value="Guardar intervenció"/>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
