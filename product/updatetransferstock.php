<?php
include "koneksi.php";
$idusage= $_POST['idusage'];
$idstoreloc=$_POST['idstoreloc'];
$date = $_POST['date'];
$desc = $_POST['desc'];
$idinv = $_POST['idinv'];
$qty = $_POST['qty']; //yg di transfer
$qtysebelum = $_POST['qtysebelum']; //yg di transfer
$balance=$qtysebelum-$qty;
$total=$balance+$qty;
$stock=$inv_qty-$balance;
$store_location = $_POST['store_location'];
$date=date('Y-m-d');

// $date=="" {$date='0001-01-01';}
// echo $idusage;
// echo "<br>".$date;
// echo "<br>".$material;
// echo "<br>".$idinv;
// echo "<br>".$balance;
// echo "<br>".$qty;
// echo "<br>".$qtysebelum;
// echo "<br>".$desc;
// echo "<br>".$idstoreloc;
$stock=mysqli_query($sambung, "UPDATE mat_inventory a SET a.inv_qty = a.inv_qty - $balance WHERE a.id_store_loc=$idstoreloc AND a.desc LIKE '$desc'");
$stockbalance=mysqli_query($sambung, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $idinv"); // kode untuk nambahin stok di bpp
$perintah = mysqli_query($sambung, "UPDATE mat_usage SET qty= $qty WHERE id_usage=$idusage");  // in kode untuk edit stock transfer
?>
<script>
    history.go(-1);
</script> 
