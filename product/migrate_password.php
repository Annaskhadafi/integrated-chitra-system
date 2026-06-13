<?php
include 'koneksi.php';

// Ambil semua user yang passwordnya belum di-hash
// Password hash BCRYPT biasanya diawali dengan '$2y$' dan panjangnya 60 karakter
$query = mysqli_query($koneksi, "SELECT id_user, password FROM user");

$count = 0;
while ($row = mysqli_fetch_assoc($query)) {
    $id = $row['id_user'];
    $pass = $row['password'];

    // Cek apakah sudah di-hash
    if (strpos($pass, '$2y$') !== 0) {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $update = $koneksi->prepare("UPDATE user SET password=? WHERE id_user=?");
        $update->bind_param("si", $hashed, $id);
        $update->execute();
        $count++;
        echo "User ID $id migrated.\n";
    }
}

echo "Total $count users migrated.";
?>