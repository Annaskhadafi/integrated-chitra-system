<?php
//tangkap nilai compound, inisialisasi $compound 
$compound = $_POST['compound'];
include "koneksi.php";
if ($compound !="") {
//insert nilai $compound ke dalam tabel tire_compound
$query = mysqli_query($sambung, "INSERT into tire_compound (compound) values ('$compound')");
header ("location: halamantiremaster.php");
echo"<script>
	alert('Data submitted ');
	history.go(-1);
	</script>";
} else {
	//kembali ke halaman sebelumnya
	echo "<script>
	alert ('Please fill the blank form');
	history.go(-1);
	</script>";
	}
?>