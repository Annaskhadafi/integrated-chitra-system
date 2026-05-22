<?php
include "koneksi.php"; 
	$sn = $_POST['sn'];
	$size = $_POST['size'];
	$brand = $_POST['brand'];
	$nocargo = $_POST['nocargo'];
	$tipe = $_POST['tipe'];
	$status = $_POST['status'];
	$customer = $_POST['customer'];
	$date = $_POST['date'];
	$jobtype = $_POST['job_type'];
	
	if ($sn !="" AND $size !="" AND $brand !=""AND $nocargo !="" AND $tipe !="" AND $status !="" AND $customer !=""AND $date !=""AND $jobtype !="") {
// 	echo $sn;
// 	echo $size;
// 	echo $brand;
// 	echo $tipe;
// 	echo $status;
// 	echo $customer;
// 	echo $date;
//     echo $jobtype;
//     echo $createby;

		$query = mysqli_query($koneksi3, "INSERT into work_order set wo='$wo',job_type='$jobtype',status='$status',size='$size',brand='$brand',nocargo='$nocargo',type='$tipe',tire_sn='$sn',customer='$customer',received_date='$date',injury='',repair_type='',id_store_loc=3,createby=0,po='',bast=''");
		echo"<script>
		alert ('Data Submitted');
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
       