<?php
$idsite = $_GET['idsite'];
include "koneksi.php";
$query=mysqli_query($koneksi3, "DELETE from customer where id_customer=$idsite ");
echo "<script>
		history.go(-1);
		</script>";
?>