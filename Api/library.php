<?php
class database
{
    public function connect()
    {
        $connect = mysqli_connect('localhost', 'root', '', 'ktp');
        return $connect;
    }

    public function query($command)
    {
        $query = mysqli_query($this->connect(), $command);
        return $query;
    }

    public function list_makanan(){
        $query = $this->query("select * from list_makanan");
        while($show = mysqli_fetch_assoc($query)){
            $build[] = $show;
        }
        return $build;
    }
    public function hitung_harga(){
        $query = $this->query("select * from list_makanan where nama_makanan = '".$_POST['pilihan']."'");
        while($show = mysqli_fetch_assoc($query)){
            $build[] = $show;
        }
        return $build;
    }
   
}
