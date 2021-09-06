<?php 
require_once("library.php");
header("Content-type: application/json");
$call = new token;
echo json_encode($call->insert_token());


?>