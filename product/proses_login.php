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

// Lakukan pengambilan data dari database untuk dicocokkan
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");

// Masukkan username dan password ke session
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

// Pemeriksaan kecocokan dengan percabangan
if (mysqli_num_rows($query) == 0) {
    // Jika username dan password tidak cocok
    echo "<script>
            alert('Username atau password salah');
            history.go(-1);
          </script>";
} else {
    $row = mysqli_fetch_assoc($query);  
    
    // update last login
    $id_user = $row['id_user'];
    $perintah = mysqli_query($koneksi, "UPDATE user SET last_login=NOW() WHERE id_user=$id_user");
    
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
