<?php
//tangkap nilai remark
$remark = $_POST['remark'];
$cause = $_POST['cause'];
$rating = $_POST['rating'];
include "koneksi.php";
if ($remark !="") {
	// masukkan data pattern baru ke tire_pattern
$query = mysqli_query($sambung, "INSERT into tire_remark (remark,cause,rating) values ('$remark','$cause','$rating')");
 echo"<script>
		history.go(-1);
		</script>";  
} else {
	echo "<script>
	alert ('Tidak boleh kosong');
	history.go(-1);
	</script>";
	}
?>