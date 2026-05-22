<?php
//tangkap data id_compound dan compound
$id_compound = $_POST['id_compound'];
$compound = $_POST['compound'];
include "koneksi.php";
//update data dengan id_compound = $id_compound
$perintah = mysqli_query($sambung, "UPDATE tire_compound set
	id_compound= '$id_compound',
    compound='$compound'    
    where id_compound=$id_compound");
header ("location: halamantiremaster.php");
 echo"<script>
		history.go(-2);
		</script>";  
?>