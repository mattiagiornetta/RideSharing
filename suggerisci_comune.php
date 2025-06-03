<?php
require_once "db.php";

$comuni = ottieniSuggerimentoCitta($_GET['citta']);
header('Content-Type: application/json');

echo json_encode($comuni);
?>