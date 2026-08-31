<?php
include "koneksi.php";

// Mendapatkan tahun dan bulan saat ini
$tahun_ini = date("Y");
$bulan_terakhir = date("m"); 

// Membuat array $period
$period = array();

// Menambahkan bulan-bulan yang sudah muncul di tahun ini ke dalam array
for ($i = 1; $i <= $bulan_terakhir; $i++) {
    $bulan = sprintf("%02d", $i);
    $period[] = "$tahun_ini-$bulan";
}

// Tidak perlu membalik array $period
// $period = array_reverse($period);

$datatopredict = array();

foreach ($period as $periode) {
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
        `variance`
        FROM coalPrice as cp
        WHERE DATE_FORMAT(cp.date, '%Y-%m') = '$periode' AND ytdrevenue > 1"
    );

    if ($perintah) {
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
                    "variance" => $data['variance']
                );
            }
        }
    } else {
        // Menangani kesalahan query
        error_log("Query error in prediksi.php: " . mysqli_error($koneksi));
        echo "<p>Terjadi kesalahan saat memproses data prediksi.</p>";
    }
}
// print_r($datatopredict);
?>

<!-- Membuat formulir -->
<form id="form" action="http://10.41.100.11:8080/prediksi" method="POST">
    <?php
    foreach ($datatopredict as $data) {
        foreach ($data as $key => $value) {
            echo '<input type="hidden" name="'.$key.'[]" value="'.$value.'">';
        }
    }
    ?>
</form>

<!-- Mengirimkan formulir secara otomatis -->
<script type="text/javascript">
    window.onload = function() {
        document.getElementById("form").submit();
    };
</script>
