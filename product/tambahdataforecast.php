<?php
include "koneksi.php";
$size = $_POST['size'];
$quantity = $_POST['qty'];
$submit_date = $_POST['submitdate']."-01";
$create_date = date('Y-m-d');
$project= $_POST['project'];
$user= $_POST['user'];
$str= $_POST['pattern'];
$data=explode(";",$str);
$brand=$data[0];
$pattern=$data[1];

echo $size."<br>";
echo $quantity."<br>";
echo $create_date."<br>";
echo "periode".$submit_date."<br>";
echo $project."<br>";
echo $user."<br>";

$query = mysqli_query($koneksi6,"INSERT into forecast (size,expected_brand,expected_pattern,quantity,create_date,submit_date,project,user,status) 
	                                values ('$size','$brand','$pattern','$quantity','$create_date','$submit_date','$project','$user','Open')");
	echo"<script>
		alert('Forecast data submitted');
		history.go(-1);
		</script>";
?>