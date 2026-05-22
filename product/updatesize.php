<?php
//tangkap data id_size, size, pattern, otd , psi dan target
$id_size = $_POST['id_size'];
$size = $_POST['size'];
$pattern = $_POST['pattern'];
$otd = $_POST['otd'];
$psi = $_POST['psi'];
$target = $_POST['target'];
include "koneksi.php";
//update data yang memiliki id_size = $id_size
$perintah = mysqli_query($sambung, "UPDATE tire_size set
	id_size= '$id_size',
	size= '$size',
    pattern='$pattern',
    otd='$otd',
    recc_pressure='$psi',
    target='$target'
    where id_size=$id_size");
 echo"<script>
		history.go(-1);
		</script>";  
?>