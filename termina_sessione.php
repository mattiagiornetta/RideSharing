<?php
require_once "db.php";
if(!autentica()) {
    header("Location: accesso.php");
    die("403");
}
$target_session_id = $_GET['id'];
invalidaAccesso($target_session_id, false);
if(session_id() == $target_session_id) {
    header('Location: accesso.php');
    die(200);
}
header('Location: impostazioni.php');
?>