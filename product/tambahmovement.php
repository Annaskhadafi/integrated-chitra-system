<?php
//tangkap seluruh nilai dari modal install/remove
$unitnumber = $_POST['unitnumber'];
$sn = $_POST['sn'];
$job = $_POST['job'];
$hm = $_POST['hm'];
$rtd = $_POST['rtd'];
$position = $_POST['position'];
$status = $_POST['status'];
$explanation = $_POST['explanation'];
$date = $_POST['date'];
$finishdate = $_POST['finishdate'];
$desc = $_POST['desc'];
$tire = $_POST['tire'];
$rimsn = $_POST['rimsn'];
$rimlama = $_POST['rimlama'];
if ($rimsn>0) {
	$component=1;
}
else{
	$component=0;
}
// echo "unit number".$unitnumber;
// echo "<br>sn".$sn;
// echo "<br>job".$job;
// echo "<br>hm".$hm;
// echo "<br>rtd".$rtd;
// echo "<br>position".$position;
// echo "<br>status".$status;
// echo "<br>explanation".$explanation;
// echo "<br>date".$date;
// echo "<br>finishdate".$finishdate;
// echo "<br>desc".$desc;
// echo "<br>tire".$tire;
// echo "<br>rimsn".$rimsn;
// echo "<br>component".$component;
// echo "<br>rim lama".$rimlama;

