<?php
//tangkap data id_site, site, dan target
$id_site = $_POST['id_site'] ?? '';
$site = $_POST['site'] ?? '';
$target = $_POST['target'] ?? '';
include "koneksi.php";
//update data yang memiliki id_site = $id_site
$perintah = mysqli_query($sambung, "UPDATE site SET
    site='$site',
    target='$target'    
    WHERE id_site = '$id_site'");

echo "<script>
		history.go(-1);
		</script>";  
?>