<?php
$repair_type = $_POST['repairtype'] ?? '';
$jobtype = $_POST['jobtype'] ?? ''; //ini hrus sesuai dengan name yg ada di modalaccpet
$type = $_POST['type'] ?? '';
$createby = $_POST['createby'] ?? '';
$idwo = $_POST['idwo'] ?? '';
$wo = $_POST['wo'] ?? '';
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? '';
$injury = $_POST['injury'] ?? '';
$storeloc = $_POST['storeloc'] ?? '';
include "koneksi.php";

if ($jobtype=="repair" and $status==2 and $wo!="" and $injury!="" and $status!="" and $date!="" and $createby!="" and $date!="" and $storeloc!="") {
    //ganti status & tambah injury
    $perintah = mysqli_query($sambung, "UPDATE work_order 
                                        SET wo='$wo', status='$status', injury='$injury', input_date='$date', job_type='$jobtype', repair_type='$repair_type', type='$type', createby='$createby', id_store_loc='$storeloc'
                                        WHERE id_wo = '$idwo'");
    //buat job di tabel job
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Skiving', 1)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Buffing', 2)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Cementing', 3)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Buffing innerliner', 4)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Install patch', 8)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Built up', 5)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Curing', 6)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Finishing', 7)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Quality control', 8)");

}
else if ($jobtype=="retread" and $status==2 and $wo!="" and $injury!="" and $status!="" and $date!="" and $createby!="" and $date!="" and $storeloc!="") {
    $perintah = mysqli_query($sambung, "UPDATE work_order 
                                        SET wo='$wo', status='$status', injury='$injury', input_date='$date', job_type='$jobtype', repair_type='$repair_type', type='$type', createby='$createby', id_store_loc='$storeloc'
                                        WHERE id_wo = '$idwo'");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Buffing', 8)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Skiving & Filling', 2)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Building', 5)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Bagging', 8)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Curing', 4)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Finishing', 8)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Quality Control', 8)");
    mysqli_query($sambung, "INSERT INTO job (wo, job, material) VALUES ('$idwo', 'Painting', 3)");
}
elseif($status==3){
    $perintah = mysqli_query($sambung, "UPDATE work_order SET status='$status', injury='$injury' WHERE id_wo = '$idwo'");
}
else{
    echo "<script>
    alert ('Please fill the blank page');
    history.go(-1);
    </script>";
}
echo "<script>
		history.go(-1);
		</script>";  
?>