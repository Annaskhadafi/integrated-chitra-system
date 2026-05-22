<?php
//tangkap nilai idsize, inisialisasi $id_size
$id_size = $_GET['idsize'];
include "koneksi.php";
//hapus data dengan id_size=$id_size dari tabel tire_size
$query=mysqli_query($sambung, "DELETE from tire_size where id_size=$id_size");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>