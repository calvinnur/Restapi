<?php 

class data {

    public function koneksi(){
        $connect = mysqli_connect("localhost","root","","indonesia");
        return $connect;
    }

    public function query($command){
        $query = mysqli_query($this->koneksi(), $command);
        return $query;
    }

    public function data_provinsi(){
        $cari = null;
        if(isset($_GET["cari"])){
            $cari = $_GET["cari"];
        }
        $query = $this->query("select * from provinces where name like '%$cari%'");
        $index = 0;
        while($show = mysqli_fetch_assoc($query)){
            $build[$index]["province_id"] = $show["id"];
            $build[$index]["province_name"] = $show["name"];

            $query1= $this->query("select * from regencies where province_id = '".$show["id"]."'");
            $index1 = 0;
            while($show1 = mysqli_fetch_assoc($query1)){
                $build[$index]["city"][$index1]["city_id"] = $show1["id"];
                $build[$index]["city"][$index1]["city_name"] = $show1["name"];
                $index1++;
            }
            $index++;
            }
    return $build;
    }
}

?>