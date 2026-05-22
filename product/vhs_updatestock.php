<?php
include "koneksi.php";
$date  = $_POST['date'];
$picgi = $_POST['picgi'];
$wo    = $_POST['wo'];
$gi    = $_POST['gi'];
$qty   = $_POST['qty'];
$mrko   = $_POST['mrko'];

$giArray = explode("\n",$gi);
$woArray = explode("\n",$wo);
$mrkoArray = explode("\n",$mrko);

// buang elemen kosong
$woArray = array_values(array_filter($woArray, function($val) {
    return trim($val) !== '';
}));
$giArray = array_values(array_filter($giArray, function($val) {
    return trim($val) !== '';
}));
$mrkoArray = array_values(array_filter($mrkoArray, function($val) {
    return trim($val) !== '';
}));

// hitung isi array
$woCount = count($woArray);
$giCount = count($giArray);
$mrkoCount = count($mrkoArray);

if($qty == $woCount && $qty == $giCount && $qty == $mrkoCount){
    $a=0;
    foreach ($_POST['idstock'] as $index => $idstock) {
        $wox = $woArray[$a];
        $gix = $giArray[$a];
        $mrkox = $mrkoArray[$a];
        $query = mysqli_query($koneksi6, "UPDATE stock 
            SET status='Done',
                wo='$wox',
                gi='$gix',
                gi_date='$date',
                picgi='$picgi',
                mrko='$mrkox'  
            WHERE stock.id_stock=$idstock;");
        $a++;    
    }
    echo "<script>alert('Data updated!'); window.location.href='vhs_halamanstockvhs.php';</script>";
    exit;
}
else{
    echo "<script>alert('Qty tire tidak sesuai dgn jumlah WO/GI/MRKO!'); window.history.back();</script>";
    exit;
}
?>
