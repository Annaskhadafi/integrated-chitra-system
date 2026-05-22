<?php
$id_inventory = $_POST['id_inventory'];
$sn = $_POST['sn'];
$size = $_POST['size'];
$compound = $_POST['compound'];
$supplier = $_POST['supplier'];
$site = $_POST['site'];
$status = $_POST['status'];
$lifetime = $_POST['lifetime'];
$rtd = $_POST['rtd'];
$price = $_POST['price'];
$date = $_POST['date'];
include "koneksi.php";
$perintah = mysqli_query($sambung, "UPDATE tire_inventory set
	id_inventory='$id_inventory',
	sn='$sn',
	size= '$size',
	compound= '$compound',
    supplier='$supplier',
    site='$site',
    status='$status',
    lifetime='$lifetime',
    rtd='$rtd',
    price='$price',
    date_receive='$date'    
    where id_inventory=$id_inventory");
$perintah2 = mysqli_query($sambung, "UPDATE tire_movement 
    set status='$status'
    where sn=$id_inventory 
    order by id_movement 
    desc limit 1");
 echo "<script>
		history.go(-1);
		</script>";  
?>