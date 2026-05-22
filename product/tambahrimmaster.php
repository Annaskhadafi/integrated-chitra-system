<?php
//tangkap nilai manufac
$manufac = $_POST['manufac'];
$type = $_POST['type'];
$size = $_POST['size'];
// echo $manufac."<br>";
// echo $type."<br>";
// echo $size."<br>";
include "koneksi.php";
if ($manufac !="") {
	//insert nilai $manufac ke dalam tabel tire_manufac
	$query = mysqli_query($sambung, "INSERT into rim (rim_manufac,rim_type,rim_size) values ('$manufac','$type','$size')");
	// kembali ke halaman sebelumnya
	echo"<script>
		alert('Data berhasil dimasukkan');
		history.go(-1);
	</script>";
} 
else {
	echo "<script>
	alert ('Please fill the blank form !');
	history.go(-1);
	</script>";
}
?>