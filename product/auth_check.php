<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    if (headers_sent()) {
        echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    } else {
        header("Location: login.php");
    }
    exit;
}

// Fungsi untuk mengecek level user
function check_user_level($koneksi, $allowed_levels) {
    if (!isset($_SESSION['username'])) {
        return false;
    }
    
    $username = $_SESSION['username'];
    $stmt = mysqli_prepare($koneksi, "SELECT level FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    
    if (!$user) {
        return false;
    }
    
    return in_array(intval($user['level']), $allowed_levels);
}

// Fungsi untuk membatasi akses hanya untuk Super Admin (level 910)
function require_super_admin($koneksi) {
    if (!check_user_level($koneksi, [910])) {
        http_response_code(403);
        echo "<script>alert('Akses ditolak. Halaman ini memerlukan hak akses Super Admin.'); window.location.href='halamanics.php';</script>";
        exit;
    }
}
?>
