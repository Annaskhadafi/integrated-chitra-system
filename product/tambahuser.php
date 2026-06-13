<?php
include "koneksi.php"; 
	$nama = $_POST['nama'];
	$sn = $_POST['sn'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$level = $_POST['level'];
	if ($nama !="" AND $sn !="" AND $password !="" AND $level !="") {
		$query = mysqli_query($koneksi3, "INSERT into user (sn,nama,password,level) values ('$sn','$nama','$password','$level')");
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
       