<?php 
// menghubungkan dengan koneksi
$idsite = $_POST['idsite'] ?? '';
include 'koneksi.php';
// menghubungkan dengan library excel reader
include "excel_reader2.php";
// upload file xls
$target = basename($_FILES['fileexcel']['name'] ?? '') ;
if ($target) {
    move_uploaded_file($_FILES['fileexcel']['tmp_name'], $target);
    // beri permisi agar file xls dapat di baca
    chmod($target, 0777);

    // mengambil isi file xls
    $data = new Spreadsheet_Excel_Reader($target, false);
    // menghitung jumlah baris data yang ada
    $jumlah_baris = $data->rowcount($sheet_index=0);

    // jumlah default data yang berhasil di import
    $berhasil = 0;
    for ($i=2; $i<=$jumlah_baris; $i++){

    	// menangkap data dan memasukkan ke variabel sesuai dengan kolumnya masing-masing
    	$sn  		= $data->val($i, 1) ?? '';
    	$size   	= $data->val($i, 2) ?? '';
    	$brand  	= $data->val($i, 3) ?? '';
    	$pattern  	= $data->val($i, 4) ?? '';
    	$compound  	= $data->val($i, 5) ?? '';
    	$otd		= $data->val($i, 6) ?? '';
    	$pressure	= $data->val($i, 7) ?? '';
    	$supplier  	= $data->val($i, 8) ?? '';
    	$status  	= $data->val($i, 9) ?? '';
    	$lifetime  	= $data->val($i, 10) ?? '';
    	$rtd  		= $data->val($i, 11) ?? '';
    	$price  	= $data->val($i, 12) ?? '';
    	$date  		= $data->val($i, 13) ?? ''; 
    	if($brand != ""){
    		$perintahcek1 = mysqli_query($sambung, "SELECT * from tire_manufac where manufac='$brand'"); 																			//cek apakah manufacture sudah ada
            if(mysqli_num_rows($perintahcek1)<=0){																														//jika tidak ada 
            	mysqli_query($sambung, "INSERT into tire_manufac values('','$brand')");																							//tambahkan manufacture baru
            }
    		$perintahcek2 = mysqli_query($sambung, "SELECT * from tire_pattern a ,tire_manufac b where a.manufac=b.id_manufac and a.pattern='$pattern' and b.manufac='$brand'"); 	//cek apakah pattern sudah ada
            if(mysqli_num_rows($perintahcek2)<=0){																														//jika tidak ada 
            	$ambildata1 = mysqli_query($sambung, "SELECT id_manufac from tire_manufac where manufac='$brand'");  																//ambil data id_manufac      	
                $data1=mysqli_fetch_assoc($ambildata1);
                $idbrand=$data1['id_manufac'];															
            	mysqli_query($sambung, "INSERT into tire_pattern values('','$pattern','$idbrand')");																				//insert pattern baru + id_manufac
            }
            $perintahcek3 = mysqli_query($sambung, "SELECT * from tire_size a ,tire_pattern b where a.pattern=b.id_pattern and a.size='$size' and b.pattern='$pattern'");
            if(mysqli_num_rows($perintahcek3)<=0){																														//jika tidak ada 
            	$ambildata3 = mysqli_query($sambung, "SELECT id_pattern from tire_pattern where pattern='$pattern'");  															//ambil data id_pattern      	
                $data3=mysqli_fetch_assoc($ambildata3);
                $idpattern=$data3['id_pattern'];															
            	mysqli_query($sambung, "INSERT into tire_size values('','$size','$idpattern','$otd','$pressure')");																//insert size baru + id_pattern + otd + pressure
            }
    		$perintahcek4 = mysqli_query($sambung, "SELECT * from tire_compound where compound='$compound'"); 																		//cek apakah compound sudah ada
            if(mysqli_num_rows($perintahcek4)<=0){																														//jika tidak ada 
            	mysqli_query($sambung, "INSERT into tire_compound values('','$compound')");																						//tambahkan compound baru
            }
    		$perintahcek6 = mysqli_query($sambung, "SELECT * from supplier where supplier='$supplier'"); 																			//cek apakah supplier sudah ada
            if(mysqli_num_rows($perintahcek6)<=0){																														//jika tidak ada 
            	mysqli_query($sambung, "INSERT into supplier values('','$supplier')");																								//tambahkan supplier baru
            }
            $perintahcek5 = mysqli_query($sambung, "SELECT * from tire_inventory where sn='$sn'");																					//cek apakah SN sudah ada
            if(mysqli_num_rows($perintahcek5)<=0){																														//jika tidak ada 
            	$ambildata5 = mysqli_query($sambung, "SELECT * from tire_size a ,tire_pattern b where a.pattern=b.id_pattern and a.size='$size' and b.pattern='$pattern'");  		//ambil data tire master      	
                $data5=mysqli_fetch_assoc($ambildata5);															
            	$ambildata5a = mysqli_query($sambung, "SELECT * from tire_compound where compound='$compound'");        	
                $data5a=mysqli_fetch_assoc($ambildata5a);														
            	$ambildata5b = mysqli_query($sambung, "SELECT * from supplier where supplier='$supplier'");     	
                $data5b=mysqli_fetch_assoc($ambildata5b);                                                        
                $ambildata5c = mysqli_query($sambung, "SELECT * from tire_status where status='$status'");        
                $data5c=mysqli_fetch_assoc($ambildata5c);
                $idsize=$data5['id_size'] ?? '';
                $idsupplier=$data5b['id_supplier'] ?? '';
                $idcompound=$data5a['id_compound'] ?? '';
                $idstatus=$data5c['id_status'] ?? '';                                         															
            	mysqli_query($sambung, "INSERT into tire_inventory values('','$sn','$idsize','$idcompound','$idsupplier','$idsite','$idstatus','$lifetime','$rtd','$price','$date')");		//insert SN baru
            	$berhasil++;																																			//$berhasil+1
            }
    	}
    }
}
// alihkan halaman ke index.php
header ("location: halamanimportfromexcel.php?berhasil=$berhasil");
?>
	}
