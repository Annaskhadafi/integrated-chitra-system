<?php
//tangkap data id_pattern,pattern, dan manufac
$id_pattern = $_POST['id_pattern'];
$pattern = $_POST['pattern'];
$manufac = $_POST['manufac'];
include "koneksi.php";
//update data dengan id_compound = $id_compound
$perintah = mysqli_query($sambung, "UPDATE tire_pattern set
	id_pattern= '$id_pattern',
	pattern= '$pattern',
    manufac='$manufac'    
    where id_pattern=$id_pattern");
header ("location: halamantiremaster.php");
 echo"<script>
		history.go(-2);
		</script>";  
?>