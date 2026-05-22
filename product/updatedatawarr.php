<?php
include "koneksi.php";
$sn_tire = $_POST['sn_tire'];
$status = $_POST['status'];
$est_loss = $_POST['est_loss'];
$date_princ = $_POST['date_princ'];
$date_closed = $_POST['date_closed'];
// echo $date_princ;
// echo "<br>".$date_closed;

// $query = mysqli_query($sambung, "UPDATE tab_warranty SET status = '$status',est_loss = '$est_loss',date_princ ='$date_princ',date_closed ='$date_closed',WHERE sn_tire = '$sn_tire'");
$query = mysqli_query($sambung, "UPDATE tab_warranty SET status = '$status', est_loss = '$est_loss', date_princ = '$date_princ', date_closed = '$date_closed' WHERE sn_tire = '$sn_tire' ");

// mysqli_query($koneksi, $query);
header("location:halamanwarr.php");
?>
<!-- <script>
    history.go(-1);
</script>  -->