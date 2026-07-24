<?php
include "koneksi.php";
include "auth_check.php";

$sn_tire = $_POST['sn_tire'];

try {
    $stmt = mysqli_prepare($koneksi5, "DELETE FROM tab_warranty WHERE sn_tire = ?");
    mysqli_stmt_bind_param($stmt, "s", $sn_tire);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header("location:halamanwarr.php");
    exit;
    
} catch (Exception $e) {
    echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); history.go(-1);</script>";
    exit;
}
?>

