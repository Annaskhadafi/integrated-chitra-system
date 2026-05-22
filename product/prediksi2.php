<?php
include "koneksi.php";
// Tangkap data dari form

$periodpredict = $_POST["date"]; // Pastikan form mengirimkan data ini
$exchangerate = [16290, 15589, 15245, 15305, 15348, 15445];
$ici_index_6500 = [150, 153, 142, 143, 143, 140];
$ici_index_5800 = [90, 93, 92, 94, 93, 96];
$ici_index_5000 = [74, 75, 78, 77, 70, 65];
$ici_index_4200 = [62, 68, 69, 68, 67, 68];
$ici_index_3400 = [48, 53, 48, 48, 46, 41];

// Gabungkan data dalam array
$data = [
    'periodpredict' => $periodpredict,
    'exchangerate' => $exchangerate,
    'ici_index_6500' => $ici_index_6500,
    'ici_index_5800' => $ici_index_5800,
    'ici_index_5000' => $ici_index_5000,
    'ici_index_4200' => $ici_index_4200,
    'ici_index_3400' => $ici_index_3400,
];

// Ubah array menjadi JSON
$json_data = json_encode($data);

// Output JSON
header('Content-Type: application/json');
echo $json_data;
?>
