<?php 
require_once("library.php");
$call = new data;

header("Content-type: application/json");
echo json_encode($call->data_provinsi());
?>
