<?php
include "koneksi.php";
$date  = $_POST['date'];
$invoice  = $_POST['invoice'];
list($mrko, $id_storeloc) = explode('|', $_POST['mrko']);

// echo "Tanggal: " . $date . "<br>";
// echo "Invoice: " . $invoice . "<br>";
// echo "MRKO: " . $mrko . "<br>";
// echo "ID Storeloc: " . $id_storeloc . "<br>";
        
$query = mysqli_query($koneksi6, "UPDATE stock 
    SET invoice='$invoice'
    WHERE mrko='$mrko' and id_storeloc='$id_storeloc';");
    
    // echo "UPDATE stock 
    // SET invoice='$invoice'
    // WHERE mrko='$mrko' and id_storeloc='$id_storeloc';"
    
echo "<script>alert('Data updated!'); window.location.href='vhs_halamanstockvhs.php';</script>";
?>