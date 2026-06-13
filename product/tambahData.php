<?php
include "koneksi.php";
$item = $_POST['item'];
if($item=='fleet'){
	$idsite = $_POST['customer'];
	$unit = $_POST['unit'];
	$qty = $_POST['qty'];
	$rotasi = $_POST['rotasi'];
	$scrap = $_POST['scrap'];
	$segment = $_POST['segment'];
    $date=date('Y-m-d');
	$user=$_POST['name'];
	$query = mysqli_query($koneksi2,"INSERT into fleet_list (id_site,id_unit,unit_qty,rotasi,scrap,segment,date,updateby) 
		values ('$idsite','$unit','$qty','$rotasi','$scrap','$segment','$date','$user')");
	echo"<script>
		alert('Data submitted');
		history.go(-1);
		</script>";
}
elseif ($item=='user') {
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password sebelum disimpan
    $section  = $_POST['section'];
    $level    = $_POST['level'];
    
    // Insert dengan prepared statement
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO user 
        (sn, name, username, password, section, department, email, level, last_login) 
        VALUES (?, ?, ?, ?, ?, '', '', ?, NULL)
    ");
    
    $sn = ''; // Jika memang dikosongkan
    
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $sn, $name, $username, $password, $section, $level
    );
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Data submitted');
            window.location.href = 'adm_halamanusermaster.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');
            window.history.back();
        </script>";
    }
    
    mysqli_stmt_close($stmt);
}
elseif ($item=='supply') {
    // Tangkap semua data dari form
    $item       = trim($_POST['item']);
    $customer   = intval($_POST['customer']);
    $supplier   = intval($_POST['supplier']);
    $brand      = trim($_POST['brand']);
    $size       = trim($_POST['size']);
    $qty       = trim($_POST['qty']);
    $period     = !empty($_POST['period']) ? $_POST['period'] : NULL;
    $user       = intval($_POST['user']);
    
    // Validasi minimal
    if ($item == "" || $customer == 0 || $supplier == 0 || $brand == "" || $size == "") {
        die("Data tidak lengkap.");
    }
    
    // ======================================
    // ECHO UNTUK TESTING
    // ======================================
    
    // echo "<pre>";
    // echo "ITEM        : $item\n";
    // echo "CUSTOMER ID : $customer\n";
    // echo "SUPPLIER ID : $supplier\n";
    // echo "BRAND       : $brand\n";
    // echo "SIZE        : $size\n";
    // echo "PERIOD      : " . ($period ? $period : "NULL") . "\n";
    // echo "USER        : $user\n";
    // echo "</pre>";
    // exit; // stop sampai sini supaya tidak insert
	$query = mysqli_query($koneksi8,"INSERT into allSupply (id_user,id_customer_master,supplier,brand,size,qty_supply,periode) 
		values ('$user','$customer','$supplier','$brand','$size','$qty','$period')");
	echo"<script>
		alert('Data submitted');
        window.location.href='marketing_halamansupply.php';
		</script>";
}
?>