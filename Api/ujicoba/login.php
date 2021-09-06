<?php 
require_once("library.php");
$call = new token;
header("Content-type: application/json");
echo json_encode($call->login_exec());


?>