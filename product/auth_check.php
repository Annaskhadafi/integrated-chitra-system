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
    if (!check_user_level($koneksi, array(910))) {
        http_response_code(403);
        echo "<script>alert('Akses ditolak. Halaman ini memerlukan hak akses Super Admin.'); window.location.href='halamanics.php';</script>";
        exit;
    }
}

/**
 * Membatasi akses halaman berdasarkan daftar level pengguna yang diperbolehkan.
 * Contoh penggunaan:
 *   require_user_levels($koneksi, array(1, 910));       // Admin & Super Admin
 *   require_user_levels($koneksi, array(1, 3, 910));    // Admin, Managerial & Super Admin
 *   require_user_levels($koneksi, array(910));           // Super Admin saja
 * 
 * Level referensi: 1=Admin, 2=Staff, 3=Managerial, 910=Super Admin
 */
function require_user_levels($koneksi, $allowed_levels) {
    if (!check_user_level($koneksi, $allowed_levels)) {
        http_response_code(403);
        // Buat pesan level yang diizinkan untuk informasi
        $level_names = array();
        $map = array(1 => 'Admin', 2 => 'Staff', 3 => 'Managerial', 910 => 'Super Admin');
        foreach ($allowed_levels as $lvl) {
            $level_names[] = isset($map[$lvl]) ? $map[$lvl] : "Level $lvl";
        }
        $allowed_str = implode(', ', $level_names);
        echo "<script>alert('Akses ditolak. Halaman ini hanya dapat diakses oleh: $allowed_str.'); window.location.href='halamanics.php';</script>";
        exit;
    }
}
?>
