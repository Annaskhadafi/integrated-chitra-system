<!DOCTYPE html>
	<?php
	include "koneksi.php";
	$loop1=$_POST['loop1'];
	$loop2=$_POST['loop2'];
	$loop3=$_POST['loop3'];
	$loop4=$_POST['loop4'];
	$loop5=$_POST['loop5'];
	$loop6=$_POST['loop6'];
	$loop7=$_POST['loop7'];
	$loop8=$_POST['loop8'];
	?>
	<!-- insert Brand/Manufac -->
	<?php
	$no3=1;
	while($no3 <= $loop3){
		$masterbrand=$_POST['masterbrand'.$no3];
		$query3 = mysqli_query($sambung, "INSERT into tire_manufac (manufac) values ('$masterbrand')");
	$no3++;}
	?>

	<!-- insert pattern -->
 	<?php
	$no2=1;
	while($no2 <= $loop2){
		$masterpattern=$_POST['masterpattern'.$no2];
		$data2=explode(",",$masterpattern);		
		$pattern2=$data2[0];
		$brand2=$data2[1];		
        $perintah2a = mysqli_query($sambung, "SELECT id_manufac from tire_manufac where manufac like '%$brand2%'");
        $kunci2a = mysqli_fetch_array($perintah2a);
        $idmanufac=$kunci2a['id_manufac'];        
		$query2 = mysqli_query($sambung, "INSERT into tire_pattern (pattern,manufac) values ('$pattern2','$idmanufac')");
	$no2++;}
	?>

	<!-- Insert Size -->
	<?php
	$no1=1;
	while($no1 <= $loop1){
		$master=$_POST['master'.$no1];
		$data1=explode(",",$master);
		$size1=$data1[0];
		$pattern1=$data1[1];
		$brand1=$data1[2];
		$perintah1a = mysqli_query($sambung, "SELECT id_pattern from tire_pattern where pattern like '%$pattern1%'");
        $kunci1a = mysqli_fetch_array($perintah1a);
        $idpattern=$kunci1a['id_pattern'];
        $query1 = mysqli_query($sambung, "INSERT into tire_size (size,pattern) values ('$size1','$idpattern')");
	$no1++;}
	?>

	<!-- Insert Compound -->
  	<?php
	$no4=1;
	while($no4 <= $loop4){
		$compound=$_POST['compound'.$no4];
		$query4 = mysqli_query($sambung, "INSERT into tire_compound (compound) values ('$compound')");
	$no4++;}
	?>

	<!-- Insert Supplier -->
 	<?php
	$no5=1;
	while($no5 <= $loop5){
		$supplier=$_POST['supplier'.$no5];
		$query5 = mysqli_query($sambung, "INSERT into supplier (supplier) values ('$supplier')");
	$no5++;}
	?>

	<!-- Insert Inventory -->
	<?php
	$no6=1;
	while($no6 <= $loop6){
		$inventory=$_POST['inventory'.$no6];
		$data6=explode(",",$inventory);
		$idinventory6=$data6[0];
		$sn6=$data6[1];
		$size6=$data6[2];
		$pattern6=$data6[3];
		$compound6=$data6[4];
		$status6=$data6[5];
		$lifetime6=$data6[6];
		$supplier6=$data6[7];
		$idsite6=$data6[8];
		$date6=$data6[9];
		$perintah6a = mysqli_query($sambung, "SELECT id_pattern from tire_pattern where pattern like '%$pattern6%'");
        $kunci6a = mysqli_fetch_array($perintah6a);
        $idpattern6=$kunci6a['id_pattern'];
		$perintah6b = mysqli_query($sambung, "SELECT id_size from tire_size where size like '%$size6%' and pattern=$idpattern6");
        $kunci6b = mysqli_fetch_array($perintah6b);
        $idsize6=$kunci6b['id_size'];//id size
		$perintah6c = mysqli_query($sambung, "SELECT id_compound from tire_compound where compound like '%$compound6%'");
        $kunci6c = mysqli_fetch_array($perintah6c);
        $idcompound6=$kunci6c['id_compound'];//id compound
		$perintah6d = mysqli_query($sambung, "SELECT id_supplier from supplier where supplier like '%$supplier6%'");
        $kunci6d = mysqli_fetch_array($perintah6d);
        $idsupplier6=$kunci6d['id_supplier'];//id supplier
		$perintah6e = mysqli_query($sambung, "SELECT id_status from tire_status where status like '%$status6%'");
        $kunci6e = mysqli_fetch_array($perintah6e);
        $idstatus6=$kunci6e['id_status'];//id status
        $query6 = mysqli_query($sambung, "INSERT into tire_inventory (id_inventory,sn,size,compound,supplier,site,status,lifetime,date_receive) values ('$idinventory6','$sn6','$idsize6','$idcompound6','$idsupplier6','$idsite6','$idstatus6','$lifetime6','$date6')");
	$no6++;}
	?>

	<!-- Insert unit site -->
 	<?php
	$no7=1;
	while($no7 <= $loop7){
		$unitsite=$_POST['unitsite'.$no7];
		$data7=explode(",",$unitsite);
		$unitnumber7=$data7[0];
		$hm7=$data7[1];
		$site7=$data7[2];
		$query7 = mysqli_query($sambung, "INSERT into unit_site (unit_number,hm,site) values ('$unitnumber7','$hm7','$site7')");
	$no7++;}
	?>

	<!-- Insert movement -->
 	<?php
	$no8=1;
	while($no8 <= $loop8){
		$movement=$_POST['movement'.$no8];
		$data8=explode(",",$movement);
		$idmovement8=$data8[0];//id_movement
		$unitnumber8=$data8[1];
		$sn8=$data8[2];
		$job8=$data8[3];//job
		$hm8=$data8[4];//hm_on_job
		$posisi8=$data8[5];
		$date8=$data8[6];//date
		$alasan8=$data8[7];//alasan
		$status8=$data8[8];
		$lifetime8=$data8[9];//life_on_job
		$perintah8a = mysqli_query($sambung, "SELECT id_unit_site from unit_site where unit_number like '%$unitnumber8%'");
        $kunci8a = mysqli_fetch_array($perintah8a);
        $idunit8=$kunci8a['id_unit_site'];//id_unit_site
        $perintah8b = mysqli_query($sambung, "SELECT id_inventory from tire_inventory where sn like '%$sn8%'");
        $kunci8b = mysqli_fetch_array($perintah8b);
        $idinvent8=$kunci8b['id_inventory'];//id_inventory
        $perintah8c = mysqli_query($sambung , "SELECT id_status from tire_status where status like '%$status8%'");
        $kunci8c = mysqli_fetch_array($perintah8c);
        $idstat8=$kunci8c['id_status'];//id_status
        $query8 = mysqli_query($sambung,"INSERT into tire_movement (id_movement,unit_number,sn,job,hm_on_job,posisi,date,alasan,status,life_on_job) values ('$idmovement8','$idunit8','$idinvent8','$job8','$hm8','$posisi8','$date8','$alasan8','$idstat8','$lifetime8')");
	$no8++;}
	echo "<script>
	alert('Data Submitted');
 	history.go(-1);
 	</script>";
	?>



 