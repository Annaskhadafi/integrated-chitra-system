<?php
include "koneksi.php"; 
$storeloc = $_POST['storeloc'] ?? '';
$actual = $_POST['actual'] ?? 0;
$material = $_POST['material'] ?? '';
$pic = $_POST['pic'] ?? '';
$date = date('Y-m-d');

$query = mysqli_query($koneksi6, "INSERT INTO actual SET id_part_number='$material', id_storeloc='$storeloc', last_update='$date', qty_actual='$actual', pic='$pic'");

echo "<script>alert('Data added!'); window.location.href='vhs_halamanactualvhs.php';</script>";
?>
       