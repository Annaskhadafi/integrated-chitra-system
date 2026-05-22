<?php
include "koneksi.php"; 
	$site = $_POST['site'];
	$idcust = $_POST['customer'];
	$idsap = $_POST['idsap'];
	if ($site !="" and $idcust !="") {
		$query = mysqli_query($koneksi3, "INSERT into customer (nama_customer,site,idsap) values ('$idcust','$site','$idsap')");
		echo"<script>
			history.go(-1);
			</script>";
	} 
	else {
		echo "<script>
		alert ('Please fill the blank page');
		history.go(-1);
		</script>";
	}
?>
       