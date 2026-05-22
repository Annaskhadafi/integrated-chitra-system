<?php
include "koneksi.php";
$item = $_POST['item'];
if ($item=='tire') {
	$idtire = $_POST['idtire'];
	$manufacture = $_POST['manufacture'];
	$pattern = $_POST['pattern'];
	$size = $_POST['size'];
	$compound = $_POST['compound'];
	$category = $_POST['category'];
	$supplier = $_POST['supplier'];
	$price = $_POST['price'];
	$perintah = mysqli_query($koneksi2,"UPDATE tire_master set
		manufacture='$manufacture',
		pattern= '$pattern',
		size= '$size',
	    compound='$compound',
	    category='$category',
	    supplier='$supplier',
	    price='$price'    
	    where id_tire_master=$idtire"
	);
	 header ("location: halamanDataMaster.php");
	 echo "<script>
			history.go(-1);
			</script>";  
}
elseif ($item=='unit') {
	$idunit = $_POST['idunit'];
	$manufacture = $_POST['manufacture'];
	$model = $_POST['model'];
	$size = $_POST['tire_size'];
	$quantity = $_POST['quantity'];
	$category = $_POST['category'];
	$perintah = mysqli_query($koneksi2,"UPDATE unit_master set
		unit_manufacture='$manufacture',
		model='$model',
		tire_size='$size',
	    tire_quantity='$quantity',
	    category='$category'    
	    where id_unit_master=$idunit"
	);
	 header ("location: halamanUnitMaster.php");
	 echo "<script>
			history.go(-1);
			</script>";  
}
elseif ($item=='customer') {
	$idcustomer = $_POST['idcustomer'];
	$customer = $_POST['customer'];
	$perintah = mysqli_query($koneksi2,"UPDATE customer_master set
		customer='$customer'  
	    where id_customer_master=$idcustomer" 
	);
	header ("location: halamanCustomerMaster2.php");
	 echo "<script>
			history.go(-1);
			</script>";  
}
elseif ($item=='site') {
$idsite = isset($_POST['idsite']) ? $_POST['idsite'] : "";
$customer = isset($_POST['customer']) ? $_POST['customer'] : "";
$mincom = isset($_POST['mincom']) ? $_POST['mincom'] : "";
$site = isset($_POST['site']) ? $_POST['site'] : "";
$location = isset($_POST['location']) ? $_POST['location'] : "";
$kabupaten = isset($_POST['kabupaten']) ? $_POST['kabupaten'] : "";
$kecamatan = isset($_POST['kecamatan']) ? $_POST['kecamatan'] : "";
$target = isset($_POST['target']) ? $_POST['target'] : "0";
$target2 = isset($_POST['target2']) ? $_POST['target2'] : "0";
$status = isset($_POST['status']) ? $_POST['status'] : "";
$yearupdate = isset($_POST['yearupdate']) ? $_POST['yearupdate'] : "0";
// echo "idsite".$idsite;
// echo "cust".$customer;
// echo "idmincom".$mincom;
// echo "site".$site;
// echo "loc".$location;
// echo "kab".$kabupaten;
// echo "kec".$kecamatan;
// echo "targ".$target;
// echo "stat".$status;

	$perintah = mysqli_query($koneksi2,"UPDATE site_master set
		id_customer='$customer',
		mining_company='$mincom',
		site='$site',
		location='$location',
		kabupaten='$kabupaten',
		kecamatan='$kecamatan',
		target='$target',
		target2='$target2',
		status='$status',
		year_update='$yearupdate'
	    WHERE id_site_master=$idsite"
	);
    	header ("location: halamanCustomerMaster.php");
}
elseif ($item=='dealer') {
$dealer = $_POST['dealer'];
$idunitdealer = $_POST['idunitdealer'];	
$perintah = mysqli_query($koneksi2,"UPDATE unit_dealer set
		unit_dealer='$dealer'
	    where id_unit_dealer=$idunitdealer"
);
echo "<script>
alert ('Data service company has been updated');
history.go(-1);
</script>";
}
elseif ($item=='dealercontact') {
$idunitdealercontact = $_POST['idunitdealercontact'];
$address=$_POST['address'];
$pic=$_POST['pic'];
$dealer=$_POST['dealer'];
$jabatan=$_POST['jabatan'];
$dealercontact=$_POST['dealercontact'];
$dealeremail=$_POST['dealeremail'];
// print_r($_POST);

// echo $idunitdealercontact."<br>";
// echo $address."<br>";
// echo $pic."<br>";
// echo $dealer."<br>";
// echo $jabatan."<br>";
// echo $dealercontact."<br>";
// echo $dealeremail;

$perintah = mysqli_query($koneksi2,"UPDATE unit_dealer_contact set
		address='$address',
		pic='$pic',
		unit_dealer='$dealer',
		jabatan='$jabatan',
		dealer_contact='$dealercontact',
		dealer_email='$dealeremail'
	    WHERE id_unit_dealer_contact=$idunitdealercontact");
echo "<script>
alert ('Data service company contact has been updated');
history.go(-1);
</script>";
}
elseif ($item=='dealermachine') {
    $iddealermachine = $_POST['iddealermachine'];
    $unit = $_POST['unit'];
    // print_r($_POST);
    for ($x = 0; $x < 5; $x++) {
        $iddealerunit=$iddealermachine[$x];
        $machine=$unit[$x];
      $perintah = mysqli_query($koneksi2,"UPDATE unit_dealer_machine set
    		unit='$machine'
    	    WHERE id_dealer_machine='$iddealerunit'");
    }
    echo "<script>
    alert ('Data service company contact has been updated');
    history.go(-1);
    </script>";
}
elseif ($item=='warranty') {
    // print_r($_POST);
    $idwarranty = $_POST['idwarranty'];
    $act_plan = $_POST['act_plan'];
    $status_date = $_POST['status_date'];
    $by = $_POST['by'];
    $note = $_POST['note'];
    $donedate = $_POST['done_date'];
    $query = mysqli_query($koneksi5, "UPDATE tab_warranty SET act_plan='$act_plan', date_accept='$status_date',acc_by='$by',note='$note',date_closed='$donedate' WHERE no='$idwarranty' ");
    echo "<script>
    alert ('Tire warranty data has been updated');
    history.go(-1);
    </script>";
}
?>
