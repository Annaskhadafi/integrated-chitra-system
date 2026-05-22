<?php
$name = $_POST['name'];
$idwo = $_POST['idwo'];
$wo = $_POST['wo'];
$date = $_POST['date'];
$status=$_POST['status'];
$statusfix = ($status == "Complete") ? "Complete" : $status;
$inv=$_POST['inv'];
$invdate=$_POST['invdate'];
$inv_sql = ($inv === null) ? "NULL" : "'$inv'";
$invdate_sql = empty($invdate) ? "NULL" : "'$invdate'";
include "koneksi.php";

// echo $name."<br>";
// echo $idwo."<br>";
// echo $wo."<br>";
// echo $date."<br>";
if ($idwo!="") {
    $perintah = mysqli_query($koneksi3, "
    UPDATE work_order 
    SET 
        wo='$wo',
        status='$statusfix',
        createby='$name',
        wo_date='$date',
        invoice=$inv_sql,
        invoice_date=$invdate_sql
    WHERE id_wo='$idwo'
");
// $query="UPDATE work_order 
//     SET 
//         wo='$wo',
//         status='Progress',
//         createby='$name',
//         wo_date='$date',
//         invoice=$inv_sql,
//         invoice_date=$invdate_sql
//     WHERE id_wo='$idwo'";
//     echo $query;
}
else{
    echo "<script>
    alert ('Please fill the blank page');
    history.go(-1);
    </script>";
}
    echo "<script>
    alert ('WO submited.');
    history.go(-1);
    </script>";
?>