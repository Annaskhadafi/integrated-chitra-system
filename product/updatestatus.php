<?php 
$idwo = $_POST['idwo'] ?? '';
$status = $_POST['status'] ?? '';
include "koneksi.php";
$perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='$status' WHERE id_wo = '$idwo'");
?>
<script>
    history.go(-1);
</script>