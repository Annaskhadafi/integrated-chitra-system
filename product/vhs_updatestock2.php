<?php
include "koneksi.php";
$date = $_POST['date'] ?? '';
$picgi = $_POST['picgi'] ?? '';
$idstocks = $_POST['idstock'] ?? [];

if (is_array($idstocks)) {
    foreach ($idstocks as $index => $idstock) {
        $wo = $_POST['wo'][$index] ?? '';
        $gi = $_POST['gi'][$index] ?? '';
        
        $query = mysqli_query($koneksi6, "UPDATE stock SET status='Done', wo='$wo', gi='$gi', gi_date='$date', picgi='$picgi' WHERE stock.id_stock = '$idstock'");
    }
}

echo "<script>alert('Data updated!'); window.location.href='vhs_halamanstockvhs.php';</script>";
?>