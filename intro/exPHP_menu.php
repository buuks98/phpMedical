#!/usr/bin/php-cgi
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
        <title>Exemple molt simple HTML (Bases de Dades, Enginyeria Informàtica UdG)</title>
        <link rel="stylesheet" href="exemple.css" type="text/css"> 
    </head>
    <body>
        <?php
// emmagatzem usuari i password en una sessió (a bas.udg.edu hem de tenir una carpeta pròpia on desar les sessions)
        $emmagatzemarSessions = exec("pwd") . "/tmp";
        ini_set('session.save_path', $emmagatzemarSessions);
        session_start();
        if (!empty($_POST['fuser'])) { // Arribem aquí per primera vegada
            $_SESSION['usuari'] = $_POST['fuser'];
            $_SESSION['password'] = $_POST['fpwd'];
            // ara comprovem usuari i password intentant establir connexió amb Oracle	
            $conn = oci_connect($_SESSION['usuari'], $_SESSION['password'], 'ORCLCDB');
            if (!$conn) {
                $error = oci_error($connexio);
                header('Location: exPHP_errorLogin.php');
            }
        }
        ?>
        <h1>Opcions disponibles</h1>
        <p> <a class="menu" href="exPHP_consultaVisites.php">Consulta visites</a></p>
        <p> <a class="menu" href="exPHP_consultaTipologiesProtesis.php">Consulta tipologies pròtesis</a></p>
        <p> <a class="menu" href="exPHP_altaVisita.html">Alta visita <i>(Només formulari HTML)</i></a></p>
        <p> <a class="menu" href="exPHP_altaVisita_a.php">Alta  visita <i>(Formulari amb desplegables codis)</i></a></p>
        <p> <a class="menu" href="exPHP_altaVisita_b.php">Alta visita <i>(Formulari amb desplegable cognoms i cognoms)</i></a></p>
        <p> <a class="menu" href="exPHP_altaVisita_c.php">Alta visita <i>(amb oci_bind)</i></a></p>
        <p> <a class="menu" href="exPHP_altaVisita_d.php">Alta visita <i> (codi i ordre automàtics)</i></a></p>
        <p> <a class="menu" href="exPHP_altres.php"> Altres... </a></p>
        <p class="peu"><a class="peu" href="practica_php.html"> Torna a la pàgina de login</p>
    </body>
</html>
