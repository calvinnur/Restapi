<?php 

class token{

    public $username;
    public $password;
    public $retype;
    public $email;
    public $phone;
    public $fullname;
    public $isi_laporan;
    public $bagian_kerusakan;
    public function __construct()
    {
        #auth
        if(isset($_POST["full_name"])){
            $this->fullname = str_replace("'","",trim($_POST["fullname"]));
        }
        if(isset($_POST["email"])){
            $this->email = str_replace("'","",trim($_POST["email"])); 
        }
        if(isset($_POST["username"])){
            $this->username = str_replace("'","@",trim($_POST["username"]));
        }
        if(isset($_POST["password"])){
            $this->password = str_replace("'","@",trim($_POST["password"]));
        }
        if(isset($_POST["retype"])){
            $this->retype = str_replace("'","",trim($_POST["retype"]));
        }
        if(isset($_POST["phone"])){
            $this->phone = str_replace("'","",trim($_POST["phone"]));
        }
        #laporan
        $this->isi_laporan = (isset($_POST["isi_laporan"])) ? $_POST["isi_laporan"] : "";
        $this->bagian_kerusakan = (isset($_POST["bagian_kerusakan"])) ? $_POST["bagian_kerusakan"] : "";
    }

    public function koneksi(){
        $connect = mysqli_connect('localhost','root','','token');
        return $connect;
    }

    public function query($command){
        $query = mysqli_query($this->koneksi(), $command);
        return $query;
    }

    public function data(){
            $query = $this->query("select * from user where username = '".$this->username."'");
            $user_check = mysqli_num_rows($query);
            if(!isset($this->username)){
                $build["pesan"] = "method tidak ditemukan";
                $build["status"] = false;
            }elseif($user_check < 1){
                $build["pesan"] = "user tidak ditemukan";
                $build["status"] = false;
            }else{
                while($show = mysqli_fetch_assoc($query)){
                    if(!password_verify($this->password,$show["password"])){
                    $build["pesan"] = "password salah";
                    $build["status"] = false;
                    }else{
                        $build["username"] = $show["username"];   
                        $build["email"] = $show["email"];   
                        $build["address"] = $show["address"];   
                        $build["phone"] = $show["phone"];   
                        $build["token"] = $show["token"];
                        $build["expired_in"] = date("d M Y",$show["expired"]);
                        $build["status"] = true;
    
                    }
                }
            }    
            
        return $build;
    }


    public function required_check(){
        $field = ["username","password","retype","address","email","phone number"];
        $build["status"] = true;
        $index = 0;
        foreach($_POST as $key => $val){
            if(empty(trim($val))){
                $build["title"] = $field[$index];
                $build["status"] = false;
                break;    
            }
            $index++;
        }
        return $build;
    }

    public function user_karakter(){
        $length = strlen($this->username);
        if($length >= 6 and $length <= 12){
            return true;
        }else{
            return false;
        }
    }

    public function password_check(){
        $length = strlen($this->password);
        if($length >= 6 and $length <= 12){
            return true;
        }else{
            return false;
        }
    }

    public function retype(){
        if($this->password !== $_POST["retype"]){
            return false;
        }else{
            return true;
        }
    }

    public function email_data(){
        $query = $this->query("select * from user where email = '".$this->email."'");
        $count = mysqli_num_rows($query);
        if($count > 1){
            return false;
        }else{
            return true;
        }
    }

