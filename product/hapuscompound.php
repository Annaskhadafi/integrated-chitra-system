<?php
//tangkap nilai idcompound, inisialisasi $id_compound
$id_compound = $_GET['idcompound'];
include "koneksi.php";
//hapus data dengan id_compound=$id_compound dati tabel tire_compound
$query=mysqli_query($sambung, "DELETE from tire_compound where id_compound=$id_compound ");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>