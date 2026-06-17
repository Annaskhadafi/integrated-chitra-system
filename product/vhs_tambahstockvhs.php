<?php
include "koneksi.php"; 
$pic = $_POST['pic'] ?? '';
$do = $_POST['do'] ?? '';
$pn = $_POST['pn'] ?? '';
$date = $_POST['date'] ?? '';
$qty = (int)($_POST['qty'] ?? 0);
$storeloc = $_POST['storeloc'] ?? '';

$loop=0;
while($loop<$qty){	
    $query = mysqli_query($koneksi6, "INSERT INTO stock SET id_part_number='$pn', delivery_date='$date', do='$do', status='onsite', id_storeloc='$storeloc', pic='$pic'");
    $loop++;
}

echo "<script>alert('Data added!'); window.location.href='vhs_halamanstockvhs.php';</script>";
?>
       