<?php
require_once("library.php");
$call = new login;
if($call->email_check() == false){
    echo "email salah";
}else{
    echo "email benar";
}
?>
