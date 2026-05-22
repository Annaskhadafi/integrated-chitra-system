<?php
//tangkap nilai idunit, inisialisasi $id_unit
$idcust = $_GET['idcust'];
include "koneksi.php";
//hapus data dengan id_unit=$id_unit dari tabel unit
$query=mysqli_query($sambung, "DELETE from customer_data where id_cust=$idcust");
echo "<script>
		history.go(-1);
		</script>";
?>