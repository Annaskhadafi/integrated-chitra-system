<?php
//tangkap data id_supplier dan supplier
$id_supplier = $_POST['id_supplier'];
$supplier = $_POST['supplier'];
include "koneksi.php";
//update data yang memiliki id_supplier = $id_supplier
$perintah = mysqli_query($sambung, "UPDATE supplier set
	id_supplier= '$id_supplier',
    supplier='$supplier'    
    where id_supplier=$id_supplier");
header ("location: halamantiremaster.php");
 echo"<script>
		history.go(-2);
		</script>";  
?>