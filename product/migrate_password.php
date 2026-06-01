 1 <?php
    2 include 'koneksi.php';
    3
    4 // Ambil semua pengguna
    5 $query = mysqli_query($koneksi, "SELECT id_user, password FROM user");
    6
    7 echo "Memulai migrasi password...<br>";
    8
    9 while ($row = mysqli_fetch_assoc($query)) {
   10     $id = $row['id_user'];
   11     $plain_password = $row['password'];
   12
   13     // Hashing password
   14     $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
   15
   16     // Update database
   17     $stmt = $koneksi->prepare("UPDATE user SET password=? WHERE id_user=?");
   18     $stmt->bind_param("si", $hashed_password, $id);
   19     
   20     if ($stmt->execute()) {
   21         echo "User ID $id berhasil diupdate.<br>";
   22     } else {
   23         echo "Gagal update User ID $id.<br>";
   24     }
   25 }
   26
   27 echo "Migrasi selesai.";
   28 ?>