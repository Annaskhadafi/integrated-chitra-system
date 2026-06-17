<?php
include "koneksi.php";
$date  = $_POST['date'] ?? '';
$invoice  = $_POST['invoice'] ?? '';
$mrko_raw = $_POST['mrko'] ?? '';
$mrko_parts = explode('|', (string)$mrko_raw);
$mrko = $mrko_parts[0] ?? '';
$id_storeloc = $mrko_parts[1] ?? '';

$query = mysqli_query($koneksi6, "UPDATE stock 
    SET invoice='$invoice'
    WHERE mrko='$mrko' AND id_storeloc='$id_storeloc'");
    
echo "<script>alert('Data updated!'); window.location.href='vhs_halamanstockvhs.php';</script>";
?>