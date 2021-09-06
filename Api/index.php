<?php
require_once('library.php');
$call = new database;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?<?php echo time() ?>">
    <title>Makanan</title>
</head>

<body>
    <table border="1" cellspacing=0>
        <tr>
            <td>Nama makanan</td>
            <td>Harga</td>
            <td>Jenis</td>
        </tr>
        <?php
        $get_data = file_get_contents("http://localhost/belajar/Api/menu.php");
        $decode = json_decode($get_data);
        foreach ($decode as $key => $value) :
        ?>
            <tr>
                <td id="makanan"><?php echo $value->nama_makanan ?></td>
                <td id="harga"><?php echo $value->harga ?></td>
                <td id="jenis"><?php echo $value->jenis ?></td>
            </tr>
        <?php endforeach ?>

    </table>
    <h3>Pilih makanan</h3>
    <form method="POST" action="hasil.php">
        <select name="pilihan">
            <?php
            foreach ($call->list_makanan() as $key => $value) : ?>
                <option><?php echo $value['nama_makanan'] ?></option>
            <?php endforeach ?>
        </select>
        <input type="text" name="jumlah">
        <button type="submit">Kirim</button>
    </form>
    <script src="../jquery.js"></script>
    <script src="menu.js"></script>
</body>

</html>