include "koneksi.php";
//jika sn,unitnumber,date atau status tidak kosong jalankan perintah
if ($sn!="" and $unitnumber!="" and $date!="" and $status!="") {
	//cari id unit
	$cekdulu= mysqli_query ($sambung, "SELECT * from unit_site where id_unit_site='$unitnumber'"); 
	$cek = mysqli_fetch_array($cekdulu);
	// jika $job=1 (install) jalankan perintah
	if($job==1) {
		if($component==1){ //install tire dan rim
			//cari data tire yang diinstal
			$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn'");
			$datainstall = mysqli_fetch_array($cektire);
			$cekrim = mysqli_query ($sambung, "SELECT * from rim_inventory where id_rim_inventory='$rimsn'");
			$datainstallrim = mysqli_fetch_array($cekrim);
			$umurrim=$datainstallrim['rim_lifetime'];
			$umur=$datainstall['lifetime'];
			$rtdpasang=$datainstall['rtd'];
			//insert data tire dan data movement ke dalam tabel tire_movement
			$queryinstallmovement = mysqli_query($sambung, "INSERT INTO `test`.`tire_movement` (`id_movement`, `unit_number`, `sn`, `job`, `hm_on_job`, `posisi`, `date`, `finish_date`, `alasan`, `status`, `rtd_on_job`, `life_on_job`, `component`, `id_rim_inventory`, `rim_lifetime_on_job`, `desc`) VALUES ('','$unitnumber','$sn','$job','$hm','$position','$date','$finishdate','$explanation','$status','$rtdpasang','$umur','$component','$rimsn','$umurrim','$desc')");
			//ubah status tire di tire_inventory sesuai $status
			$queryinstalltire = mysqli_query($sambung, "UPDATE tire_inventory set status='$status' where id_inventory=$sn");
			$queryinstalltire2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_status='$status' where id_rim_inventory=$rimsn");
	 		echo"<script>
	 		history.go(-1);
			</script>";
		}
		else{ //install tire only (vertical)
			//cari data tire yang diinstal
			$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn'");
			$datainstall = mysqli_fetch_array($cektire);
			$umur=$datainstall['lifetime'];
			$cekrim = mysqli_query ($sambung, "SELECT * from rim_inventory where id_rim_inventory='$rimlama'");
			$datainstallrim = mysqli_fetch_array($cekrim);
			$umurrim=$datainstallrim['rim_lifetime'];
			//insert data install tire baru di tire_movement
			$queryinstallmovement = mysqli_query($sambung, "INSERT INTO `test`.`tire_movement` (`id_movement`, `unit_number`, `sn`, `job`, `hm_on_job`, `posisi`, `date`, `finish_date`, `alasan`, `status`, `rtd_on_job`, `life_on_job`, `component`, `id_rim_inventory`, `rim_lifetime_on_job`,`desc`) VALUES ('','$unitnumber','$sn','$job','$hm','$position','$date','$finishdate','$explanation','$status','$rtdpasang','$umur','$component','$rimlama','$umurrim','$desc')");
			//insert data disassembly di tabel assembly
			$queryinstallvertical = mysqli_query($sambung, "INSERT INTO `test`.`assembly` (`id_assembly`,`id_inventory`,`tire_lifetime`,`rim_lifetime`,`id_rim_inventory`,`job_assembly`,`assembly_date`) VALUES ('','$sn','$umur','$umurrim','$rimlama','1','$date')");
			//update status dan RTD tire yang dilepas
			$queryinstalltire = mysqli_query($sambung, "UPDATE tire_inventory set status='$status',rim_assembly='1' where id_inventory=$sn");
			$queryinstalltire2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_status='2',rim_assembly='1' where id_rim_inventory=$rimlama");
	 		echo"<script>
	 		history.go(-1);
			</script>";
		}
	}
	// jika $job!=1 (remove) jalankan perintah
	else {
		if($component==1){//remove tire & rim
			//cari umur baru dengan hitung selisih antara HM pasang dengan HM lepas
			$life=$hm-$cek['hm'];
			$no=1;
			//lakukan perulangan sebanyak $tire (jumlah tire pada unit)
			while($no<=$tire){
				//cari data pengerjaan terakhir tire yang akan di remove
				$cekinstall = mysqli_query ($sambung, "SELECT * from tire_movement where unit_number=$unitnumber and posisi=$no ORDER BY id_movement DESC LIMIT 1");		
				$idinstall=	mysqli_fetch_array($cekinstall);
				$job2 = $idinstall['job'];			
				$sn2 = $idinstall['sn'];
				// jika pengerjaan terakhir adalah install,jalankan perintah
				if($job2==1){
					//cari data tire yang akan di remove
					$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn2'");
					$datainstall = mysqli_fetch_array($cektire);
				    $cekrim=mysqli_query($sambung, "SELECT * from assembly a,rim_inventory b 
				    									where a.id_inventory=$sn2 and a.id_rim_inventory=b.id_rim_inventory 
				    									order by a.id_assembly desc limit 1");
				    $datainstallrim = mysqli_fetch_array($cekrim);
					$idrim = $datainstallrim['id_rim_inventory'];
					//tambahkan umur tire dengan umur baru (selisih antara HM pasang dengan HM lepas)
					$umur=$life+$datainstall['lifetime'];
					//tambahkan umur rim dengan umur baru (selisih antara HM pasang dengan HM lepas)
					$umurrim=$life+$datainstallrim['rim_lifetime'];
					//update umur tire
					$query2 = mysqli_query($sambung, "UPDATE tire_inventory set lifetime=$umur where id_inventory=$sn2");
					//update umur rim
					$query2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_lifetime=$umurrim where id_rim_inventory=$idrim");
				}
				$no++;
			}
			$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn'");
			$datainstall = mysqli_fetch_array($cektire);
			$umur=$datainstall['lifetime'];
			$rtd=$datainstall['rtd'];
			$cekrim = mysqli_query ($sambung, "SELECT * from rim_inventory where id_rim_inventory='$rimsn'");
			$datainstallrim = mysqli_fetch_array($cekrim);
			$umurrim=$datainstallrim['rim_lifetime'];
			//insert data remove tire baru di tire_movement
			$queryinstallmovement = mysqli_query($sambung, "INSERT INTO `test`.`tire_movement` (`id_movement`, `unit_number`, `sn`, `job`, `hm_on_job`, `posisi`, `date`, `finish_date`, `alasan`, `status`, `rtd_on_job`, `life_on_job`, `component`, `id_rim_inventory`, `rim_lifetime_on_job`,`desc`) VALUES ('','$unitnumber','$sn','$job','$hm','$position','$date','$finishdate','$explanation','$status','$rtd','$umur','$component','$rimsn','$umurrim','$desc')");
			
			if($status==4||$status==5){
				//update status dan RTD tire yang dilepas
				$queryremovenormal = mysqli_query($sambung, "UPDATE tire_inventory set status='$status',rtd=$rtd,rim_assembly='2' where id_inventory=$sn");
				$queryremovenormal1 = mysqli_query($sambung, "UPDATE rim_inventory set rim_status='3',rim_assembly='2' where id_rim_inventory=$rimsn");
				//insert data disassembly di tabel assembly
				$queryremovenormal2 = mysqli_query($sambung, "INSERT INTO `test`.`assembly` (`id_assembly`,`id_inventory`,`tire_lifetime`,`rim_lifetime`,`id_rim_inventory`,`job_assembly`,`assembly_date`) VALUES ('','$sn','$umur','$umurrim','$rimsn','2','$date')");
			}
			else{
				//update status dan RTD tire yang dilepas
				$queryremovenormal = mysqli_query($sambung, "UPDATE tire_inventory set status='$status',rtd=$rtd where id_inventory=$sn");
				$queryremovenormal1 = mysqli_query($sambung, "UPDATE rim_inventory set rim_status='$status' where id_rim_inventory=$rimsn");
			}
			//update HM unit site
			$query6 = mysqli_query($sambung, "UPDATE unit_site set hm='$hm' where id_unit_site=$unitnumber");
			echo"<script>
	 		history.go(-1);
			</script>";
		}
		else{ //remove tire only (vertical)
			//cari umur baru dengan hitung selisih antara HM pasang dengan HM lepas
			$life=$hm-$cek['hm'];
			$no=1;
			//lakukan perulangan sebanyak $tire (jumlah tire pada unit)
			while($no<=$tire){
				//cari data pengerjaan terakhir tire yang akan di remove
				$cekinstall = mysqli_query ($sambung, "SELECT * from tire_movement where unit_number=$unitnumber and posisi=$no ORDER BY id_movement DESC LIMIT 1");		
				$idinstall=	mysqli_fetch_array($cekinstall);
				$job2 = $idinstall['job'];			
				$sn2 = $idinstall['sn'];
				// jika pengerjaan terakhir adalah install,jalankan perintah
				if($job2==1){
					//cari data tire yang akan di remove
					$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn2'");
					$datainstall = mysqli_fetch_array($cektire);
				    $cekrim=mysqli_query($sambung, "SELECT * from assembly a,rim_inventory b 
				    									where a.id_inventory=$sn2 and a.id_rim_inventory=b.id_rim_inventory 
				    									order by a.id_assembly desc limit 1");
				    $datainstallrim = mysqli_fetch_array($cekrim);
					$idrim = $datainstallrim['id_rim_inventory'];
					//tambahkan umur tire dengan umur baru (selisih antara HM pasang dengan HM lepas)
					$umur=$life+$datainstall['lifetime'];
					//tambahkan umur rim dengan umur baru (selisih antara HM pasang dengan HM lepas)
					$umurrim=$life+$datainstallrim['rim_lifetime'];
					echo "-".$umurrim;
					//update umur tire
					$query2 = mysqli_query($sambung, "UPDATE tire_inventory set lifetime=$umur where id_inventory=$sn2");
					//update umur rim
					$query2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_lifetime=$umurrim where id_rim_inventory=$idrim");
				}
				$no++;
			}
			$cektire = mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory='$sn'");
			$datainstall = mysqli_fetch_array($cektire);
			$umur=$datainstall['lifetime'];
			$cekrim = mysqli_query ($sambung, "SELECT * from rim_inventory where id_rim_inventory='$rimlama'");
			$datainstallrim = mysqli_fetch_array($cekrim);
			$umurrim=$datainstallrim['rim_lifetime'];
			//insert data remove tire baru di tire_movement
			$queryinstallmovement = mysqli_query($sambung, "INSERT INTO `test`.`tire_movement` (`id_movement`, `unit_number`, `sn`, `job`, `hm_on_job`, `posisi`, `date`, `finish_date`, `alasan`, `status`, `rtd_on_job`, `life_on_job`, `component`, `id_rim_inventory`, `rim_lifetime_on_job`,`desc`) VALUES ('','$unitnumber','$sn','$job','$hm','$position','$date','$finishdate','$explanation','$status','$rtd','$umur','$component','$rimlama','$umurrim','$desc')");
			//insert data disassembly di tabel assembly
			$queryremovevertical = mysqli_query($sambung, "INSERT INTO `test`.`assembly` (`id_assembly`,`id_inventory`,`tire_lifetime`,`rim_lifetime`,`id_rim_inventory`,`job_assembly`,`assembly_date`) VALUES ('','$sn','$umur','$umurrim','$rimlama','2','$date')");
			//update status dan RTD tire yang dilepas
			$queryinstalltire = mysqli_query($sambung, "UPDATE tire_inventory set status='$status',rtd=$rtd,rim_assembly='2' where id_inventory=$sn");
			$queryinstalltire2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_status='2',rim_assembly='2' where id_rim_inventory=$rimlama");
			//update HM unit site
			$query6 = mysqli_query($sambung, "UPDATE unit_site set hm='$hm' where id_unit_site=$unitnumber");
			echo"<script>
	 		history.go(-1);
			</script>";

		}
	}
}
else {
 	echo "<script>
 	alert ('Please fill the blank form');
 	history.go(-1);
 	</script>";
}
?>