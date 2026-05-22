<?php
//tangkap data unit,jumlah tire, dan size yang digunakan, inisialisasi $unit,$tire, dan $size
$unit = $_POST['unit'];
$tire = $_POST['tire'];
$size = $_POST['size'];
$axl2 = $_POST['axl2'];
$axl4 = $_POST['axl4'];
$axl8 = $_POST['axl8'];
include "koneksi.php";
if ($unit !="" or $tire !="" or $size !="" ) {
//tambahkan unit baru di tabel unit 
$query = mysqli_query($sambung, "INSERT into unit (unit,tire,size,axl2tire,axl4tire,axl8tire) values ('$unit','$tire','$size','$axl2','$axl4','$axl8')");
header ("location: halamansitemaster.php");
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