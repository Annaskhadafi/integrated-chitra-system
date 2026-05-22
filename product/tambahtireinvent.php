<?php
include "koneksi.php"; 
//cek apakah Serial number sudah ada atau belum
$cekdulu= "SELECT * from tire_inventory where sn like '%$_POST[sn]%'"; 
$prosescek= mysqli_query($sambung, $cekdulu);
//jika ada, tampilkan warning
if (mysqli_num_rows($prosescek)>0) { 
    echo "<script>alert('Serial Number already exist');history.go(-1) </script>";
}
//jika tidak ada, jalankan perintah
else {
//tangkap seluruh data dari form tambah tire
$sn = $_POST['sn'];
$size = $_POST['size'];
$compound = $_POST['compound'];
$supplier = $_POST['supplier'];
$status = $_POST['status'];
$site = $_POST['site'];
$lifetime = $_POST['lifetime'];
$rtd = $_POST['rtd'];
$price = $_POST['price'];
$date = $_POST['date'];
if ($sn !="" AND $size !="" AND $supplier !=""AND $status !="" AND $lifetime !="" AND $rtd !=""AND $compound !="") {
//insert data tire baru ke dalam tabel tire_inventory
$query = mysqli_query($sambung, "INSERT into tire_inventory (sn,size,supplier,status,compound,site,lifetime,rtd,price,date_receive,rating) values ('$sn','$size','$supplier','$status','$compound','$site','$lifetime','$rtd','$price','$date','A')");
header ("location: halamantireinventupd.php");
echo"<script>
	alert('Data submitted');
	history.go(-1);
	</script>";
} else {
	echo "<script>
	alert ('Please fill the blank page');
	history.go(-1);
	</script>";
	}
} 
?>
       