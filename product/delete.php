<?php
include "koneksi.php";
$sn_tire = $_POST['sn_tire'];
$query = mysqli_query($koneksi5,"DELETE FROM tab_warranty WHERE sn_tire='$sn_tire'");
header("location:halamanwarr.php");
?>