<?php
//tangkap nilai manufac
$manufac = $_POST['manufac'];
include "koneksi.php";
if ($manufac !="") {
	//insert nilai $manufac ke dalam tabel tire_manufac
	$query = mysqli_query($sambung, "INSERT into tire_manufac (manufac) values ('$manufac')");
	header ("location: halamantiremaster.php");
	// kembali ke halaman sebelumnya
	echo"<script>
		alert('Data berhasil dimasukkan');
		history.go(-1);
	</script>";
} 
else {
	echo "<script>
	alert ('Tidak boleh kosong');
	history.go(-1);
	</script>";
}
?>