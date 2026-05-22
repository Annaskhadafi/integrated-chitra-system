<?php
//tangkap data id_pattern,pattern, dan manufac
$idusage = $_POST['idusage'];
$qtysebelum = $_POST['qtysebelum'];
$idinv = $_POST['idinv'];
$qty = $_POST['qty'];
$balance=$qtysebelum-$qty;   
include "koneksi.php";
//update data dengan id_compound = $id_compound 
$stockbalance=mysqli_query($sambung, "UPDATE mat_inventory SET inv_qty = inv_qty - $balance WHERE id_inv = $idinv");

$perintah = mysqli_query($sambung, "UPDATE mat_usage set qty='$qty' where id_usage=$idusage");
header ("location: halamanstockupdate.php");
 echo"<script>
		history.go(-1);
		</script>";  
?>

