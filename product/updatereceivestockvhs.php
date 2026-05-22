<?php
$do=$_POST['do'];
$idforecast=$_POST['idforecast'];
$deliverydate=$_POST['deliverydate'];
echo $do."<br>";
echo $idforecast."<br>";
echo $deliverydate."<br>";
include "koneksi.php";

$perintahmodal=mysqli_query($koneksi6, "SELECT * from stock where do='$do' AND id_forecast=$idforecast"); 

while($datamodal = mysqli_fetch_array($perintahmodal)){
$idstock=$datamodal['id_stock'];
// echo $datamodal['id_stock']."<br>";
$perintah = mysqli_query($koneksi6,"UPDATE stock set received_date='$deliverydate',status='Onsite' where id_stock=$idstock");
}

$perintahonsite=mysqli_query($koneksi6, "SELECT * from stock where id_forecast=$idforecast and status='Onsite'"); 
$jumlahonsite = mysqli_num_rows($perintahonsite);
$perintahjumlah=mysqli_query($koneksi6, "SELECT * from stock where id_forecast=$idforecast and status='Onsite'"); 
$jumlah = mysqli_num_rows($perintahjumlah);
if($jumlahonsite=$jumlah){
$perintah = mysqli_query($koneksi6,"UPDATE forecast set status='Done' where id_forecast=$idforecast");
}



// echo $jumlah;
 echo"<script>
		history.go(-1);
		</script>";  
?>