<?php
//tangkap data id_unit,unit,tire, dan size
include "koneksi.php";
$id_unit = $_POST['id_unit'] ?? '';
$unit = $_POST['unit'] ?? '';
$tire = $_POST['tire'] ?? '';
$size = $_POST['size'] ?? '';
$axl2 = $_POST['axl2'] ?? '';
$axl4 = $_POST['axl4'] ?? '';
$axl8 = $_POST['axl8'] ?? '';

//update data yang memiliki id_unit = $id_unit
$perintah = mysqli_query($sambung, "UPDATE unit SET
	unit= '$unit',
	tire= '$tire', 
	size= '$size', 
	axl2tire= '$axl2',
	axl4tire= '$axl4', 
	axl8tire= '$axl8'  
 WHERE id_unit = '$id_unit'");

echo "<script>history.go(-1);</script>";  
?>

