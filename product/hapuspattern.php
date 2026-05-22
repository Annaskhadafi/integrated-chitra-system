<?php
//tangkap nilai idpattern, inisialisasi $id_pattern
$id_pattern = $_GET['idpattern'];
include "koneksi.php";
//hapus data dengan id_pattern=$id_pattern dari tabel tire_pattern
$query=mysqli_query($sambung, "DELETE from tire_pattern where id_pattern=$id_pattern");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>