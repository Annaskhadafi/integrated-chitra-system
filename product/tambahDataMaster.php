<?php
include "koneksi.php";
$item = $_POST['item'];

if($item=='tire'){
	$manufacture = $_POST['manufacture'];
	$pattern = $_POST['pattern'];
	$size = $_POST['size'];
	$compound = $_POST['compound'];
	$category = $_POST['category'];
	$supplier = $_POST['supplier'];
	$price = $_POST['price'];
	$query = mysqli_query($koneksi2,"INSERT into tire_master (manufacture,pattern,size,compound,category,supplier,price) 
		values ('$manufacture','$pattern','$size','$compound','$category','$supplier','$price')");
	header ("location: halamanDataMaster.php");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='unit') {
	$manufacture = $_POST['manufacture'];
	$model = $_POST['model'];
	$size = $_POST['size'];
	$quantity = $_POST['quantity'];
	$category = $_POST['category'];
	$query = mysqli_query($koneksi2,"INSERT into unit_master (unit_manufacture,model,tire_size,tire_quantity,category) 
		values ('$manufacture','$model','$size','$quantity','$category')");
	header ("location: halamanUnitMaster.php");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='site') {
	$idcustomer = $_POST['idcustomer'];
	$idmincom = $_POST['idmincom'];
	$kabupaten = $_POST['kabupaten'];
	$kecamatan = $_POST['kecamatan'];
	$site = $_POST['site'];
	$target = $_POST['target'];
	$location = $_POST['location'];
	
	$query = mysqli_query($koneksi2,"INSERT into site_master (id_customer,mining_company,site,location,kabupaten,kecamatan,target,status) 
		values ('$idcustomer','$idmincom','$site','$location','$kabupaten','$kecamatan','$target','Active')");
		echo "<script> alert ('Data submitted'); history.go(-1); </script>";
}
elseif ($item=='customer2') {
	$customer = $_POST['customer'];
	$query = mysqli_query($koneksi2,"INSERT into customer_master (customer) 
		values ('$customer')");
	header ("location: halamanCustomerMaster2.php");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='dealer') {
	$name = $_POST['name'];
	$query = mysqli_query($koneksi2,"INSERT INTO unit_dealer (unit_dealer) 
		values ('$name')");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='dealercontact') {
	$company = $_POST['company'];
	$alamat = $_POST['alamat'];
	$nama = $_POST['nama'];
	$jabatan = $_POST['jabatan'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];
// 	print_r($_POST);
	$query = mysqli_query($koneksi2,"
	    INSERT INTO unit_dealer_contact (unit_dealer,address,pic,jabatan,dealer_contact,dealer_email) 
		VALUES ('$company','$alamat','$nama','$jabatan','$contact','$email')");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='dealer&machine') {
	$dealer = $_POST['dealer'];
	$unit = $_POST['unit'];
// 	print_r($_POST);
	$query = mysqli_query($koneksi2,"
	    INSERT INTO unit_dealer_machine (unit_dealer,unit) 
		VALUES ('$dealer','$unit')");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
?>