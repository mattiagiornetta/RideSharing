<?php 
require_once "db.php";
session_start();
$utente = ottieniUtenteDaSessione(session_id());
var_dump($_POST);
if(password_verify($_POST['password-attuale'], $utente['password'])) {
    cambiaPassword($utente['id'], $_POST['nuova-password']);
      $_SESSION['message'] = "Password cambiata!";
      $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = "Password attuale errata!";
$_SESSION['message_type'] = 'error';
}

  header('Location: impostazioni.php');
    exit();
?>