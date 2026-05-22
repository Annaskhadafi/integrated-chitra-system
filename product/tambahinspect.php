<?php
include "koneksi.php";
//tangkap nilai idunit yang diinspect
$idunit = $_POST['idunit'];
//tangkap nilai hm yang diinspect
$hm = $_POST['hm'];
$date = $_POST['date'];
//cek jumlah tire, inisialisasi sebagai $tire
$cektire= mysqli_query ($sambung, "SELECT * from unit_site a,unit b where a.unit=b.id_unit and a.id_unit_site=$idunit");
$datacektire=mysqli_fetch_array($cektire);
$tire=$datacektire['tire'];

	
if ($hm !="" AND $date !="" ) {
	$cekunit= mysqli_query ($sambung, "SELECT * from unit_site where id_unit_site=$idunit");
	$datacekunit=mysqli_fetch_array($cekunit);
	//cek selisih HM lama dengan HM saat inspect
	$life=$hm-$datacekunit['hm'];
	//lakukan perulangan sebanyak $tire (jumlah tire pada unit)
	for($i = 1; $i <= $tire; $i++){
		${'sn' . $i}=$_POST{'sn' . $i};
		${'rtd' . $i}=$_POST{'rtd' . $i};
		${'pressure' . $i}=$_POST{'pressure' . $i};
		${'remark' . $i}=$_POST{'remark' . $i};
		//select tire dengan id_inventory = $sn1,$sn2,... dst sesuai perulangan  
		$perintah=mysqli_query ($sambung, "SELECT * from tire_inventory where id_inventory=${'sn' . $i}");
		$data=mysqli_fetch_array($perintah);
		$perintah2=mysqli_query ($sambung, "SELECT * from tire_remark where id_remark=${'remark' . $i}");
		$data2=mysqli_fetch_array($perintah2);
		if($data2['rating']==""){
			$rating='A';	
		}
		else{$rating=$data2['rating'];}
		//tambahkan lifetime tire dengan selisih HM
		$lifetotal1=$life+$data['lifetime'];
		//update lifetime tire dengan lifetime yang telah ditambah dengan selisih HM 
		$total = mysqli_query($sambung, "UPDATE tire_inventory set
                    lifetime = $lifetotal1,
                    rtd = ${'rtd' . $i},
                    rating = '$rating'
                    where id_inventory=${'sn' . $i}");
		//inset data inspeksi ke dalam tabel tire_inspect
		$inspect =  mysqli_query($sambung, "INSERT into tire_inspect (inventory,rtd_inspect,pressure,remark,lifetime_on_inspect,date_inspect) values ('${'sn' . $i}','${'rtd' . $i}','${'pressure' . $i}','${'remark' . $i}','$lifetotal1','$date')");
}
//update HM unit dengan hm inspect
$perintah7 = mysqli_query($sambung, "UPDATE unit_site set
	            hm=$hm    
                where id_unit_site=$idunit");
                echo"<script>
                history.go(-1);
                </script>";
} 
else {
	 	echo "<script>
	 	alert ('Please fill the blank page');
	 	history.go(-1);
	 	</script>";
	  }
?>
       