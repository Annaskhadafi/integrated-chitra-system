<?php 
$idforecast = $_POST['idforecast'] ?? '';
$loop = (int)($_POST['loop'] ?? 0);
$idstock = $_POST['idstock'] ?? [];
$sn = $_POST['sn'] ?? [];
$est = $_POST['est'] ?? [];
$delivery = $_POST['delivery'] ?? [];
$do = $_POST['do'] ?? [];
$invoice = $_POST['invoice'] ?? '';
$inv_date = $_POST['inv_date'] ?? '';
$mrko = $_POST['mrko'] ?? '';
$job = $_POST['job'] ?? '';

include "koneksi.php";
if($job == 'delivery' && is_array($idstock)){
    for($ulang=0; $ulang < $loop; $ulang++){
        $data5 = $idstock[$ulang] ?? '';
        $data1 = $sn[$ulang] ?? '';
        $data2 = $est[$ulang] ?? '';
        $data3 = $delivery[$ulang] ?? '';
        $data4 = $do[$ulang] ?? '';
        
        if ($data5 !== '') {
            mysqli_query($koneksi6, "UPDATE stock SET sn='$data1', estimasi='$data2', delivery_date='$data3', do='$data4' WHERE id_stock = '$data5'");
            if ($data4 != "") {
                mysqli_query($koneksi6, "UPDATE stock SET status='Delivery' WHERE id_stock = '$data5'");
            }
        }
    }
}
elseif($job == 'invoice'){
    mysqli_query($koneksi6, "UPDATE stock SET inv_date='$inv_date', invoice='$invoice', status='Completed' WHERE mrko='$mrko'");
}
echo "<script> alert ('Data stock telah diupdate'); history.go(-1); </script>";
?>
