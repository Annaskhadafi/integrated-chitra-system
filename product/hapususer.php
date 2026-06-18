<?php
include "koneksi.php";
include "auth_check.php";
require_super_admin($koneksi);

if (isset($_GET['iduser']) && is_numeric($_GET['iduser'])) {
    $iduser = intval($_GET['iduser']);

    $stmt = mysqli_prepare($koneksi, "DELETE FROM user WHERE id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $iduser);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('User berhasil dihapus'); window.location.href = 'adm_halamanusermaster.php';</script>";
    } else {
        echo "<script>alert('User tidak ditemukan atau gagal dihapus'); window.location.href = 'adm_halamanusermaster.php';</script>";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('ID tidak valid'); window.location.href = 'adm_halamanusermaster.php';</script>";
}
?>
