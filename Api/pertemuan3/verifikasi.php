<?php 
require_once("library.php");
$call = new login;

header("Content-type:application/json");
echo json_encode($call->total());

?>