<?php
include "koneksi.php"; //ini untuk masuk ke database
//proses cek, apakah nomor unit sudah ada atau belum
$cekdulu= "SELECT * from unit_site where unit_number='$_POST[unitnumber]'";
$prosescek= mysqli_query($sambung, $cekdulu);
//jika nomor unit sudah ada
if (mysqli_num_rows($prosescek)>0) { 
	//tampilkan warning
    echo "<script>alert('Unit number already exist');history.go(-1) </script>";
}
//jika nomor unit belum ada, jalankan perintah
else { $unit = $_POST['unit'];
	$unitnumber = $_POST['unitnumber'];
	$hm = $_POST['hm'];
	$site = $_POST['site'];
	$status = $_POST['status'];
	if ($unitnumber !="" AND $unit !="" AND $hm !="") {
		//insert data unit baru ke dalam tabel unit_site
		$query = mysqli_query($sambung, "INSERT into unit_site (unit,unit_number,hm,site,status) values ('$unit','$unitnumber','$hm','$site','$status')");
		echo"<script>
			alert('Data berhasil dimasukkan');
			history.go(-1);
			</script>";
	} 
	else {
		echo "<script>
		alert ('Tidak boleh kosong');
		history.go(-1);
		</script>";
	} 
}
?>

       