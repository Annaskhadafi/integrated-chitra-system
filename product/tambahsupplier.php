<?php
//tangkap data supplier, inisialisasi $supplier
$supplier = $_POST['supplier'];
include "koneksi.php";
if ($supplier !="") {
//tambahkan data supplier baru di tabel supplier
$query = mysqli_query($sambung, "INSERT into supplier (supplier) values ('$supplier')");
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