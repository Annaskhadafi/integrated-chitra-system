<?php
include "koneksi.php";
$item = $_POST['item'];

if($item=='tire'){
	$manufacture = $_POST['manufacture'];
	$pattern = $_POST['pattern'];
	$size = $_POST['size'];
	$compound = $_POST['compound'];
	$category = substr($_POST['category'],0, 50);
	$supplier = $_POST['supplier'];
	$price = $_POST['price'];

	try {
	$stmt = mysqli_prepare(
		$koneksi2,
		"INSERT INTO tire_master (manufacture, pattern, size, compound, category, supplier, price)
		VALUES (?,?,?,?,?,?,?)
		"
	);
	mysqli_stmt_bind_param($stmt, "ssssssd", $manufacture, $pattern, $size, $compound, $category, $supplier, $price);
	/* $query = mysqli_query($koneksi2,"INSERT into tire_master (manufacture,pattern,size,compound,category,supplier,price)  */
	/* 	values ('$manufacture','$pattern','$size','$compound','$category','$supplier','$price')"); */

	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
	exit;

	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}
	
}
elseif ($item=='unit') {
	$unit_manufacture = (string) $_POST['manufacture'];
	$model = (string) $_POST['model'];
	$tire_size = (string) $_POST['size'];
	$tire_quantity = (int) $_POST['quantity'];
	$category = (string) $_POST['category'];

	try {
		$stmt = mysqli_prepare(
			$koneksi2,
			"INSERT INTO unit_master (unit_manufacture, model, tire_size, tire_quantity, category)
			VALUES (?,?,?,?,?)
			"
		);
		mysqli_stmt_bind_param($stmt, "sssis", $unit_manufacture, $model, $tire_size, $tire_quantity, $category);

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
		
	} catch (Exception $e){
		error_log("unit_master insert error: " . $e->getMessage());
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}
	/* $query = mysqli_query($koneksi2,"INSERT into unit_master (unit_manufacture,model,tire_size,tire_quantity,category)  */
	/* 	values ('$manufacture','$model','$size','$quantity','$category')"); */
	/* header ("location: halamanUnitMaster.php"); */

}
elseif ($item=='site') {
	$idcustomer = $_POST['idcustomer'];
	$idmincom = $_POST['idmincom'];
	$kabupaten = $_POST['kabupaten'];
	$kecamatan = $_POST['kecamatan'];
	$site = $_POST['site'];
	$target = $_POST['target'];
	$location = $_POST['location'];

	try {
		$stmt = mysqli_prepare(
			$koneksi2, 
			"INSERT INTO site_master (id_customer, mining_company, site, location, kabupaten, kecamatan, target, status)
 			VALUES (?, ?, ?, ?, ?, ?, ?, 'Active')"
		);
		mysqli_stmt_bind_param($stmt, "sssssss", $idcustomer, $idmincom, $site, $location, $kabupaten, $kecamatan, $target);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}
	/* $query = mysqli_query($koneksi2,"INSERT into site_master (id_customer,mining_company,site,location,kabupaten,kecamatan,target,status)  */
	/* 	values ('$idcustomer','$idmincom','$site','$location','$kabupaten','$kecamatan','$target','Active')"); */
	/* 	echo "<script> alert ('Data submitted'); history.go(-1); </script>"; */
}
elseif ($item=='customer2') {
	$customer = $_POST['customer'];
	try {
		$stmt = mysqli_prepare(
			$koneksi2, 
			"INSERT INTO customer_master (customer)
 			VALUES (?)"
		);
		mysqli_stmt_bind_param($stmt, "s", $customer);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}

/* 	$query = mysqli_query($koneksi2,"INSERT into customer_master (customer)  */
/* 		values ('$customer')"); */
/* 	header ("location: halamanCustomerMaster2.php"); */
/* 	echo"<script> */
/* 		alert('Data submitted'); */
/* 		history.go(-1); */
/* 		</script>"; */
}
elseif ($item=='dealer') {
	$name = $_POST['name'];

	try {
		$stmt = mysqli_prepare(
			$koneksi2, 
			"INSERT INTO unit_dealer (unit_dealer)
 			VALUES (?)"
		);
		mysqli_stmt_bind_param($stmt, "s", $name);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}

	/* $query = mysqli_query($koneksi2,"INSERT INTO unit_dealer (unit_dealer)  */
	/* 	values ('$name')"); */
	/* echo"<script> */
	/* 	alert('Data submitted'); */
	/* 	history.go(-1); */
	/* 	</script>"; */
}
elseif ($item=='dealercontact') {
	$company = $_POST['company'];
	$alamat = $_POST['alamat'];
	$nama = $_POST['nama'];
	$jabatan = $_POST['jabatan'];
	$contact = $_POST['contact'];
	$email = $_POST['email'];



	try {
		$stmt = mysqli_prepare(
            	$koneksi2, 
            	"INSERT INTO unit_dealer_contact (unit_dealer, address, pic, jabatan, dealer_contact, dealer_email) 
            	 VALUES (?, ?, ?, ?, ?, ?)"
        );
		mysqli_stmt_bind_param($stmt, "ssssss", $company, $alamat, $nama, $jabatan, $contact, $email);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}

// 	print_r($_POST);
	/* $query = mysqli_query($koneksi2," */
	/*     INSERT INTO unit_dealer_contact (unit_dealer,address,pic,jabatan,dealer_contact,dealer_email)  */
	/* 	VALUES ('$company','$alamat','$nama','$jabatan','$contact','$email')"); */
	/* echo"<script> */
	/* 	alert('Data submitted'); */
	/* 	history.go(-1); */
	/* 	</script>"; */
}
elseif ($item=='dealer&machine') {
	$dealer = $_POST['dealer'];
	$unit = $_POST['unit'];
	// 	print_r($_POST);
	try {
		$stmt = mysqli_prepare(
			$koneksi2, 
			"INSERT INTO unit_dealer_machine (unit_dealer, unit)
 			VALUES (?, ?)"
		);
		mysqli_stmt_bind_param($stmt, "ss", $dealer, $unit);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>
			alert('Data submitted');
			history.go(-1);
			</script>";
		exit;
	}catch (Exception $e){
		echo "<script>alert('Ada masalah dalam penginputan data. Coba lagi'); history.go(-1);</script>";
		exit;
	}

	/* $query = mysqli_query($koneksi2," */
	/*     INSERT INTO unit_dealer_machine (unit_dealer,unit)  */
	/* 	VALUES ('$dealer','$unit')"); */
	/* echo"<script> */
	/* 	alert('Data submitted'); */
	/* 	history.go(-1); */
	/* 	</script>"; */
}
?>
