#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $sentenciaSou = "SELECT souReferencia FROM tipologiespersonal WHERE codi LIKE '".$_POST['tipologia']."%'";
    $comandaSou = oci_parse($conn, $sentenciaSou);
    oci_execute($comandaSou);
    $rowA = oci_fetch_array($comandaSou, OCI_ASSOC + OCI_RETURN_NULLS);
    foreach ($rowA as $columnaA) {
        $sou = $columnaA;
    }

    $letterType = substr($_POST['tipologia'], 0,1);
    $sentenciaCodis = "SELECT codi FROM personalmedic WHERE codi LIKE '".$letterType."%' order by codi desc";
    $comandaCodis = oci_parse($conn, $sentenciaCodis);
    oci_execute($comandaCodis);
    $row = oci_fetch_array($comandaCodis, OCI_ASSOC + OCI_RETURN_NULLS);
    $higherNumber = '';
    foreach ($row as $columna) {
        $higherNumber = intval(substr($columna, 1, 3));
    }

    $finalHigherNumber = 100;
    if($higherNumber >= 100){
        $finalHigherNumber = $higherNumber + 1;
    }

    $code = $letterType . $finalHigherNumber;

    $sentenciaInsert = "INSERT INTO personalmedic (codi, nom, dni, 
    genere, cognoms, adreca, poblacio, email, telefon, tipologiaProfessional, sou, dataAlta, codiPostal)
    VALUES (:codi, :nom, :dni, :genere, :cognoms, :adreca, :poblacio, :email, :telefon, :tipologiaProfessional, :sou, TO_DATE(:dataAlta, 'YYYY-MM-DD'), :codiPostal)";
    $insertComand = oci_parse($conn, $sentenciaInsert);
    oci_bind_by_name($insertComand, ":codi", $code);
    oci_bind_by_name($insertComand, ":nom", $_POST['nom']);
    oci_bind_by_name($insertComand, ":dni", $_POST['dni']);
    oci_bind_by_name($insertComand, ":genere", $_POST['genere']);
    oci_bind_by_name($insertComand, ":cognoms", $_POST['cognoms']);
    oci_bind_by_name($insertComand, ":adreca", $_POST['adreca']);
    oci_bind_by_name($insertComand, ":poblacio", $_POST['poblacio']);
    oci_bind_by_name($insertComand, ":email", $_POST['email']);
    oci_bind_by_name($insertComand, ":telefon", $_POST['telefon']);
    oci_bind_by_name($insertComand, ":tipologiaProfessional", $_POST['tipologia']);
    oci_bind_by_name($insertComand, ":sou", $sou);
    $codiPostal = "17820";
    oci_bind_by_name($insertComand, ":codiPostal", $codiPostal);
    oci_bind_by_name($insertComand, ":dataAlta", $_POST['data']);
    $exit = oci_execute($insertComand);
    $msg = "";

    if(!$exit){
        $msg = "No s'ha pogut guardar correctament el personal medic";
    } else {
        if(!empty($_POST["nom"])){
            $msg = "<p>Nou personal medic " . $code . " inserit</p>\n";
        }
    }
    oci_free_statement($insertComand);
    oci_free_statement($comandaCodis);
    oci_free_statement($comandaSou);
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
        <div class="contingut">
            <div class="titol">
                <div class="topleft">
                    <a href="home.php">Tornar al menu</a>
                </div>
                <h1>Entrar nou membre de personal mèdic</h1>
            </div>
            <?php
            if(isset($msg)){
                echo "<p class='error'>" . $msg . "</p>";
            }
            ?>
            <div class="opcions center">
                <form class="center-block" method="post">
                    <div class="espai-dades">
                        <label for="dni">DNI</label>
                        <input type="text" name="dni"/>
                    </div>
                    <div class="espai-dades">
                        <label for="genere">Gènere:</label>
                        <select name="genere">
                            <option value="H">Home</option>
                            <option value="D">Dona</option>
                        </select>
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
                        <label for="adreca">Adreça</label>
                        <input type="text" name="adreca" />
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
                        </select>
                    </div>
                    <div class="espai-dades">
                        <label for="data">Data d'alta</label>
                        <input type="date" name="data" />
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
