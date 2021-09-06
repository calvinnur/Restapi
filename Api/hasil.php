<?php 
require_once('library.php');
$call = new database;
$jumlah = $_POST['jumlah'];
foreach($call->hitung_harga() as $key => $value){
    $harga = $value['harga'];
    $hitung = $harga * $jumlah;
}
$data = array(
    'hasil' => $hitung
);
header("Content-type:application/json");
echo json_encode($data);

?>