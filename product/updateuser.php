<?php
include "koneksi.php";

// Ambil data dari form
$id_user    = $_POST['id_user'];
$sn         = mysqli_real_escape_string($koneksi, $_POST['sn']);
$name       = mysqli_real_escape_string($koneksi, $_POST['name']);
$username   = mysqli_real_escape_string($koneksi, $_POST['username']);
$password_input = $_POST['password'];

// Cek apakah password yang dikirim sudah berupa hash atau masih plain text
// Jika panjangnya 60 karakter dan diawali $, kemungkinan besar sudah hash (BCRYPT)
// Namun paling aman adalah selalu menganggap input dari form adalah password baru yang perlu di-hash,
// KECUALI jika user tidak mengubahnya. Di form modaledituser.php, password ditampilkan.
$password = password_hash($password_input, PASSWORD_DEFAULT);

$section    = mysqli_real_escape_string($koneksi, $_POST['section']);
$department = mysqli_real_escape_string($koneksi, $_POST['department']);
$email      = mysqli_real_escape_string($koneksi, $_POST['email']);
$level      = mysqli_real_escape_string($koneksi, $_POST['level']);
$site       = mysqli_real_escape_string($koneksi, $_POST['site']); // jika kamu pakai kolom 'site' di tabel user

// Update data user
$update = mysqli_query($koneksi, "
    UPDATE user SET
        sn = '$sn',
        name = '$name',
        username = '$username',
        password = '$password',
        section = '$section',
        email = '$email',
        level = '$level'
    WHERE id_user = $id_user
");

// Redirect ke halaman utama
if ($update) {
    header("Location: adm_halamanusermaster.php");
    exit;
} else {
    echo "<script>alert('Gagal update data'); window.history.back();</script>";
}
?>
