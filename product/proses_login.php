<?php
// ini_set('session.cookie_domain', '.chitraparatama.co.id');
ini_set('session.cookie_path', '/');
session_start();
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

// Lakukan pengambilan data dari database menggunakan prepared statement
$stmt = $koneksi->prepare("SELECT * FROM user WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$query = $stmt->get_result();
$row = $query->fetch_assoc();

// Pemeriksaan kecocokan dengan percabangan
if (!$row || !password_verify($password, $row['password'])) {
    // Jika username tidak ditemukan atau password tidak cocok
    echo "<script>
            alert('Username atau password salah');
            history.go(-1);
          </script>";
    exit;
} else {
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
