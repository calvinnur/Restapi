<?php 

class login{

    public $email;
    public $password;
    
    public function __construct()
    {
        $this->email = str_replace("'","",trim($_POST["email"]));
        $this->password = str_replace("'","",trim($_POST["password"]));
        
    }

    public function connect(){
        $connect = mysqli_connect("localhost","root","","mahasiswa");
        return $connect;
    }

    public function query($command){
        $query = mysqli_query($this->connect(), $command);
        return $query;
    }

    public function input_check(){
        if($this->email == "" && $this->password == ""){
            return false;
        }else{
            return true;
        }
    }
    
    public function check_password(){
        $query = $this->query("select * from admin where username = '".$this->email."'");
        while($show = mysqli_fetch_assoc($query)){
            if(password_verify($this->password, $show["password"])){
                return true;
            }else{
                return false;
            }
        }
    }

    public function email_check(){
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        return false;
        }else{
        return true;
        }
    }

    public function total(){
        if($this->input_check() == false){
            $build["status"] = false;
            $build["message"] = "mohon isi username dan password anda";
        }elseif($this->email_check() == false){
            $build["status"] = false;
            $build["message"] = "mohon isi email dengan benar";
        }elseif($this->check_password() == false){
            $build["status"] = false;
            $build["message"] = "mohon check password anda dengan benar";
        }else{
            $build["status"] = true;
            $build["message"] = "selamat anda berhasil melakukan login";
        }
        return $build;
    }

    


}

?>