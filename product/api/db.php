<?php
include "../koneksi.php";

$perintah = mysqli_query($koneksi, "SELECT 
    `date`,
    `lastytdrevenue`,
    `ytdbudget`,
    `month`,
    `exchangerate`,
    `target`,
    `lastmtdrevenue`,
    `ici index 4200`,
    `ici index 3400`,
    `ici index 6500`,
    `ici index 5000`,
    `ici index 5800`,
    `variance`,
    `ytdrevenue`
    FROM coalPrice as cp"
);

$datatopredict = array();

while ($data = mysqli_fetch_array($perintah)) {
    if ($data['date'] != "") {
        $datatopredict[] = array(
            "date" => $data['date'],
            "lastytdrevenue" => $data['lastytdrevenue'],
            "ytdbudget" => $data['ytdbudget'],
            "month" => $data['month'],
            "exchangerate" => $data['exchangerate'],
            "target" => $data['target'],
            "lastmtdrevenue" => $data['lastmtdrevenue'],
            "ici_index_4200" => $data['ici index 4200'],
            "ici_index_3400" => $data['ici index 3400'],
            "ici_index_6500" => $data['ici index 6500'],
            "ici_index_5000" => $data['ici index 5000'],
            "ici_index_5800" => $data['ici index 5800'],
            "variance" => $data['variance'],
            "ytdrevenue" => $data['ytdrevenue']
        );
    }
}

// Mengatur header agar output berupa JSON
header('Content-Type: application/json');

// Menampilkan data dalam bentuk JSON
echo json_encode($datatopredict);
?>
