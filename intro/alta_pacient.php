#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $sentenciaCodiPacient = "SELECT codi FROM pacients order by codi desc";
    $comandaCodiPacient = oci_parse($conn, $sentenciaCodiPacient);
    oci_execute($comandaCodiPacient);
    $row = oci_fetch_array($comandaCodiPacient, OCI_ASSOC + OCI_RETURN_NULLS);
    $higherNumber = '';
    foreach ($row as $columna) {
        $higherNumber = intval(substr($columna, 1, 4));
    }

    $codiPacient = $higherNumber + 1;

    $finalCodePacient = "P".str_pad($codiPacient, 4, "0", STR_PAD_LEFT);

    //insert pacient
    $sentenciaInsertPacient = "INSERT INTO pacients (codi, nom, dni, 
    genere, cognoms, poblacio, email, telefon, dataNaixement)
    VALUES (:codi, :nom, :dni, :genere, :cognoms, :poblacio, :email, :telefon, TO_DATE(:dataNaixement, 'YYYY-MM-DD'))";
    $insertPacientComand = oci_parse($conn, $sentenciaInsertPacient);
    oci_bind_by_name($insertPacientComand, ":codi", $finalCodePacient);
    oci_bind_by_name($insertPacientComand, ":nom", $_POST['nom']);
    oci_bind_by_name($insertPacientComand, ":dni", $_POST['dni']);
    oci_bind_by_name($insertPacientComand, ":genere", $_POST['genere']);
    oci_bind_by_name($insertPacientComand, ":cognoms", $_POST['cognoms']);
    oci_bind_by_name($insertPacientComand, ":poblacio", $_POST['poblacio']);
    oci_bind_by_name($insertPacientComand, ":email", $_POST['email']);
    oci_bind_by_name($insertPacientComand, ":telefon", $_POST['telefon']);
    oci_bind_by_name($insertPacientComand, ":dataNaixement", $_POST['data']);
    $exit1 = oci_execute($insertPacientComand);


    $sentenciaCodiAcompanyant = "SELECT codi FROM familiars order by codi desc";
    $comandaCodiAcompanyant = oci_parse($conn, $sentenciaCodiAcompanyant);
    oci_execute($comandaCodiAcompanyant);
    $rowA = oci_fetch_array($comandaCodiAcompanyant, OCI_ASSOC + OCI_RETURN_NULLS);
    $higherNumberAcompanyant = '';
    foreach ($rowA as $columnaA) {
        $higherNumberAcompanyant = intval(substr($columnaA, 1, 4));
    }

    $codiAcompanyant = $higherNumberAcompanyant + 1;

    $finalCodeAcompanyant = "A".str_pad($codiAcompanyant, 4, "0", STR_PAD_LEFT);

    //insert a taula familiars
    $sentenciaInsertAcompanyant = "INSERT INTO familiars (codi, nom, dni, cognoms, email, telefon)
    VALUES (:codi, :nom, :dni, :cognoms, :email, :telefon)";
    $insertAcompanyantComand = oci_parse($conn, $sentenciaInsertAcompanyant);
    oci_bind_by_name($insertAcompanyantComand, ":codi", $finalCodeAcompanyant);
    oci_bind_by_name($insertAcompanyantComand, ":nom", $_POST['nomAcompanyant']);
    oci_bind_by_name($insertAcompanyantComand, ":dni", $_POST['dniAcompanyant']);
    oci_bind_by_name($insertAcompanyantComand, ":cognoms", $_POST['cognomsAcompanyant']);
    oci_bind_by_name($insertAcompanyantComand, ":telefon", $_POST['telefonAcompanyant']);
    oci_bind_by_name($insertAcompanyantComand, ":email", $_POST['emailAcompanyant']);
    $exit2 = oci_execute($insertAcompanyantComand);

    //insert a taula acompanyants
    $sentenciaInsertTaulaAcompanyant = "INSERT INTO acompanyants (pacient, familiar, relacio)
    VALUES (:pacient, :familiar, :relacio)";
    $insertTaulaAcompanyantComand = oci_parse($conn, $sentenciaInsertTaulaAcompanyant);
    oci_bind_by_name($insertTaulaAcompanyantComand, ":pacient", $finalCodePacient);
    oci_bind_by_name($insertTaulaAcompanyantComand, ":familiar", $finalCodeAcompanyant);
    oci_bind_by_name($insertTaulaAcompanyantComand, ":relacio", $_POST['relacio']);
    $exit3 = oci_execute($insertTaulaAcompanyantComand);

    $msg = "";

    if(!$exit1){
        $msg = "No s'ha pogut guardar correctament el pacient";
    } else if(!$exit2){
        $msg = "No s'ha pogut guardar correctament l'acompanyant";
    } else if(!$exit3) {
        $msg = "No s'ha pogut guardar correctament la relació familiar/acompanyant";
    }
    else {
        if(!empty($_POST["dni"])){
            $msg = "<p>Nou pacient " . $finalCodePacient . " inserit</p>\n";
            $msg .= "<p>Nou acompanyant " . $finalCodeAcompanyant . " inserit</p>\n";
            $msg .= "<p>Nova relació " . $finalCodePacient . " " . $finalCodeAcompanyant . " ". $_POST['relacio'] . " inserida</p>\n";
        }
    }
}

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Nou pacient</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Entrar nou pacient</h1>
        </div>
        <?php
        if(isset($msg)){
            echo "<p class='error'>" . $msg . "</p>";
        }
        ?>
        <div class="opcions center">
            <form class="center-block" method="post">
                <h1 style="font-weight: bold">Pacient</h1>
                <div class="espai-dades">
                    <label for="dni">DNI</label>
                    <input type="text" name="dni"/>
                </div>
                <div class="espai-dades">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" />
                </div>
                <div class="espai-dades">
                    <label for="cognoms">Cognoms</label>
                    <input type="text" name="cognoms" />
                </div>
                <div class="espai-dades">
                    <label for="poblacio">Població</label>
                    <input type="text" name="poblacio" />
                </div>
                <div class="espai-dades">
                    <label for="email">Email</label>
                    <input type="text" name="email" />
                </div>
                <div class="espai-dades">
                    <label for="telefon">Telèfon</label>
                    <input type="number" name="telefon" />
                </div>
                <div class="espai-dades">
                    <label for="data">Data naixement</label>
                    <input type="date" name="data" />
                </div>
                <div class="espai-dades">
                    <label for="genere">Gènere:</label>
                    <select name="genere">
                        <option value="H">Home</option>
                        <option value="D">Dona</option>
                    </select>
                </div>
                <h1 style="font-weight: bold">Acompanyant</h1>
                <div class="espai-dades">
                    <label for="dniAcompanyant">DNI</label>
                    <input type="text" name="dniAcompanyant"/>
                </div>
                <div class="espai-dades">
                    <label for="nomAcompanyant">Nom</label>
                    <input type="text" name="nomAcompanyant" />
                </div>
                <div class="espai-dades">
                    <label for="cognomsAcompanyant">Cognoms</label>
                    <input type="text" name="cognomsAcompanyant" />
                </div>
                <div class="espai-dades">
                    <label for="telefonAcompanyant">Telefon</label>
                    <input type="number" name="telefonAcompanyant" />
                </div>
                <div class="espai-dades">
                    <label for="emailAcompanyant">Email</label>
                    <input type="text" name="emailAcompanyant" />
                </div>
                <div class="espai-dades">
                    <label for="relacio">Relació:</label>
                    <select name="relacio">
                        <option value="fill">Fill</option>
                        <option value="filla">Filla</option>
                        <option value="conjugue">Conjugue</option>
                        <option value="mare">Mare</option>
                        <option value="pare">Pare</option>
                        <option value="germà">Germà</option>
                        <option value="germana">Germana</option>
                        <option value="amic">Amic</option>
                        <option value="amiga">Amiga</option>
                    </select>
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
