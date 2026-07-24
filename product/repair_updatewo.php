
<?php
$name = $_POST['name'];
$idwo = $_POST['idwo'];
$wo = $_POST['wo'];
$date = $_POST['date'];
$status = $_POST['status'];
$statusfix = ($status == "Complete") ? "Complete" : $status;
$inv = $_POST['inv'];
$invdate = $_POST['invdate'];

$inv = ($inv === null || $inv === '') ? null : $inv;
$invdate = empty($invdate) ? null : $invdate;

include "koneksi.php";

if ($idwo != "") {
    try {
        $stmt = mysqli_prepare($koneksi3, "
            UPDATE work_order 
            SET 
                wo = ?,
                status = ?,
                createby = ?,
                wo_date = ?,
                invoice = ?,
                invoice_date = ?
            WHERE id_wo = ?
        ");
        
        
	mysqli_stmt_bind_param($stmt, "sssssss", $wo, $statusfix, $name, $date, $inv, $invdate, $idwo);

        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        echo "<script>
            alert('Data updated');
            history.go(-1);
        </script>";
        exit;
        
    } catch (Exception $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); history.go(-1);</script>";
        exit;
    }
}
?>

