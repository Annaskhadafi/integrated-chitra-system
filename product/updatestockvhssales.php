<?php 
$idstock = $_POST['idstock'] ?? [];
$idpartnumber = $_POST['idpartnumber'] ?? [];
$loop = (int)($_POST['loop'] ?? 0);
include "koneksi.php";

if (is_array($idstock) && is_array($idpartnumber)) {
    for($ulang=0; $ulang < $loop; $ulang++){
        $current_idstock = $idstock[$ulang] ?? '';
        $current_idpartnumber = $idpartnumber[$ulang] ?? '';
        if ($current_idstock !== '' && $current_idpartnumber !== '') {
            mysqli_query($koneksi6, "UPDATE stock SET id_part_number='$current_idpartnumber' WHERE id_stock = '$current_idstock'");
        }
    }
}
echo "<script> alert ('Data stock telah diupdate'); history.go(-1); </script>";
?>