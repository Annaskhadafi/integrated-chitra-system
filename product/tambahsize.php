<?php
// tangkap seluruh nilai dari form tambah size
$size = $_POST['size'];
$pattern = $_POST['pattern'];
$otd = $_POST['otd'];
$psi = $_POST['psi'];
$target = $_POST['target'];
include "koneksi.php";
if ($size !="" AND $pattern !="" AND $otd !="" AND $psi !="") {
$query = mysqli_query($sambung, "INSERT into tire_size (size,pattern,otd,recc_pressure,target) values ('$size','$pattern','$otd','$psi','$target')");
echo"<script>
	alert('Data berhasil dimasukkan');
	history.go(-1);
	</script>";
} else {
	echo "<script>
	alert ('Tidak boleh kosong');
	history.go(-1);
	</script>";
	}
?>