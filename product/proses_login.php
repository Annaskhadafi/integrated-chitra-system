<?php
require_once 'login_helper.php';
safe_session_start();
include 'koneksi.php';

// Cek apakah metode akses adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
            alert('Login first');
            window.location.href = 'login.php';  // Arahkan ke halaman login
          </script>";
    exit;
}

// Tangkap data yang diinput dari form login
$username = strip_tags($_POST['username']);
$password = $_POST['password'];

$ip = get_ip_address();

// 1. Cek apakah IP atau Username sedang dalam kondisi lockout (diblokir)
$lockout_remaining = check_lockout($ip, $username);
if ($lockout_remaining > 0) {
    echo "<script>
            alert('Terlalu banyak percobaan login gagal. Akses diblokir sementara selama " . $lockout_remaining . " menit.');
            window.location.href = 'login.php';
          </script>";
    exit;
}

// 2. Cek apakah CAPTCHA wajib diisi (berdasarkan kegagalan login IP)
$show_captcha = get_failed_count($ip) >= CAPTCHA_THRESHOLD;
if ($show_captcha) {
    if (!isset($_POST['captcha']) || !verify_captcha($_POST['captcha'])) {
        // Catat kegagalan login karena CAPTCHA salah/tidak diisi
        record_failed_attempt($ip, $username);
        
        $lockout_check = check_lockout($ip, $username);
        if ($lockout_check > 0) {
            echo "<script>
                    alert('Jawaban CAPTCHA salah. Batas percobaan habis, akses diblokir selama " . $lockout_check . " menit.');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Jawaban CAPTCHA salah atau kedaluwarsa.');
                    window.location.href = 'login.php';
                  </script>";
        }
        exit;
    }
}

// Lakukan pengambilan data dari database menggunakan prepared statement
$stmt = $koneksi->prepare("SELECT * FROM user WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$query = $stmt->get_result();
$row = $query->fetch_assoc();

// Pemeriksaan kecocokan dengan percabangan
$is_authenticated = false;
if ($row) {
    if (password_verify($password, $row['password'])) {
        $is_authenticated = true;
    } elseif ($password === $row['password']) {
        // Fallback untuk password plain text (legacy)
        $is_authenticated = true;
        
        // Upgrade password ke hash secara otomatis
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt_upgrade = $koneksi->prepare("UPDATE user SET password=? WHERE id_user=?");
        $stmt_upgrade->bind_param("si", $new_hash, $row['id_user']);
        $stmt_upgrade->execute();
    }
}

if (!$is_authenticated) {
    // Jika username tidak ditemukan atau password tidak cocok, catat sebagai kegagalan
    record_failed_attempt($ip, $username);
    
    $lockout_check = check_lockout($ip, $username);
    if ($lockout_check > 0) {
        echo "<script>
                alert('Username atau password salah. Batas percobaan habis, akses diblokir selama " . $lockout_check . " menit.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Username atau password salah.');
                window.location.href = 'login.php';
              </script>";
    }
    exit;
} else {
    // Reset data percobaan login setelah login berhasil
    reset_failed_attempts($ip, $username);
    // Regenerasi ID sesi untuk mencegah session fixation
    session_regenerate_id(true);

    // Masukkan username ke session (jangan simpan password)
    $_SESSION['username'] = $username;
    
    // update last login
    $id_user = $row['id_user'];
    $stmt_update = $koneksi->prepare("UPDATE user SET last_login=NOW() WHERE id_user=?");
    $stmt_update->bind_param("i", $id_user);
    $stmt_update->execute();
    
    // Jika username dan password cocok, buat session dengan nama sesuai dengan username
    if ($row['level'] == 1) {
        // Jika level yang login == 1 (Admin)
        if ($row['section'] == 3) {
            $_SESSION['section'] = $row['section'];
        }
        
        header("Location: halamanics.php");
    } 
    elseif ($row['level'] == 2) {
        // Jika level yang login == 2 (Staff)
        header("Location: halamanics.php");
    }
    elseif ($row['level'] == 910) {
        // Jika level yang login == 910 (Super_adm)
        header("Location: adm_halamanusermaster.php");
    }
    else {
        // Jika level yang login bukan 1 (TE), masuk ke halamanics.php
        header("Location: halamanics.php");
    }
}
?>
