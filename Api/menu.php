<?php 
require_once('library.php');
$call = new database;
header("Content-type:application/json");
echo json_encode($call->list_makanan());

?>