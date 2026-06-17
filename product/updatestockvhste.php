<?php 
$idstock = $_POST['idstock'] ?? '';
$install = $_POST['install'] ?? '';
$unit = $_POST['unit'] ?? '';
$position = $_POST['position'] ?? '';
$wo = $_POST['wo'] ?? '';
$mrko = $_POST['mrko'] ?? '';

include "koneksi.php";
if($install!='' && $mrko!=''){
    $query = mysqli_query($koneksi6, "UPDATE stock SET install_date='$install', status='Waiting Invoice', unit='$unit', position='$position', wo='$wo', mrko='$mrko' WHERE id_stock = '$idstock'");
}
else{
    $query = mysqli_query($koneksi6, "UPDATE stock SET install_date='$install', status='Waiting mrko', unit='$unit', position='$position', wo='$wo', mrko='$mrko' WHERE id_stock = '$idstock'");
}

echo "<script> alert ('Data stock telah diupdate'); history.go(-1); </script>";
?>
