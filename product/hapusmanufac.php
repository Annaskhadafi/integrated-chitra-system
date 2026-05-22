<?php
//tangkap nilai id,manufac, inisialisasi $id_manufac
$id_manufac = $_GET['idmanufac'];
include "koneksi.php";
//hapus data dengan id_manufac=$id_manufac dari tabel tire_manufac
$query=mysqli_query($sambung, "DELETE from tire_manufac where id_manufac=$id_manufac ");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>