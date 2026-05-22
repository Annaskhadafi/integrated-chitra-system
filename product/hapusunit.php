<?php
//tangkap nilai idunit, inisialisasi $id_unit
$id_unit = $_GET['idunit'];
include "koneksi.php";
//hapus data dengan id_unit=$id_unit dari tabel unit
$query=mysqli_query($sambung, "DELETE from unit where id_unit=$id_unit ");
header ("location: halamansitemaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>