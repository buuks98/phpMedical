#!/usr/bin/php-cgi
<?php
include 'functions.php';
start_session();
check_login();
$conn = connect();
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Llistat intervencions</title>
    <link rel="stylesheet" href="home.css" type="text/css">
</head>
<body>
<div class="contenidor">
    <div class="contingut" style="width: 80%!important;">
        <div class="titol">
            <div class="topleft">
                <a href="home.php">Tornar al menu</a>
            </div>
            <h1>Llistat intervencions</h1>
        </div>
        <?php
        if(isset($msg)){
            echo "<p class='error'>" . $msg . "</p>";
        }
        ?>
        <div class="opcions center">
            <form class="center-block" method="POST">
                <div class="espai-dades">
                    <label for="dataInici">Data inici</label>
                    <input type="date" name="dataInici" />
                </div>
                <div class="espai-dades">
                    <label for="dataFi">Data fi</label>
                    <input type="date" name="dataFi" />
                </div>
            </form>
        </div>
        <table border="1" style="margin: 0 auto 0 auto; display=block">
            <tr>
                <th>Data</th>
                <th>Responsable</th>
                <th>Nombre de professionals</th>
                <th>Pacient</th>
                <th>Serie protesis</th>
                <th>Estat</th>
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
