<?php
include "koneksi.php";
$id_forecast=$_POST['id_forecast'];
$editdate=date('Y-m-d');
$size=$_POST['size'];
$qty=$_POST['qty'];
$submitdate=$_POST['submitdate'];
$project=$_POST['project'];
	
$perintah = mysqli_query($koneksi6,"UPDATE forecast set size='$size',quantity='$qty',submit_date='$submitdate',update_date='$editdate',project='$project' WHERE id_forecast=$id_forecast");
echo "<script>history.go(-1);</script>";  
?>
