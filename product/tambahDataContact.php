<?php
include "koneksi.php"; 
$updateby = $_POST['updateby'];
$date = $_POST['date'];
$site = $_POST['site'];
$nama = $_POST['nama'];
$telp = $_POST['telp'];
$email = $_POST['email'];
$site = $_POST['site'];
$title = $_POST['title'];
	if ($site !="" and $nama !="" and $telp !="" and $email !="" and $site !="") {
		$query = mysqli_query($koneksi2, "INSERT INTO contact (idsite,nama,phone,email,updateby,date,title) values ('$site','$nama','$telp','$email','$updateby','$date','$title')");
		echo"<script>
			history.go(-1);
			</script>";
// echo "updateby".$updateby;
// echo "date".$date;
// echo "nama".$nama;
// echo "telp".$telp;
// echo "email".$email;
// echo "site".$site;
	} 
	else {
		echo "<script>
		alert ('Please fill the blank page');
		history.go(-1);
		</script>";
	}
?>
       