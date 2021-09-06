<?php 

    class token{
      
        public $isi_laporan;
        public $bagian_kerusakan;
        public $username;
        public $password;
        public $retype;
        public $email;
        public $phone;
        public $fullname;
        public $address;
        public function __construct()
        {
            if(isset($_POST["isi_laporan"])){
                $this->isi_laporan = str_replace("'","",trim($_POST["isi_laporan"]));
            }
            if(isset($_POST["bagian_kerusakan"])){
                $this->bagian_kerusakan = str_replace("'","",trim($_POST["bagian_kerusakan"]));
            }
            if(isset($_POST["username"])){
                $this->username = str_replace("'","",trim($_POST["username"]));
            }
            if(isset($_POST["fullname"])){
                $this->fullname = str_replace("'","",trim($_POST["fullname"]));
            }
            if(isset($_POST["password"])){
                $this->password = str_replace("'","",trim($_POST["password"]));
            }
            if(isset($_POST["retype"])){
                $this->retype = str_replace("'","",trim($_POST["retype"]));
            }
            if(isset($_POST["email"])){
                $this->email = str_replace("'","",trim($_POST["email"]));
            }
            if(isset($_POST["phone"])){
                $this->phone = str_replace("'","",trim($_POST["phone"]));
            }
            if(isset($_POST["address"])){
                $this->address = str_replace("'","",trim($_POST["address"]));
            }
           
        }
        public function connect(){
            $connect = mysqli_connect('localhost','root','','token');
            return $connect;
        }

        public function query($command){
            $query = mysqli_query($this->connect(),$command);
            return $query;
        }

        # laporan function
        public function insert_laporan(){
            $header = getallheaders();
            $token = $header["token"];
            $q1 = $this->query("insert into laporan (isi_laporan,bagian_kerusakan,waktu_laporan,token,status) values(
                '".$this->isi_laporan."',
                '".$this->bagian_kerusakan."',
                '".date("d M Y", time())."',
                '".$token."',
                '0'
            )");
            return $q1;
        }

        public function view_laporan(){
            $konfirmasi = "konfirmasi";
            $belum = "belum terkonfirmasi";
            $header = getallheaders();
            $token = $header["token"];
            $query = $this->query("select * from laporan where token = '".$token."'");
            $row = mysqli_num_rows($query);
            if($row < 0){
                $build["response"] = false;
                $build["message"] = "data tidak tersedia";
            }else{
                while($show = mysqli_fetch_assoc($query)){

                    // $dump = var_dump($shows);
                    if($show["status"] == "0"){
                        $build = array_splice($show,0,5);
                        $build["status"] = $belum;
                        
                    }else{
                        $build = array_splice($show,0,5);
                        $build["status"] = $konfirmasi;
                    }
                }
            }
            return $build;
         
        } 
        public function laporan_check(){
            $query = $this->query("select * from laporan where isi_laporan = '".$this->isi_laporan."'");
            $row = mysqli_num_rows($query);
            if($row > 0){
                return false;
            }else{
                return true;
            }
        }

        public function role_check(){
            $header = getallheaders();
            $token = $header["token"];
            $query = $this->query("select * from token where token = '".$token."'");
            while($show = mysqli_fetch_assoc($query)){
                $username = $show["username"];
            }
            $querys = $this->query("select * from user where username = '".$username."'");
            $row = mysqli_num_rows($querys);
            if($row < 1){
                $build["response"] = false;
                $build["message"] = "user tidak ditemukan";
            }else{
                while($shows = mysqli_fetch_assoc($querys)){
                    $role_id = $shows["role_id"];
                }
                if($role_id < 2){
                    return true;
                }else{
                    return false;
                }
            }
          return $build;
        }


        public function delete_laporan(){
            $query = $this->query("delete from laporan where id = '".$_POST["id"]."'");
            return $query;
        }

        public function delete_laporan_exec(){
            if($this->token_check() == false){
                $build["response"] = false;
                $build["message"] = "token telah kadaluarsa atau tidak sama";
            }elseif($this->token_status() == false){
                $build["response"] = false;
                $build["message"] = "token sudah tidak aktif";
            }elseif($this->role_check() == true){
                $build["response"] = false;
                $build["message"] = "user tidak memiliki hak akses untuk ini";
            }else{
                $build["response"] = true;
                $build["message"] = "data berhasil dihapus";
                echo $this->delete_laporan();
            }
            return $build;
        }

        public function numeric_laporan(){
            $status = null;
            if(isset($_POST["status"])){
                $status = str_replace("'","",$_POST["status"]);
            }
            $numeric = is_numeric($status);
            if($status == $numeric){
                return true;
            }else{
                return false;
            }
           
        }
     
        public function update_laporan(){  
            $query = $this->query("update laporan set 
            status = '" . $_POST["status"]. "'
            where id = '" . $_POST["id"] . "'
            ");
            return $query;
        }

        public function updated_exec(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
            if($this->required_check() == false){
                $build["response"] = false;
                $build["message"] = "mohon isi angka pada form yang tesedia";
            }elseif($this->token_check() == false){
                $build["response"] = false;
                $build["message"] = "token telah kadaluarsa atau tidak sama";
            }elseif($this->token_status() == false){
                $build["response"] = false;
                $build["message"] = "token sudah tidak dapat dipakai";
            }elseif($this->role_check() == true){
                $build["response"] = false;
                $build["message"] = "user tidak memiliki akses untuk ini";
            }elseif($this->required_check() == false){
                $build["response"] = false;
                $build["message"] = "mohon isi form";
            }elseif($this->numeric_laporan() == false){
                $build["response"] = false;
                $build["message"] = "mohon diisi dengan angka";
            }else{
                $build["response"] = true;
                $build["message"] = "data berhasil diupdate";
                echo $this->update_laporan();
            }
        }else{
            $build["response"] = false;
            $build["message"] = "mohon gunakan method post";
        }
                return $build;
            
        }
            
        
        
        public function eksekusi(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                if($this->token_check() == false){
                    $build["response"] = false;
                    $build["message"] = "token telah kadaluarsa atau tidak sama";
                }elseif($this->token_status() == false){
                    $build["response"] = false;
                    $build["message"] = "token sudah tidak dapat dipakai";
                }elseif($this->required_check() == false){
                    $build["response"] = false;
                    $build["message"] = "mohon isi form";
                }elseif($this->laporan_check() == false){
                    $build["response"] = false;
                    $build["message"] = "mohon isi laporan yang berbeda";
                }else{
                    $build["response"] = true;
                    $build["message"] = "data berhasil ditambahkan";
                    $build["status_laporan"] = "belum terkonfirmasi";
                    echo $this->insert_laporan();
                }
            }else{
                $build["response"] = false;
                $build["message"] = "mohon isi dengan method post";
            }
            
            return $build;
        }

        public function all_data(){
            $cari = null;
            if(isset($_GET["cari"])){
                $cari = str_replace("'","",$_GET["cari"]);
            }
            $query = $this->query("select * from laporan where isi_laporan like '%$cari%'");
            $row = mysqli_num_rows($query);
            if($_SERVER["REQUEST_METHOD"] == "GET"){
                if($row < 1){
                    $build["response"] = false;
                    $build["message"] = "data tidak tersedia"; 
                }elseif($this->token_check() == false){
                    $build["response"] = false;
                    $build["message"] = "token tidak sama atau telah kadaluarsa"; 
                }elseif($this->role_check() == true){
                    $build["response"] = false;
                    $build["message"] = "user tidak memiliki hak akses untuk ini";
                }elseif($this->token_status() == false){
                    $build["response"] = false;
                    $build["message"] = "token sudah tidak aktif"; 
                }else{
                    while($show = mysqli_fetch_assoc($query)){
                        $build[] = $show;
                    }
                }
            }else{
                $build["response"] = false;
                $build["message"] = "method tidak mendukung";
            }
            
            return $build;
        }

        # register function

        public function required_check(){
            
            foreach($_POST as $key => $val){
                if(empty(trim($val))){
                    return false;
                    break;
                }
            }
            return true;
           
        }

        public function user_check(){
            $query = $this->query("select * from user where username = '".$this->username."' and fullname = '".$this->fullname."'");
            $row = mysqli_num_rows($query);
            if($row > 0){
                return false;
            }else{
                return true;
            }
        }

        public function email_data(){
            $query = $this->query("select * from user where email = '".$this->email."'");
            $row  = mysqli_num_rows($query);
            if($row > 0){
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

        public function numeric_phone(){
            $phone = $this->phone;
            $num = is_numeric($phone);
            if($phone == $num){
                return true;
            }else{
                return false;
            }
        }

        public function max_digit(){
            $phone = $this->phone;
            $length = strlen($phone);
            if($length >= 11 and $length <= 14){
                return true;
            }else{
                return false;
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

        public function number_check(){
            $query = $this->query("select * from user where phone = '".$this->phone."'");
            $row = mysqli_num_rows($query);
            if($row > 1){
                return false;
            }else{
                return true;
            }
        }

        public function password_check(){
            if($this->password !== $this->retype){
                return false;
            }else{
                return true;
            }
        }

        public function user_insert(){
            $query = $this->query("insert into user (fullname,address,email,phone,username,password,role_id)values(
                '".strip_tags($this->fullname)."',
                '".strip_tags($this->address)."',
                '".strip_tags($this->email)."',
                '".strip_tags($this->phone)."',
                '".strip_tags($this->username)."',
                '".password_hash(strip_tags($this->password),PASSWORD_DEFAULT)."',
                '1'
            )");
            return $query;
        }

        public function space_check(){
            $list = array_splice($_POST,2,4);
            $list["username"] = $_POST["username"];
            foreach($list as $key => $val){
                if(strpos($val,' ')){
                    return false;
                    break;
                }
            }
            return true;
        }
        
        public function regist_exec(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
             
                if($this->required_check() == false){
                    $build["response"] = false;
                    $build["message"] = "mohon isi form ";
            
                }elseif($this->space_check() == false){
                    $build["response"] = false;
                    $build["message"] = "spasi tidak diperbolehkan";
                    
                }elseif($this->user_check() == false){
                    $build["response"] = false;
                    $build["message"] = "user ini telah terdaftar mohon isi yang lain";
                    
                }elseif($this->email_check() == false){
                    $build["response"] = false;
                    $build["message"] = "mohon isi email dengan benar";
                    
                }elseif($this->email_data() == false){
                    $build["response"] = false;
                    $build["message"] = "email telah terdaftar";
                    
                }elseif($this->password_check() == false){
                    $build["response"] = false;
                    $build["message"] = "retype dan password tidak cocok";
                    
                }elseif($this->numeric_phone() == false){
                    $build["response"] = false;
                    $build["message"] = "mohon isi form phone dengan angka";
                    
                }elseif($this->first_number() == false){
                    $build["response"] = false;
                    $build["message"] = "isi form phone dengan awalan 08";
                    
                }elseif($this->max_digit() == false){
                    $build["response"] = false;
                    $build["message"] = "min digit 11 dan max digit 14";
                }elseif($this->number_check() == false){
                    $build["response"] = false;
                    $build["message"] = "no ini telah terdaftar";
                    
                }else{
                    $build["response"] = true;
                    $build["message"] = "data berhasil ditambahkan";
                    echo $this->user_insert();
                }
                    
                    
            }else{
                $build["response"] = false;
                $build["message"] = "method tidak dikenal";
            }
            return $build;
        }

        #login 

        public function username_check(){
            $query = $this->query("select * from user where username = '".$this->username."'");
            $row = mysqli_num_rows($query);
            if($row < 1){
                return false;
            }else{
                return true;
            }
        }

        public function check_password(){
            $query = $this->query("select * from user where username = '".$this->username."'");
            while($show = mysqli_fetch_assoc($query)){
                if(password_verify($this->password,$show["password"])){
                    return true;
                }else{
                    return false;
                }
            }
        }

        public function updated_token(){
            $duration = 2;
            $token =  md5(time());
            $expired = (time() + ((3600 * 24) * $duration));
            $query = $this->query("select * from user where username = '".$this->username."'");
            while($show = mysqli_fetch_assoc($query)){
                $update = $this->query("update user set
                    token = '".$token."',
                    expired = '".$expired."'
                    where id = '".$show["id"]."'
                ");
            }
            $insert = $this->query("insert into token (username,token,expired,status)values(
                '".strip_tags($this->username)."',
                '".$token."',
                '".$expired."',
                '1'
            )");
            return $update.$insert;
        }

     

        public function login_exec(){
            
            if($this->required_check() == false){
                $build["response"] = false;
                $build["message"] = "mohon isi form yang tersedia";
            }elseif($this->username_check() == false){
                $build["response"] = false;
                $build["message"] = "user tidak terdaftar silahkan mendaftar terlebih dahulu";
            }elseif($this->check_password() == false){
                $build["response"] = false;
                $build["message"] = "password salah ";
            }else{
                $build["response"] = true;
                $build["message"] = "login berhasil ";
                $build["token"] = md5(time());
                $build["token_status"] = "activated";
                echo $this->updated_token();
            }
            return $build;
        }

        #logout
        public function logout(){
            $header = getallheaders();
            $token = $header["token"];
            $query = $this->query("update token set 
            status = '0'
            where token = '".$token."'
            ");
            return $query;
        }
        public function logout_exc(){
            $build["response"] = true;
            $build["message"] = "berhasil melakukan logout";
            echo $this->logout();
            return $build;
        }

        #token
        public function token_check(){
            $header = getallheaders();
            $token = $header["token"];
            $query = $this->query("select * from token where token = '".$token."'");
            $row = mysqli_num_rows($query);
            if($row < 1){
                return false;
            }else{
                return true;
            }   
        }

        public function token_status(){
            $header = getallheaders();
            $token = $header["token"];
            $query = $this->query("select * from token where token = '".$token."' ");
            while($show = mysqli_fetch_assoc($query)){
                $status = $show["status"];
                if($status > 0){
                    return true;
                }else{
                    return false;
                }
            }
            
           
        }

        #user
        public function user_delete(){
            $query = $this->query("delete from user where username = '".$this->username."'");
            return $query;
        }

        public function admin_check(){
          if($this->username == "admin"){
              return false;
          }else{
              return true;
          }
        }

        public function delete_user(){
            if($this->required_check() == false){
                $build["response"] = false;
                $build["message"] = "mohon isi form yang tersedia";
            }elseif($this->token_status() == false){
                $build["response"] = false;
                $build["message"] = "token sudah tidak aktif";
            }elseif($this->role_check() == true){
                $build["response"] = false;
                $build["message"] = "user tidak memiliki hak akses untuk ini";
            }elseif($this->admin_check() == false){
                $build["response"] = false;
                $build["message"] = "admin tidak dapat menghapus dirinya sendiri";
            }elseif($this->user_check() == true){
                $build["response"] = false;
                $build["message"] = "username tidak dapat ditemukan";
            }else{
                $build["response"] = true;
                $build["message"] = "data berhasil dihapus";
                echo $this->user_delete();  
            }
            return $build;
        }

    }

?>