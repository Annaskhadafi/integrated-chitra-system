<?php
// Debugging
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Sesuaikan dengan file koneksi Anda
include "koneksi.php"; 

$apiKey = "09b3e08979d1474cb81c55c040744ca9";
$tahun_bulan = date('Y-m');

$query = "SELECT wo, tire_sn, size, customer, site, received_date, status 
          FROM work_order 
          WHERE status = 'w/ work_order';";

// Pastikan variabel koneksi adalah $koneksi3 sesuai file koneksi.php Anda
$perintah = mysqli_query($koneksi3, $query);

$list_pesan = [];
$total_wo = 0; // Inisialisasi variabel total

if ($perintah && mysqli_num_rows($perintah) > 0) {
    $total_wo = mysqli_num_rows($perintah); // Mengambil total baris dari query
    
    while ($data = mysqli_fetch_assoc($perintah)) {
        $msg = "📌 *Reminder Work Order*\n";
        $msg .= "WO: " . $data['wo'] . "\n";
        $msg .= "SN Tire: " . $data['tire_sn'] . "\n";
        $msg .= "Size: " . $data['size'] . "\n";
        $msg .= "Customer: " . $data['customer'] . " (" . $data['site'] . ")\n";
        $msg .= "Received: " . $data['received_date'] . "\n";
        $msg .= "Status: *" . $data['status'] . "*";
        
        $list_pesan[] = $msg;
    }
}

if (!empty($list_pesan)) {
    // Menambahkan Total WO ke dalam header pesan
    $full_message = "🔔 *DAFTAR ANTRIAN WORK ORDER*\n";
    $full_message .= "Total Antrian: *" . $total_wo . " WO*\n\n";
    $full_message .= implode("\n\n---\n\n", $list_pesan);
    
    $groupId = "120363408403034968@g.us"; 

    // Data yang akan dikirim dikonversi ke JSON
    $dataToSend = [
        "apiKey"   => $apiKey,
        "id_group" => $groupId,
        "message"  => $full_message
    ];
    $jsonPayload = json_encode($dataToSend);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.82.92.181/api/sendMessageGroup",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonPayload, // Kirim string JSON
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json", // Header diubah ke JSON
            "Accept: application/json",
            "Content-Length: " . strlen($jsonPayload)
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    echo "Status Pengiriman: " . $response;
} else {
    echo "Tidak ada data dengan status 'w/ work_order'.";
}

mysqli_close($koneksi3);
?>