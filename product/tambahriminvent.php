<?php
include "koneksi.php"; 
//cek apakah Serial number sudah ada atau belum
$rimsn=str_replace(" ", "", $_POST['rimsn']);

$cekdulu= "SELECT * from rim_inventory where rim_sn like '%$rimsn%'"; 
$prosescek= mysqli_query($sambung, $cekdulu);
//jika ada, tampilkan warning
if (mysqli_num_rows($prosescek)>0) { 
    echo "<script>alert('Serial Number already exist');history.go(-1) </script>";
}
//jika tidak ada, jalankan perintah
else {
//tangkap seluruh data dari form tambah rim
$rimsn=str_replace(" ", "", $_POST['rimsn']);
$size = $_POST['size'];
$status = $_POST['status'];
$lifetime = $_POST['lifetime'];
$price = $_POST['price'];
$date = $_POST['date'];
if ($rimsn !="" AND $size !="" AND $status !="" AND $lifetime !="" AND $price !="") {
//insert data tire baru ke dalam tabel tire_inventory
	// echo "sn".$rimsn;
	// echo "size".$size;
	// echo "status".$status;
	// echo "lifetime".$lifetime;
	// echo "price".$price;
	// echo "date".$date;	
$query = mysqli_query($sambung, "INSERT into rim_inventory (rim_sn,rim_size,rim_status,rim_price,rim_lifetime,rim_assembly,rim_received_date) values ('$rimsn','$size','$status','$price','$lifetime','0','$date')");
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
       