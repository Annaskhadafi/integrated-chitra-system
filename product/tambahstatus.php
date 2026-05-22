<?php
//tangkap data status, inisialisasi $status
$status = $_POST['status'];
include "koneksi.php";
if ($status !="") {
//tambahkan status baru di tabel tire_status
$query = mysqli_query($sambung, "INSERT into tire_status (status) values ('$status')");
header ("location: halamantiremaster.php");
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