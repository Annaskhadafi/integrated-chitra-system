<?php
include "koneksi.php"; 
$mincom= strip_tags($_POST['mincom']);
$material= $_POST['material'];
$target= $_POST['target'];
$tanggal=date('Y-m-d');
// echo $mincom;
// echo $material;
// echo $target;
if ($material !="" and $target !="") {
		$query = mysqli_query($koneksi2,"INSERT into mining_company (mining_company,material,target,tgl_update) values ('$mincom','$material','$target','$tanggal')");
		echo "<script>
		alert ('Data mining company telah ditambahkan');
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