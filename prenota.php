<?php 
require_once 'db.php';
if(!autentica()) {
    header("Location: accesso.php");
    die("403");
}
$utente = ottieniUtenteDaSessione(session_id());
print_r($_SESSION);
if(!isset($utente['id'])) {
    echo "utente non registrato";
    die(403);
}
if(!prenotaViaggio($utente['id'], $_POST['id_viaggio'])) {
    echo 'Prenotazione gia avvenuta.';
    die();
} 
echo "prenotato con successo...";
header(header: "Location: viaggio.php?id=".$_POST['id_viaggio']);
die();

?>