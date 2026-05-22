<?php

require_once "koneksi.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

// ==========================
// CONFIG WA
// ==========================
$apiKey  = "09b3e08979d1474cb81c55c040744ca9";
// Menggunakan groupId dari contoh kode WA yang Anda lampirkan (bisa disesuaikan kembali)
$groupId = "120363425240446101@g.us"; 

// ==========================
// FUNCTION KIRIM WA
// ==========================
function sendWhatsapp($message, $apiKey, $groupId){

    $payload = json_encode([
        "apiKey"   => $apiKey,
        "id_group" => $groupId,
        "message"  => $message
    ]);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.82.92.181/api/sendMessageGroup",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "Content-Length: " . strlen($payload)
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    return [
        "response" => $response,
        "httpcode" => $httpCode,
        "error"    => $curlError
    ];
}

// ==========================
// MODE POST
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            "status" => "error",
            "message" => "No input data"
        ]);
        exit;
    }

    $temperature = $data['temperature'] ?? null;
    $pressure    = $data['pressure'] ?? null;
    $unit        = $data['unit'] ?? 'PPA-BIB';
    $device_id   = $data['device_id'] ?? null;
    $timestamp   = $data['timestamp'] ?? date("Y-m-d H:i:s");

    // ==========================
    // VALIDASI INPUT
    // ==========================
    if ($temperature === null || $pressure === null || !$device_id) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid sensor data"
        ]);
        exit;
    }

    // ==========================
    // INSERT DATABASE
    // ==========================
    $stmt = $koneksi->prepare("
        INSERT INTO sensor_data
        (
            temperature,
            pressure,
            unit,
            device_id,
            timestamp,
            is_notified
        )
        VALUES (?, ?, ?, ?, ?, 0)
    ");

    $stmt->bind_param(
        "ddsss",
        $temperature,
        $pressure,
        $unit,
        $device_id,
        $timestamp
    );

    if (!$stmt->execute()) {
        echo json_encode([
            "status" => "error",
            "message" => "Insert gagal"
        ]);
        exit;
    }

    // Ambil ID data yang baru saja masuk
    $lastId = $stmt->insert_id;

    // ==========================
    // PENGKONDISIAN DATA BARU (RULE ALARM)
    // ==========================
    $status = [];
    $isCritical = false;

    // Rule 1: Overheat
    if ($temperature >= 140) {
        $status[] = "OVERHEAT";
        $isCritical = true;
    }

    // Rule 2: Over Press
    if ($pressure >= 32) {
        $status[] = "OVER PRESS";
        $isCritical = true;
    }

    // ==========================
    // PROSES KIRIM NOTIFIKASI WA REAL-TIME
    // ==========================
    if ($isCritical) {
        $statusText = implode(" & ", $status);

        // Format Pesan terstruktur menggunakan data yang baru di-insert
        $msg  = "🔔 *MONITORING ALARM SENSOR*\n";
        $msg .= "Status Terdeteksi: *" . $statusText . "*\n\n";
        $msg .= "🚨 *ALARM INDUSTRIAL SENSOR*\n";
        $msg .= "Device ID : " . $device_id . "\n";
        $msg .= "Unit      : " . $unit . "\n";
        $msg .= "Temp      : " . $temperature . " °C\n";
        $msg .= "Pressure  : " . $pressure . " psi\n";
        $msg .= "Time      : " . $timestamp;

        // Eksekusi fungsi kirim WA
        $wa = sendWhatsapp($msg, $apiKey, $groupId);

        // Catat ke log file untuk kebutuhan debug/tracing
        file_put_contents(
            "log_wa.txt",
            date("Y-m-d H:i:s") . " | ID: " . $lastId . " | RESPONSE : " . json_encode($wa) . PHP_EOL,
            FILE_APPEND
        );

        // Jika curl tidak error dan API mengembalikan response sukses, update is_notified
        if (empty($wa['error'])) {
            // Menggunakan prepared statement agar lebih aman dari SQL Injection
            $updateStmt = $koneksi->prepare("UPDATE sensor_data SET is_notified = 1 WHERE id = ?");
            $updateStmt->bind_param("i", $lastId);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Data tersimpan",
        "alarm_triggered" => $isCritical
    ]);

    $stmt->close();
    $koneksi->close();
    exit;
}

// ==========================
// MODE GET
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $query = "
        SELECT sd.*
        FROM sensor_data sd
        INNER JOIN (
            SELECT device_id, MAX(timestamp) as max_time
            FROM sensor_data
            GROUP BY device_id
        ) latest
        ON sd.device_id = latest.device_id
        AND sd.timestamp = latest.max_time
        ORDER BY sd.timestamp DESC
    ";

    $result = $koneksi->query($query);

    if (!$result) {
        echo json_encode([
            "status" => "error",
            "message" => "Query gagal"
        ]);
        exit;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "device_id"   => $row['device_id'],
            "temperature" => (float)$row['temperature'],
            "pressure"    => (float)$row['pressure'],
            "timestamp"   => $row['timestamp']
        ];
    }

    echo json_encode([
        "status" => "success",
        "total_device" => count($data),
        "data" => $data
    ]);

    $koneksi->close();
    exit;
}

// Jika method bukan POST atau GET
echo json_encode([
    "status" => "error",
    "message" => "Invalid method"
]);
$koneksi->close();

?>