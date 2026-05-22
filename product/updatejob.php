<?php
include "koneksi.php";
$jobtype = $_POST['jobtype'];
$idwo = $_POST['wo'];
$storeloc = $_POST['storeloc'];
$skip4 = $_POST['skip4'];
$skip5 = $_POST['skip5'];
$fullfill=0;
// echo $jobtype;
// echo $storeloc;
$perintah = mysqli_query($koneksi3, "SELECT status FROM work_order WHERE id_wo=$idwo");
$data = mysqli_fetch_array($perintah);
$wostat=$data['status'];
if($jobtype=="repair"){
    //job1
        $job1 = $_POST['job1'];
        $date1 = $_POST['date1'];
        $qtysebelum1 = $_POST['qtysebelum1'];
        if($qtysebelum1==''){$qtysebelum1=0;}
        $qty1 = $_POST['qty1'];
        $time1 = $_POST['time1'];
        $personil1 = $_POST['personil1'];
        $note1 = $_POST['note1'];
        $inv1 = $_POST['inv1'];
        $idusage1=$job1.$inv1;
        $balance = (int)$qtysebelum1-(int)$qty1;
        $stockbalance1=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv1");
        $usage1= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage1','$job1','$inv1','$qty1') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage1',job='$job1',inv='$inv1',qty='$qty1'");
        $perintah1=mysqli_query($koneksi3, "UPDATE job SET date='$date1',time='$time1',person='$personil1',note='$note1' WHERE id_job=$job1");
        if($time1>0){$fullfill++;}
        // if($time1>0 || $skip==1){$fullfill++;}
        // echo "sebelum =".$qtysebelum1;
        // echo "qty =".$qty1;
        // echo "idusage =".$idusage1;
    //job1 end
    //job2
        $job2 = $_POST['job2'];
        $date2 = $_POST['date2'];
        $qtysebelum2 = $_POST['qtysebelum2'];
        if($qtysebelum2==''){$qtysebelum2=0;}
        $qty2 = $_POST['qty2'];
        $time2 = $_POST['time2'];
        $personil2 = $_POST['personil2'];
        $note2 = $_POST['note2'];
        $inv2 = $_POST['inv2'];
        $idusage2=$job2.$inv2;
        $balance = (int)$qtysebelum2-(int)$qty2;
        $stockbalance2=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv2");
        $usage2= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage2','$job2','$inv2','$qty2') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage2',job='$job2',inv='$inv2',qty='$qty2'");
        $perintah2=mysqli_query($koneksi3, "UPDATE job SET date='$date2',time='$time2',person='$personil2',note='$note2' WHERE id_job=$job2");
        if($time2>0){$fullfill++;}
    //job2 end
    //job3
        $job3 = $_POST['job3'];
        $date3 = $_POST['date3'];
        $time3 = $_POST['time3'];
        $personil3 = $_POST['personil3'];
        $note3 = $_POST['note3'];
        if($time3>0){$fullfill++;}
        $perintah = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=3 and a.id_store_loc=$storeloc ORDER BY a.desc");
        while ($data = mysqli_fetch_array($perintah)){ 
            $inv3 = $data['id_inv'];
            $qty3 = $_POST['qty3'.$inv3];
            $idusage3=$job3.$inv3;
            $qtysebelum3 = $_POST['qtysebelum3'.$inv3];
            if($qtysebelum3==''){$qtysebelum3=0;}
            $balance = (int)$qtysebelum3-(int)$qty3;
            if($balance!=0){
                $stockbalance3=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv3");
                $usage3= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage3','$job3','$inv3','$qty3') 
                                            ON DUPLICATE KEY UPDATE
                                            id_usage='$idusage3',job='$job3',inv='$inv3',qty='$qty3'");
            }
        }
        $perintah3=mysqli_query($koneksi3, "UPDATE job SET date='$date3',time='$time3',person='$personil3',note='$note3' WHERE id_job=$job3");
    //job3 end
    //job4
        $job4 = $_POST['job4'];
        $date4 = $_POST['date4'];
        $time4 = $_POST['time4'];
        $personil4 = $_POST['personil4'];
        $note4 = $_POST['note4'];
        $perintah = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=4 and a.id_store_loc=$storeloc ORDER BY a.desc");
        while ($data = mysqli_fetch_array($perintah)){ 
            $inv4 = $data['id_inv'];
            $qty4 = $_POST['qty4'.$inv4];
            $idusage4=$job4.$inv4;
            $qtysebelum4 = $_POST['qtysebelum4'.$inv4];
            if($qtysebelum4==''){$qtysebelum4=0;}
            $balance = (int)$qtysebelum4-(int)$qty4;
            if($balance!=0){
                $stockbalance4=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv4");
                $usage4= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage4','$job4','$inv4','$qty4') 
                                            ON DUPLICATE KEY UPDATE
                                            id_usage='$idusage3',job='$job4',inv='$inv4',qty='$qty4'");
            }
        }
        $perintah4=mysqli_query($koneksi3, "UPDATE job SET date='$date4',time='$time4',person='$personil4',note='$note4' WHERE id_job=$job4");
        // if($time4>0){$fullfill++;}
        if($time4>0 || $skip4==4){$fullfill++;}
    //job4 end
    //job5
        $job5 = $_POST['job5'];
        $date5 = $_POST['date5'];
        $time5 = $_POST['time5'];
        $personil5 = $_POST['personil5'];
        $note5 = $_POST['note5'];
        $perintah5=mysqli_query($koneksi3, "UPDATE job SET date='$date5',time='$time5',person='$personil5',note='$note5' WHERE id_job=$job5");
        // echo $job5."0<br>";
        // echo $date5."1<br>";
        // echo $time5."3<br>";
        // echo $personil5."4<br>";
        // if($time5>0){$fullfill++;}
        if($time5>0 || $skip5==5){$fullfill++;}
    //job5 end
    //job6
        $job6 = $_POST['job6'];
        $date6 = $_POST['date6'];
        $qtysebelum6 = $_POST['qtysebelum6'];
        if($qtysebelum6==''){$qtysebelum6=0;}
        $qty6 = $_POST['qty6'];
        $time6 = $_POST['time6'];
        $personil6 = $_POST['personil6'];
        $note6 = $_POST['note6'];
        $inv6 = $_POST['inv6'];
        $idusage6=$job6.$inv6;
        $balance = (int)$qtysebelum6-(int)$qty6;
        $stockbalance6=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv6");
        $usage6= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage6','$job6','$inv6','$qty6') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage6',job='$job6',inv='$inv6',qty='$qty6'");
        $perintah6=mysqli_query($koneksi3, "UPDATE job SET date='$date6',time='$time6',person='$personil6',note='$note6' WHERE id_job=$job6");
        if($time6>0){$fullfill++;}
    //job6 end
    //job7
        $job7 = $_POST['job7'];
        $date7 = $_POST['date7'];
        $qtysebelum7 = $_POST['qtysebelum7'];
        if($qtysebelum7==''){$qtysebelum7=0;}
        $qty7 = $_POST['qty7'];
        $time7 = $_POST['time7'];
        $personil7 = $_POST['personil7'];
        $note7 = $_POST['note7'];
        $inv7 = $_POST['inv7'];
        $idusage7=$job7.$inv7;
        $balance = (int)$qtysebelum7-(int)$qty7;
        $stockbalance7=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv7");
        $usage7= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage7','$job7','$inv7','$qty7') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage7',job='$job7',inv='$inv7',qty='$qty7'");                  
        $perintah7=mysqli_query($koneksi3, "UPDATE job SET date='$date7',time='$time7',person='$personil7',note='$note7' WHERE id_job=$job7");
        if($time7>0){$fullfill++;}
        // echo "personil".$personil7;
        // echo "time".$time7;
        // echo "idjob".$job7;
        
    //job7 end
    //job8
        $job8 = $_POST['job8'];
        $date8 = $_POST['date8'];
        $qtysebelum8 = $_POST['qtysebelum8'];
        if($qtysebelum8==''){$qtysebelum8=0;}
        $qty8 = $_POST['qty8'];
        $time8 = $_POST['time8'];
        $personil8 = $_POST['personil8'];
        $note8 = $_POST['note8'];
        $inv8 = $_POST['inv8'];
        $idusage8=$job8.$inv8;
        $balance = (int)$qtysebelum8-(int)$qty8;
        $stockbalance8=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv8");
        $usage8= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage8','$job8','$inv8','$qty8') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage8',job='$job8',inv='$inv8',qty='$qty8'");
        $perintah8=mysqli_query($koneksi3, "UPDATE job SET date='$date8',time='$time8',person='$personil8',note='$note8' WHERE id_job=$job8");
        if($time8>0){$fullfill++;}
    //job8 end
    //job9
        $job9 = $_POST['job9'];
        $date9 = $_POST['date9'];
        //$qtysebelum9 = $_POST['qtysebelum9'];
        // if($qtysebelum9==''){$qtysebelum9=0;}
        // $qty9 = $_POST['qty9'];
        $time9 = $_POST['time9'];
        $personil9 = $_POST['personil9'];
        $note9 = $_POST['note9'];
        // $inv9 = $_POST['inv9'];
        // $idusage9=$job9.$inv9;
        $perintah9=mysqli_query($koneksi3, "UPDATE job SET date='$date9',time='$time9',person='$personil9',note='$note9' WHERE id_job=$job9");
        if($time9>0){$fullfill++;}
    //job9 end
}
// if($fullfill==9){
//     $perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='4' WHERE id_wo=$idwo");
// }
if($fullfill==9){
    if($wostat<5){$perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='4' WHERE id_wo=$idwo");}
}
elseif($jobtype=="retread"){
    //job1
        
        $job1 = $_POST['job1'];
        $date1 = $_POST['date1'];
        $qtysebelum1 = $_POST['qtysebelum1'];
        if($qtysebelum1==''){$qtysebelum1=0;}
        $qty1 = $_POST['qty1'];
        $time1 = $_POST['time1'];
        $personil1 = $_POST['personil1'];
        $note1 = $_POST['note1'];
        $inv1 = $_POST['inv1'];
        $idusage1=$job1.$inv1;
        $balance = (int)$qtysebelum1-(int)$qty1;
        $stockbalance1=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv1");
        $usage1= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage1','$job1','$inv1','$qty1') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage1',job='$job1',inv='$inv1',qty='$qty1'");
        $perintah1=mysqli_query($koneksi3, "UPDATE job SET date='$date1',time='$time1',person='$personil1',note='$note1' WHERE id_job=$job1");
        if($time1>0){$fullfill++;}

    //job1 end
    //job2
        $job2 = $_POST['job2'];
        $date2 = $_POST['date2'];
        $qtysebelum2 = $_POST['qtysebelum2'];
        if($qtysebelum2==''){$qtysebelum2=0;}
        $qty2 = $_POST['qty2'];
        $time2 = $_POST['time2'];
        $personil2 = $_POST['personil2'];
        $note2 = $_POST['note2'];
        $inv2 = $_POST['inv2'];
        $idusage2=$job2.$inv2;
        $balance = (int)$qtysebelum2-(int)$qty2;
        // $balance = $qtysebelum2-$qty2;
        // $stockbalance2=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv2");
        $stockbalance2=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv2");
        $usage2= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage2','$job2','$inv2','$qty2') 
                                        ON DUPLICATE KEY UPDATE
                                        id_usage='$idusage2',job='$job2',inv='$inv2',qty='$qty2'");
        $perintah2=mysqli_query($koneksi3, "UPDATE job SET date='$date2',time='$time2',person='$personil2',note='$note2' WHERE id_job=$job2");
        if($time2>0){$fullfill++;}
    // job2 end
    // job3 pake balance ya semua kategori yg ada material nya 
        $job3 = $_POST['job3'];
        $date3 = $_POST['date3'];
        $time3 = $_POST['time3'];
        $personil3 = $_POST['personil3'];
        $note3 = $_POST['note3'];
        if($time3>0){$fullfill++;}
        $perintah = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category in (5,6,7) and a.id_store_loc=$storeloc ORDER BY a.desc");
        while ($data = mysqli_fetch_array($perintah)){ 
            $inv3 = $data['id_inv'];
            $qty3 = $_POST['qty3'.$inv3];
            $idusage3=$job3.$inv3;
            $qtysebelum3 = $_POST['qtysebelum3'.$inv3];
            if($qtysebelum3==''){$qtysebelum3=0;}
            $balance = (int)$qtysebelum3-(int)$qty3;
            echo $balance;
            if($balance!=0){
                $stockbalance3=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv3");
                $usage3= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage3','$job3','$inv3','$qty3') 
                                            ON DUPLICATE KEY UPDATE
                                            id_usage='$idusage3',job='$job3',inv='$inv3',qty='$qty3'");
            }
        }
        $perintah3=mysqli_query($koneksi3, "UPDATE job SET date='$date3',time='$time3',person='$personil3',note='$note3' WHERE id_job=$job3");
    //job3 end
    // job4
        $job4 = $_POST['job4'];
        $date4 = $_POST['date4'];
        $time4 = $_POST['time4'];
        $personil4 = $_POST['personil4'];
        $note4 = $_POST['note4'];
        if($time4>0){$fullfill++;}
        $perintah = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category =4 and a.id_store_loc=$storeloc ORDER BY a.desc");
        while ($data = mysqli_fetch_array($perintah)){ 
            $inv4 = $data['id_inv'];
            $qty4 = $_POST['qty4'.$inv4];
            $idusage4=$job4.$inv4;
            $qtysebelum4 = $_POST['qtysebelum4'.$inv4];
            if($qtysebelum4==''){$qtysebelum4=0;}
            $balance = (int)$qtysebelum4-(int)$qty4;
            echo $balance;
            if($balance!=0){
                $stockbalance4=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv4");
                $usage4= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage4','$job4','$inv4','$qty4') 
                                            ON DUPLICATE KEY UPDATE
                                            id_usage='$idusage4',job='$job4',inv='$inv4',qty='$qty4'");
            }
        }
        $perintah4=mysqli_query($koneksi3, "UPDATE job SET date='$date4',time='$time4',person='$personil4',note='$note4' WHERE id_job=$job4");
    // job4 end
    // job5
        $job5 = $_POST['job5'];
        $date5 = $_POST['date5'];
        $time5 = $_POST['time5'];
        $personil5 = $_POST['personil5'];
        $note5 = $_POST['note5'];
        $perintah5=mysqli_query($koneksi3, "UPDATE job SET date='$date5',time='$time5',person='$personil5',note='$note5' WHERE id_job=$job5");
        if($time5>0){$fullfill++;}
    //job5 end
    //job6
        $job6 = $_POST['job6'];
        $date6 = $_POST['date6'];
        $time6 = $_POST['time6'];
        $personil6 = $_POST['personil6'];
        $note6 = $_POST['note6'];
        $perintah6=mysqli_query($koneksi3, "UPDATE job SET date='$date6',time='$time6',person='$personil6',note='$note6' WHERE id_job=$job6");
        if($time6>0){$fullfill++;}
    //job6 end
    //job7
        $job7 = $_POST['job7'];
        $date7 = $_POST['date7'];
        $time7 = $_POST['time7'];
        $personil7 = $_POST['personil7'];
        $note7 = $_POST['note7'];
        if($time7>0){$fullfill++;}
        $perintah = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category =3 and a.id_store_loc=$storeloc ORDER BY a.desc");
        while ($data = mysqli_fetch_array($perintah)){ 
            $inv7 = $data['id_inv'];
            $qty7 = $_POST['qty7'.$inv7];
            $idusage7=$job7.$inv7;
            $qtysebelum7 = $_POST['qtysebelum7'.$inv7];
            if($qtysebelum7==''){$qtysebelum7=0;}
            $balance = (int)$qtysebelum7-(int)$qty7;
            echo $balance;
            if($balance!=0){
                $stockbalance7=mysqli_query($koneksi3, "UPDATE mat_inventory SET inv_qty = inv_qty + $balance WHERE id_inv = $inv7");
                $usage7= mysqli_query($koneksi3,"INSERT INTO mat_usage (id_usage,job,inv,qty) VALUES ('$idusage7','$job7','$inv7','$qty7') 
                                            ON DUPLICATE KEY UPDATE
                                            id_usage='$idusage7',job='$job7',inv='$inv7',qty='$qty7'");
            }
        }
        $perintah7=mysqli_query($koneksi3, "UPDATE job SET date='$date7',time='$time7',person='$personil7',note='$note7' WHERE id_job=$job7");

    //job7 end
}
// if($fullfill==7){
//     $perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='4' WHERE id_wo=$idwo");
// }
if($fullfill==7){
    if($wostat<5){$perintah = mysqli_query($koneksi3, "UPDATE work_order SET status='4' WHERE id_wo=$idwo");}
}
    //  echo "personil".$personil9;
    //  echo "time".$time9;
    //  echo "idjob".$job9;
    echo $fullfill;
    // echo "<br>skip4".$skip4;
    // echo "<br>skip5".$skip5;
?>
<script>
    history.go(-1);
</script>
