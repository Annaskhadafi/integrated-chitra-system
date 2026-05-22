<?php
include "koneksi.php";
// $idwo = $_POST['idwo'];
$no = $_POST['no'];
$bast = $_POST['bast'];
$po = $_POST['po'];
$invoice = $_POST['invoice'];
$bastdate = $_POST['bastdate'];
$podate = $_POST['podate'];
$invoicedate = $_POST['invoicedate'];
if($bastdate==""){$bastdate='0001-01-01';}
if($podate==""){$podate='0001-01-01';}
if($invoicedate==""){$invoicedate='0001-01-01';}
if($bast!="" and $po!="" and $invoice!=""){$stat='2';} 
else{$stat='1';}
// echo $idwo;
// echo "<br>".$bast;
// echo "<br>".$po;
// echo "<br>".$invoice;
// echo "<br>".$stat;
// echo "<br>".$bastdate;
// echo "<br>".$podate;
// echo "<br>".$invoicedate;
$perintah = mysqli_query($koneksi4, "UPDATE work_order SET bast='$bast',po='$po',invoice='$invoice',bast_date='$bastdate',po_date='$podate',invoice_date='$invoicedate',status='$stat' WHERE no=$no");
?>
<script>
    history.go(-1);
</script> 
