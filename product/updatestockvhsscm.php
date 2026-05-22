<?php 
$idforecast=$_POST['idforecast'];
$loop=$_POST['loop'];
$idstock=$_POST['idstock'];
$sn=$_POST['sn'];
$est=$_POST['est'];
$delivery=$_POST['delivery'];
$do=$_POST['do'];
$invoice=$_POST['invoice'];
$inv_date=$_POST['inv_date'];
$mrko=$_POST['mrko'];
$job=$_POST['job'];

// echo $job."<br>";
// echo $invoice."<br>";
// echo $inv_date."<br>";
// echo $idforecast."<br>";
// echo $loop."<br>";
include "koneksi.php";
if($job=='delivery'){
    for($ulang=0;$ulang<$loop;$ulang++){
        echo $ulang;
        $data5=$idstock[$ulang];
        $data1=$sn[$ulang];
        $data2=$est[$ulang];
        $data3=$delivery[$ulang];
        $data4=$do[$ulang];
        $query=mysqli_query($koneksi6,"UPDATE stock SET sn='$data1',estimasi='$data2',delivery_date='$data3',do='$data4' WHERE id_stock=$data5 ");
        if ($data4 != ""){$query=mysqli_query($koneksi6,"UPDATE stock SET status='Delivery' WHERE id_stock=$data5 ");}
    }
}
elseif($job=='invoice'){
    $query=mysqli_query($koneksi6,"UPDATE stock SET inv_date='$inv_date',invoice='$invoice',status='Completed' WHERE mrko='$mrko' ");
}
echo "<script> alert ('Data stock telah diupdate'); history.go(-1); </script>";
?>