    public function email_check(){
        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            return false;
        }else{
            return true;
        }
    }

    public function numeric_check(){
        $number = $this->phone;
        $num = is_numeric($number);
        if($number == $num){
            return true;
        }else{
            return false;
        }
    }
    public function number_check(){
        $phone = $this->phone;
        $number = strlen($phone);
        if($number >= 11 and $number <= 14){
            return true;
        }else{
            return false;
        }
    }

    public function data_number(){
        $query = $this->query("select * from user where phone = '".$this->phone."'");
        $count = mysqli_num_rows($query);
        if($count > 1){
            return false;
        }else{
            return true;
        }
    }
    public function first_number(){
        $number = $this->phone;
        if($number[0] == "0" and $number[1] == "8"){
            return true;
        }else{
            return false;
        }
    }

    public function user_check(){
        $query = $this->query("select * from user where username = '".$this->username."'");
        $row  = mysqli_num_rows($query);
        if($row > 1){
            return true;
        }else{
            return false;
        }
    }

    public function insert_user(){
        $query = $this->query("insert into user (username,password,address,email,phone) val(
            '".$this->username."',
            '".strip_tags(password_hash($this->password,PASSWORD_DEFAULT))."',
            '".$_POST["address"]."',
            '".strip_tags($this->email)."',
            '".strip_tags($this->phone)."'
        )");
        return $query;
    }

    public function checked_register(){
        if($this->required_check()["status"] == false){
            $build["message"] = "mohon isi field yang tersedia";
            $build["response"] = false;
        }elseif($this->user_karakter() == false){
            $build["message"] = "min 6 karakter dan maksimal 12 karakter";
            $build["response"] = false;
        }elseif($this->password_check() == false){
            $build["message"] = "password min 6 karakter dan maksimal 12 karakter";
            $build["response"] = false;
        }elseif($this->retype() == false){
            $build["message"] = "password tidak sama";
            $build["response"] = false;
        }elseif($this->email_data() == false){
            $build["message"] = "email ini sudah digunakan silahkan isi dengan email lain";
            $build["response"] = false;
        }elseif($this->email_check() == false){
            $build["message"] = "mohon isi email dengan benar";
            $build["response"] = false;
        }elseif($this->numeric_check() == false){
            $build["message"] = "mohon isi no telp menggunakan angka";
            $build["response"] = false;
        }elseif($this->number_check() == false){
            $build["message"] = "no telp minimal karakter 11 dan maximal karakter 14";
            $build["response"] = false;
        }elseif($this->data_number() == false){
            $build["message"] = "no telp ini telah digunakan";
            $build["response"] = false;
        }elseif($this->first_number() == false){
            $build["message"] = "mohon isi no telp dengan angka pertama 08";
            $build["response"] = false;
        }else{
            echo $this->insert_user();
            $build["message"] = "data berhasil dibuat!";
            $build["response"] = true;
        }
            return $build;
    }

    #fungsi laporan 
    public function data_laporan(){
        $cari = null;
        if(isset($_GET["cari"])){
            $cari = str_replace("'","",$_GET["cari"]);
        }
        $query = $this->query("select * from laporan where bagian_kerusakan like '%$cari%'");
        $row = mysqli_num_rows($query);
        if($row < 1){
            $build["response"] = false;
            $build["message"] = "data tidak tersedia";
        }else{
            while($show = mysqli_fetch_assoc($query)){
                $build["isi_laporan"] = $show["isi_laporan"];
                $build["bagian_kerusakan"] = $show["bagian_kerusakan"];
                $build["token"] = $show["token"];
                $build["expired"] = date("D M Y", $show["expired"]);
                $build["response"] = true;
                $build["message"] = "data ditemukan";
            }
        }
        return $build;
    }

    public function insert_laporan(){
        $durasi = 2;
        $waktu = time();
        $query = $this->query("insert into laporan (isi_laporan,waktu_laporan,bagian_kerusakan,token,expired) values(
            '".$this->isi_laporan."',
            '".$waktu."',
            '".$this->bagian_kerusakan."',
            '".md5(time())."',
            '".(time() + ((3600 * 24) * $durasi))."'
        )");
        return $query;
    }

    public function insert_check(){
        $title = ["laporan_kerusakan","bagian_kerusakan"];
        $build["response"] = true;
        $index = 0;
        foreach($_POST as $key => $value){
            if(empty(trim($value))){
                $build["message"] = $title[$index];
                $build["response"] = false;
                break;
            }
            $index++;
        }
        return $build;
    }
    public function laporan_check(){
        $query = $this->query("select * from laporan where isi_laporan = '".$this->isi_laporan."'");
        $row = mysqli_num_rows($query);
            if($row > 1){
                return false;
            }else{
                return true;
            }
        
    }
    
    public function eksekusi_laporan(){
        $laporan = $this->insert_laporan();
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if($this->insert_check()["response"] == false){
                $build["response"] = false;
                $build["message"] = "mohon isi form yang tersedia";
                exit;
            }
            if($this->laporan_check() == false){
                $build["response"] = false;
                $build["message"] = "mohon isi laporan yang berbeda";
                exit;
            }
                $build["response"] = true;
                $build["message"] = "data berhasil ditambahkan!";
                echo $laporan;
        }else{
            $build["response"] = false;
            $build["message"] = "method tidak mendukung";
        }
        
        return $build;
    }

}




?>