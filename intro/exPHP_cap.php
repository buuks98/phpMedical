<?php
$emmagatzemarSessions = exec("pwd") . "/tmp";
ini_set('session.save_path', $emmagatzemarSessions);
session_start();
$connexio = oci_connect($_SESSION['usuari'], $_SESSION['password'], 'ORCLCDB');
if (!$connexio) {
  header('Location: exPHP_errorLogin.php');
}
?>
<p class="capcalera">usuari actiu: <b> <?php echo $_SESSION['usuari']; ?> </b></p>
