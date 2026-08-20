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

/**
 * Mendapatkan data user yang terautentikasi (level dan section)
 */
function get_authenticated_user_info($koneksi) {
    static $cached_user = null;
    if ($cached_user !== null) {
        return $cached_user;
    }

    if (!isset($_SESSION['username'])) {
        return null;
    }

    $username = $_SESSION['username'];
    $stmt = mysqli_prepare($koneksi, "SELECT level, section FROM user WHERE username = ?");
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    $cached_user = $user ? $user : false;
    return $cached_user;
}

// Fungsi untuk mengecek level user
function check_user_level($koneksi, $allowed_levels) {
    $user = get_authenticated_user_info($koneksi);
    if (!$user) {
        return false;
    }
    return in_array(intval($user['level']), array_map('intval', (array)$allowed_levels));
}

// Fungsi untuk membatasi akses hanya untuk Super Admin (level 910)
function require_super_admin($koneksi) {
    require_access($koneksi, array(910));
}

/**
 * Membatasi akses halaman berdasarkan level pengguna.
 */
function require_user_levels($koneksi, $allowed_levels) {
    require_access($koneksi, $allowed_levels);
}

/**
 * Membatasi akses halaman berdasarkan Level dan/atau Section (Divisi) Pengguna.
 * - Super Admin (level 910) selalu memiliki akses ke semua halaman.
 * - Jika allowed_levels diisi, user harus memiliki salah satu level tersebut.
 * - Jika allowed_sections diisi, user harus memiliki salah satu section tersebut.
 */
function require_access($koneksi, $allowed_levels = array(), $allowed_sections = array()) {
    $user = get_authenticated_user_info($koneksi);
    
    if (!$user) {
        header("Location: login.php");
        exit;
    }

    $user_level = intval($user['level']);
    $user_section = intval($user['section']);

    // Super Admin selalu diperbolehkan
    if ($user_level === 910) {
        return true;
    }

    $level_allowed = empty($allowed_levels) || in_array($user_level, array_map('intval', (array)$allowed_levels));
    $section_allowed = empty($allowed_sections) || in_array($user_section, array_map('intval', (array)$allowed_sections));

    if (!$level_allowed || !$section_allowed) {
        http_response_code(403);
        if (headers_sent()) {
            echo "<script>alert('Akses Ditolak: Anda tidak memiliki wewenang untuk mengakses halaman ini.'); window.location.href='halamanics.php';</script>";
        } else {
            header("Location: halamanics.php");
        }
        exit;
    }

    return true;
}
?>
