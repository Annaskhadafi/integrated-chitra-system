<?php
$repair_type = $_POST['repairtype'];
$jobtype = $_POST['jobtype']; //ini hrus sesuai dengan name yg ada di modalaccpet
$type = $_POST['type'];
// $tiretype = $_POST['tiretype'];
// $tiretype = $_POST['type'];
$createby = $_POST['createby'];
$idwo = $_POST['idwo'];
$wo = $_POST['wo'];
$date = $_POST['date'];
$status = $_POST['status'];
$injury = $_POST['injury'];
$storeloc = $_POST['storeloc'];
include "koneksi.php";
if ($jobtype=="repair" and $status==2 and $wo!="" and $injury!="" and $status!="" and $date!="" and $createby!="" and $date!="" and $storeloc!="") {
    //ganti status & tambah injury
    $perintah = mysqli_query($sambung, "UPDATE work_order 
                                        set wo='$wo',status='$status',injury='$injury',input_date='$date',job_type='$jobtype',repair_type='$repair_type',type='$type',createby='$createby',id_store_loc='$storeloc'
                                        where id_wo=$idwo");
    //buat job di tabel job
    $perintah2 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Skiving',1)");
    $perintah3 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Buffing',2)");
    $perintah4 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Cementing',3)");
    $perintah5 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Buffing innerliner',4)");
    $perintah6 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Install patch',8)");
    $perintah7 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Built up',5)");
    $perintah8 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Curing',6)");
    $perintah9 = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Finishing',7)");
    $perintah10= mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Quality control',8)");

}
else if ($jobtype=="retread" and $status==2 and $wo!="" and $injury!="" and $status!="" and $date!="" and $createby!="" and $date!="" and $storeloc!="") {
    $perintah = mysqli_query($sambung, "UPDATE work_order 
                                        set wo='$wo',status='$status',injury='$injury',input_date='$date',job_type='$jobtype',repair_type='$repair_type',type='$type',createby='$createby',id_store_loc='$storeloc'
                                        where id_wo=$idwo");
    $perintahb = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Buffing',8)");
    $perintahc = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Skiving & Filling',2)");
    $perintahd = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Building',5)");
    $perintahe = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Bagging',8)");
    $perintahf = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Curing',4)");
    $perintahg = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Finishing',8)");
    $perintahh = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Quality Control',8)");
    $perintahi = mysqli_query($sambung, "INSERT into job (wo,job,material) values ('$idwo','Painting',3)");
}
elseif($status==3){
    $perintah = mysqli_query($sambung, "UPDATE work_order set status='$status',injury='$injury' where id_wo=$idwo");
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