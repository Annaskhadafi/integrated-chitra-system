<?php
include "koneksi.php";
$sn_tire = $_POST['sn_tire'];
// $status = $_POST['status'];
$est_loss = $_POST['est_loss'];
$date_cp = $_POST['date_cp'];
$date_princ = $_POST['date_princ'];
$act_plan = $_POST['act_plan'];
// print_r($_POST);
// echo $date_princ;
// echo "<br>".$date_closed;

// $query = mysqli_query($sambung, "UPDATE tab_warranty SET status = '$status',est_loss = '$est_loss',date_princ ='$date_princ',date_closed ='$date_closed',WHERE sn_tire = '$sn_tire'");
$query = mysqli_query($koneksi5, "UPDATE tab_warranty SET act_plan = '$act_plan', est_loss = '$est_loss', date_cp = '$date_cp', date_princ = '$date_princ' WHERE sn_tire = '$sn_tire' ");

// mysqli_query($koneksi, $query);
header("location:halamanwarr.php");
?>
<!-- <script>
    history.go(-1);
</script>  -->