<?php
//tangkap nilai pattern dan id_manufac 
$pattern = $_POST['pattern'];
$manufac = $_POST['id_manufac'];
include "koneksi.php";
if ($pattern !="" AND $manufac !="") {
	// masukkan data pattern baru ke tire_pattern
$query = mysqli_query($sambung, "INSERT into tire_pattern (pattern,manufac) values ('$pattern','$manufac')");
header ("location: halamantiremaster.php");
echo"<script>
	alert('Data berhasil dimasukkan');
	</script>";
} else {
	echo "<script>
	alert ('Tidak boleh kosong');
	history.go(-1);
	</script>";
	}
?>