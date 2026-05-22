<?php 
$idstock= $_POST['idstock'];
$idpartnumber= $_POST['idpartnumber'];
$loop= $_POST['loop'];
include "koneksi.php";
for($ulang=0;$ulang<$loop;$ulang++){
    // echo "<br>";
    // echo "<br>";
    // echo $ulang;
    // echo $idstock[$ulang];
    // echo $idpartnumber[$ulang];
    // echo $idstock;
    // echo $idpartnumber;
    // echo "<br>";
    // echo "<br>";
    $query=mysqli_query($koneksi6, "UPDATE stock SET id_part_number=$idpartnumber[$ulang] WHERE id_stock=$idstock[$ulang] ");
}
echo "<script> alert ('Data stock telah diupdate'); history.go(-1); </script>";
?>