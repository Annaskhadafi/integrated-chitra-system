<?php
include "koneksi.php"; 
$pic = $_POST['pic'];
$do = $_POST['do'];
$pn = $_POST['pn'];
$date = $_POST['date'];
$qty = $_POST['qty'];
$storeloc = $_POST['storeloc'];

// echo $do."<br>";
// echo $pn."<br>";
// echo $date."<br>";
// echo $qty."<br>";
// echo $pn."<br>";

$loop=0;
while($loop<$qty){	
    $query = mysqli_query($koneksi6, "INSERT INTO stock SET id_part_number=$pn,delivery_date='$date',do='$do',status='onsite',id_storeloc=$storeloc,pic=$pic;");
    // echo "INSERT INTO stock SET id_part_number=$pn,delivery_date='$date',do='$do',status='onsite',id_storeloc=$storeloc;";
    $loop++;
}

echo "<script>alert('Data added!'); window.location.href='vhs_halamanstockvhs.php';</script>";
?>
       