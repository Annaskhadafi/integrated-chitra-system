<?php
include "koneksi.php";
$item = $_POST['item'];
if ($item=='fleet') {
	$idfleet = $_POST['idfleet'];
	$date = $_POST['date'];
	$customer = $_POST['customer'];
	$unit = $_POST['unit'];
	$qty = $_POST['qty'];
	$rotasi = $_POST['rotasi'];
	$scrap = $_POST['scrap'];
	$segment = $_POST['segment'];
	$user = $_POST['name'];
	$perintah = mysqli_query($koneksi2,"UPDATE fleet_list set id_site='$customer',id_unit= '$unit',unit_qty= '$qty',rotasi='$rotasi',scrap='$scrap',segment='$segment',date='$date',updateby='$user'
	                        where id_fleet_list=$idfleet");
	 header ("location: halamanFleetList.php");
	 echo "<script>
			history.go(-1);
			</script>";  
}
elseif ($item=='') {
}
elseif ($item=='') {
}
?>
