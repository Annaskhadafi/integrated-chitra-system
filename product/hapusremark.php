<?php
//tangkap nilai idremark, inisialisasi $id_remark
$id_remark = $_GET['idremark'];
include "koneksi.php";
//hapus data dengan id_remark=$id_remark dari tabel tire_remark
$query=mysqli_query($sambung, "DELETE from tire_remark where id_remark=$id_remark ");
header ("location: halamantiremaster.php");
echo "<script>
		history.go(-1);
		</script>";
?>