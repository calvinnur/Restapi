<?php
require_once("library.php");
$call = new token;
$header = getallheaders();
            $token = $header["token"];
            $query = $call->query("select * from token where token = '".$token."'");
            while($show = mysqli_fetch_assoc($query)){
                $username = $show["username"];
            }
            $querys = $call->query("select * from user where username = '".$username."'");
            while($shows = mysqli_fetch_assoc($querys)){
                $role_id = $shows["role_id"];
                if($role_id < 2){
                    return true;
                }else{
                    return false;
                }
            }






?>