<?php
include "koneksi.php";
$no = $_POST['no'];
$wo = $_POST['wo'];
$wo_date = $_POST['wo_date'];
$bast = $_POST['bast'];
$po = $_POST['po'];
$invoice = $_POST['invoice'];
$bast_date = $_POST['bast_date'];
$po_date = $_POST['po_date'];
$invoice_date = $_POST['invoice_date'];
$status = $_POST['status'];
// echo $wo;
// echo "<br>".$wo_date;
// echo "<br>".$bast;
// echo "<br>".$bast_date;
// echo "<br>".$po;
// echo "<br>".$po_date;
// echo "<br>".$invoice;
// echo "<br>".$invoice_date;
// echo "<br>".$status;
$perintah = mysqli_query($koneksi4, "UPDATE work_orderr SET work_order='$wo',wo_date='$wo_date',bast='$bast',bast_date='$bast_date',po='$po',invoice='$invoice',po_date='$po_date',invoice_date='$invoice_date' WHERE no=$no");

$get_data = mysqli_query($koneksi4, "SELECT * FROM work_orderr WHERE no=$no");
while ($data = mysqli_fetch_array($get_data)){
// echo $data['work_order'];
// echo "<br>".$data['wo_date'];
// echo "<br>".$data['no_quot'];
// echo "<br>".$data['quot_date'];
// echo "<br>".$data['po'];
// echo "<br>".$data['po_date'];
// echo "<br>".$data['bast'];
// echo "<br>".$data['bast_date'];
// echo "<br>".$data['invoice'];
// echo "<br>".$data['invoice_date'];
// echo "<br>".$data['status'];
// echo "<br>".$data['costumer'];
// echo "<br>".$data['job_desk'];
// echo "<br>".$data['price'];
// echo "<br>".$data['create_by'];

    if($data['no_quot']=='' || $data['quot_date']=='0000-00-00' || $data['po']=='' || $data['po_date']=='0000-00-00' || $data['work_order']=='' || $data['wo_date']=='0000-00-00' || $data['costumer']=='' || $data['job_desk']=='' || $data['price']=='' || $data['price']=='0' || $data['bast']=='' || $data['bast_date']=='0000-00-00' || $data['invoice']=='' || $data['invoice_date']=='0000-00-00' || $data['create_by']==''){
        $status = 1;
    }else{
        $status = 0;
    }
    $perintah2 = mysqli_query($koneksi4, "UPDATE work_orderr SET status='$status' WHERE no=$no");
}

?>
<script>
    history.go(-1);
</script> 
