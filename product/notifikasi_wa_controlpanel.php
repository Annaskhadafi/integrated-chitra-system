<?php

// ==============================
// DEBUG
// ==============================
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ==============================
// KONEKSI DATABASE
// ==============================
include "koneksi.php";

// ==============================
// CONFIG WA
// ==============================
$apiKey = "09b3e08979d1474cb81c55c040744ca9";
$groupId = "120363425240446101@g.us";

// ==============================
// QUERY DATA SENSOR ALARM
// RULE:
// TEMP >= 140
// PRESSURE >= 32
// ==============================
$query = "
SELECT 
    id,
    device_id,
    temperature,
    pressure,
    unit,
    timestamp
FROM sensor_data
WHERE
    is_notified = 0
AND
(
    temperature >= 140
    OR pressure >= 32
)
ORDER BY id ASC
LIMIT 20
";

// ==============================
// EKSEKUSI QUERY
// ==============================
$perintah = mysqli_query($koneksi, $query);

if(!$perintah){
    die("Query Error : " . mysqli_error($koneksi));
}

// ==============================
// ARRAY PESAN
// ==============================
$list_pesan = [];

// ==============================
// LOOP DATA
// ==============================
if(mysqli_num_rows($perintah) > 0){

    while($data = mysqli_fetch_assoc($perintah)){

        $id         = $data['id'];
        $device_id  = $data['device_id'];
        $temp       = $data['temperature'];
        $pressure   = $data['pressure'];
        $unit       = $data['unit'];
        $timestamp  = $data['timestamp'];

        $status = [];

        // ==============================
        // RULE ALARM
        // ==============================
        if($temp >= 140){
            $status[] = "OVERHEAT";
        }

        if($pressure >= 32){
            $status[] = "OVER PRESS";
        }

        $statusText = implode(" & ", $status);

        // ==============================
        // FORMAT PESAN
        // ==============================
        $msg  = "🚨 *ALARM INDUSTRIAL SENSOR*\n";
        $msg .= "Device ID : " . $device_id . "\n";
        $msg .= "Unit      : " . $unit . "\n";
        $msg .= "Temp      : " . $temp . " °C\n";
        $msg .= "Pressure  : " . $pressure . " psi\n";
        $msg .= "Status    : *" . $statusText . "*\n";
        $msg .= "Time      : " . $timestamp;

        $list_pesan[] = $msg;

        // ==============================
        // UPDATE AGAR TIDAK SPAM
        // ==============================
        mysqli_query($koneksi, "
            UPDATE sensor_data
            SET is_notified = 1
            WHERE id = '$id'
        ");
    }
}

// ==============================
// KIRIM WA
// ==============================
if(!empty($list_pesan)){

    $full_message  = "🔔 *MONITORING ALARM SENSOR*\n";
    $full_message .= "Total Alarm : *" . count($list_pesan) . "*\n\n";
    $full_message .= implode("\n\n-------------------\n\n", $list_pesan);

    // ==============================
    // PAYLOAD JSON
    // ==============================
    $dataToSend = [
        "apiKey"   => $apiKey,
        "id_group" => $groupId,
        "message"  => $full_message
    ];

    $jsonPayload = json_encode($dataToSend);

    // ==============================
    // CURL
    // ==============================
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.82.92.181/api/sendMessageGroup",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonPayload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "Content-Length: " . strlen($jsonPayload)
        ],
    ]);

    $response = curl_exec($curl);

    // ==============================
    // DEBUG RESPONSE
    // ==============================
    if(curl_errno($curl)){

        echo "CURL ERROR : " . curl_error($curl);

    } else {

        echo "STATUS PENGIRIMAN : ";
        echo "<pre>";
        print_r($response);
        echo "</pre>";
    }

    curl_close($curl);

} else {

    echo "Tidak ada alarm aktif.";
}

// ==============================
// CLOSE DB
// ==============================
mysqli_close($koneksi3);

?>