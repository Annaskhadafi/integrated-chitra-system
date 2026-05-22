<?php
include "koneksi.php";
$idwo = $_POST['idwo'];
$wo = $_POST['wo'];
$repair_type = $_POST['repair_type'];
$bast = $_POST['bast'];
$po = $_POST['po'];
$invoice = $_POST['invoice'];
$bastdate = $_POST['bastdate'];
if($bastdate==''){$bastdate='1000-01-01';}
$podate = $_POST['podate'];
if($podate==''){$podate='1000-01-01';}
$invoicedate = $_POST['invoicedate'];
if($invoicedate==''){$invoicedate='1000-01-01';}
// echo $bastdate;
// echo "<br>".$bastdate;
// echo "<br>".$bastdate;

$perintah = mysqli_query($koneksi3, "UPDATE work_order SET id_wo='$idwo',wo='$wo',repair_type='$repair_type',bast='$bast',po='$po',invoice='$invoice',bast_date='$bastdate',po_date='$podate',invoice_date='$invoicedate' WHERE id_wo=$idwo");
?>
<script>
    history.go(-1);
</script> 
