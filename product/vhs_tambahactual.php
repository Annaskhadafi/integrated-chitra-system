<?php
include "koneksi.php"; 
$storeloc = $_POST['storeloc'];
$actual = $_POST['actual'];
$material = $_POST['material'];
$pic = $_POST['pic'];
$date = date('Y-m-d');
// echo "Store Location: " . htmlspecialchars($storeloc) . "<br>";
// echo "Actual: " . htmlspecialchars($actual) . "<br>";
// echo "Material: " . htmlspecialchars($material) . "<br>";
// echo "PIC: " . htmlspecialchars($pic) . "<br>";
$query = mysqli_query($koneksi6, "INSERT INTO actual SET id_part_number=$material,id_storeloc=$storeloc,last_update='$date',qty_actual=$actual,pic=$pic");
    // echo "INSERT INTO actual SET id_part_number=$material,id_storeloc=$storeloc,last_update='$date',qty_actual=$actual,pic=$pic";

echo "<script>alert('Data added!'); window.location.href='vhs_halamanactualvhs.php';</script>";
?>
       