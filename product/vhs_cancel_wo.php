<?php
include "koneksi.php";
$id_stock = $_GET['id_stock'] ?? '';

// Validasi jika perlu
if ($id_stock) {
    // Contoh query update:
    $query = mysqli_query($koneksi6, "UPDATE stock SET status='onsite',wo=NULL,gi=NULL,gi_date=NULL,picgi=NULL WHERE id_stock='$id_stock'");

    if ($query) {
        echo "<script>alert('WO/GI berhasil dibatalkan.'); window.location.href='vhs_halamanstockvhs.php';</script>";
    } else {
        echo "<script>alert('Gagal membatalkan WO.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Data tidak lengkap.'); window.history.back();</script>";
}