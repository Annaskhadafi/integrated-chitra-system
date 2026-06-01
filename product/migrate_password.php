<?php
include 'koneksi.php';

// Ambil semua pengguna
$query = mysqli_query($koneksi, "SELECT id_user, password FROM user");

echo "Memulai migrasi password...<br>";

while ($row = mysqli_fetch_assoc($query)) {
    $id = $row['id_user'];
    $plain_password = $row['password'];

    // Hashing password
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Update database
    $stmt = $koneksi->prepare("UPDATE user SET password=? WHERE id_user=?");
    $stmt->bind_param("si", $hashed_password, $id);
    
    if ($stmt->execute()) {
        echo "User ID $id berhasil diupdate.<br>";
    } else {
        echo "Gagal update User ID $id.<br>";
    }
}

echo "Migrasi selesai.";
?>
