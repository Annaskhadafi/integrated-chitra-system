<?php
include "koneksi.php";
include "auth_check.php";
require_super_admin($koneksi);

// Ambil data dari form dengan null coalescing
$id_user    = $_POST['id_user'] ?? '';
$sn         = mysqli_real_escape_string($koneksi, $_POST['sn'] ?? '');
$name       = mysqli_real_escape_string($koneksi, $_POST['name'] ?? '');
$username   = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$password_input = $_POST['password'] ?? '';

// Update data user
// Catatan: Jika password tidak kosong, baru di-hash dan di-update
$password_sql = "";
if ($password_input !== '') {
    $password_hashed = password_hash($password_input, PASSWORD_DEFAULT);
    $password_sql = ", password = '$password_hashed'";
}

$section    = mysqli_real_escape_string($koneksi, $_POST['section'] ?? '');
$department = mysqli_real_escape_string($koneksi, $_POST['department'] ?? '');
$email      = mysqli_real_escape_string($koneksi, $_POST['email'] ?? '');
$level      = mysqli_real_escape_string($koneksi, $_POST['level'] ?? '');
$site       = mysqli_real_escape_string($koneksi, $_POST['site'] ?? '');

if (empty($id_user)) {
    echo "<script>alert('ID User tidak ditemukan'); window.history.back();</script>";
    exit;
}

$update = mysqli_query($koneksi, "
    UPDATE user SET
        sn = '$sn',
        name = '$name',
        username = '$username'
        $password_sql,
        section = '$section',
        email = '$email',
        level = '$level'
    WHERE id_user = '$id_user'
");

// Redirect ke halaman utama
if ($update) {
    header("Location: adm_halamanusermaster.php");
    exit;
} else {
    echo "<script>alert('Gagal update data'); window.history.back();</script>";
}
?>
