<?php 


class token{
    

    public function connect(){
        $connect = mysqli_connect('localhost','root','','mahasiswa');
        return $connect;
    }

    public function query($command){
        $query = mysqli_query($this->connect(),$command);
        return $query;
    }

    public function insert_token(){
        $token = md5(time());
        $duration = 2; // dalam satuan hari
        $query = $this->query("insert into token (waktu, waktu_akhir, token) values(
            '".time()."',
            '".(time() + ((3600 * 24) * $duration))."',
            '".$token."'
        )");
        return [
            "my_token" => $token,
            "expired_in" => $duration." Days"
        ];
    }

    public function check_token(){
        $token = null;
        if(isset($_GET['token'])){
            $token = $_GET['token'];
        }
        $query = $this->query("select * from token where token = '$token' "); 
            while($show = mysqli_fetch_assoc($query)){
                
                if (time() < $show["waktu"])
                {
                    $rsp["response"] = false;
                    $rsp["msg"] = "Lom bisa di pake cok.";
                }
                else if (time() > $show["waktu_akhir"])
                {
                    $rsp["response"] = false;
                    $rsp["msg"] = "Token Expired";
                }
                else if ((time() >= $show["waktu"] and time() < $show["waktu_akhir"]))
                {
                    $rsp["response"] = true;
                    $rsp["msg"] = "Token Valid";
                }
            }    
        
       return $rsp;
    }


}


?>