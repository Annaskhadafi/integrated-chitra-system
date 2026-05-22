<?php
//tangkap data id_manufac dan manufac
$id_manufac = $_POST['id_manufac'];
$manufac = $_POST['manufac'];
include "koneksi.php";
//update data tire manufac yang memiliki id_manufac=$id_manufac
$perintah = mysqli_query($sambung, "UPDATE tire_manufac set
	id_manufac= '$id_manufac',
    manufac='$manufac'    
    where id_manufac=$id_manufac");
header ("location: halamantiremaster.php");
 echo"<script>
		history.go(-2);
		</script>";  
?>