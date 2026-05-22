<?php
//tangkap nilai idsupplier, inisialisasi $id_supplier
$id_supplier = $_GET['idsupplier'];
include "koneksi.php";
//hapus data dengan id_supplier=$id_supplier dari tabel tire_supplier
$query=mysqli_query($sambung, "DELETE from supplier where id_supplier=$id_supplier ");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>