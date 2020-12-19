#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="home.css">
    <title>User: <?php echo $_SESSION["username"] ?></title>
    <head>

<body>
<div class="contenidor">
    <div class="contingut">
        <div class="titol">
            <div class="topleft">
                <a href="logout.php">Logout</a>
            </div>
            <h1>User: <?php echo $_SESSION["username"] ?></h1>
        </div>

        <div class="opcions">
            <ul>
                <li>
                    <a href="alta_personal_medic.php" class="menu">Donar d'alta un nou membre del personal mèdic</a>
                </li>
                <li>
                    <a href="llistar_membres.php" class="menu">Llistar els membres del personal</a>
                </li>
                <li>
                    <a href="alta_pacient.php" class="menu">Donar d'alta un nou pacient</a>
                </li>
                <li>
                    <a href="programar_visita.php" class="menu">Programar una visita</a>
                </li>
                <li>
                    <a href="programar_intervencio.php" class="menu">Programar una intervenció d'un pacient</a>
                </li>
                <li>
                    <a href="planificar_intervencio.php" class="menu">Planificar una intervenvió d'un pacient</a>
                </li>
                <li>
                    <a href="mostrar_intervencions.php" class="menu">Mostrar intervencions</a>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>

</html>
