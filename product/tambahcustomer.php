<?php
include "koneksi.php"; 
	$nama = $_POST['namacustomer'];
	if ($nama !="") {
		$query = mysqli_query($koneksi3, "INSERT into customer_data (cust_name) values ('$nama')");
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
       