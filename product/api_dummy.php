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
$groupId = "120363425240446101@g.us";

// Cooldown notif WA = 1 jam
$cooldownMinutes = 60;

// ==========================
// FUNCTION KIRIM WA
// ==========================
function sendWhatsapp($message, $apiKey, $groupId)
{
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

    $temperature1 = $data['temperature1'] ?? null;
    $temperature2 = $data['temperature2'] ?? null;
    $pressure     = $data['pressure'] ?? null;
    $unit         = $data['unit'] ?? 'PPA-BIB';
    $device_id    = $data['device_id'] ?? null;
    $timestamp    = date("Y-m-d H:i:s");

    // ==========================
    // VALIDASI
    // ==========================
    if (
        $temperature1 === null ||
        $temperature2 === null ||
        $pressure === null ||
        !$device_id
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid sensor data"
        ]);
        exit;
    }

    // ==========================
    // STATUS ALARM
    // ==========================
    $status = [];
    $isCritical = false;

    if ($temperature1 >= 140) {
        $status[] = "OVERHEAT T1";
        $isCritical = true;
    }

    if ($temperature2 >= 140) {
        $status[] = "OVERHEAT T2";
        $isCritical = true;
    }

    if ($pressure >= 32) {
        $status[] = "OVER PRESS";
        $isCritical = true;
    }

    // ==========================
    // INSERT DATABASE
    // ==========================
    $stmt = $koneksi->prepare("
        INSERT INTO sensor_data
        (
            temperature1,
            temperature2,
            pressure,
            unit,
            device_id,
            timestamp,
            is_notified
        )
        VALUES (?, ?, ?, ?, ?, ?, 0)
    ");

    $stmt->bind_param(
        "dddsss",
        $temperature1,
        $temperature2,
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

    $lastId = $stmt->insert_id;

    // ==========================
    // COOLDOWN WA
    // ==========================
    if ($isCritical) {

        $allowSend = true;

        $checkStmt = $koneksi->prepare("
            SELECT timestamp
            FROM sensor_data
            WHERE device_id = ?
            AND is_notified = 1
            ORDER BY id DESC
            LIMIT 1
        ");

        $checkStmt->bind_param("s", $device_id);
        $checkStmt->execute();

        $resultCheck = $checkStmt->get_result();

        if ($rowCheck = $resultCheck->fetch_assoc()) {

            $lastNotifTime = strtotime($rowCheck['timestamp']);
            $currentTime   = time();

            $diffMinutes = ($currentTime - $lastNotifTime) / 60;

            if ($diffMinutes < $cooldownMinutes) {
                $allowSend = false;
            }
        }

        $checkStmt->close();

        // ==========================
        // KIRIM WA
        // ==========================
        if ($allowSend) {

            $statusText = implode(" & ", $status);

            $msg  = "ЁЯЪи *MONITORING ALARM SENSOR*\n";
            $msg .= "Status : *" . $statusText . "*\n\n";

            $msg .= "тЪая╕П *ALARM INDUSTRIAL SENSOR*\n";
            $msg .= "Device ID : " . $device_id . "\n";
            $msg .= "Unit      : " . $unit . "\n";
            $msg .= "Temp 1    : " . $temperature1 . " ┬░C\n";
            $msg .= "Temp 2    : " . $temperature2 . " ┬░C\n";
            $msg .= "Pressure  : " . $pressure . " psi\n";
            $msg .= "Time      : " . $timestamp;

            $wa = sendWhatsapp($msg, $apiKey, $groupId);

            file_put_contents(
                "log_wa.txt",
                date("Y-m-d H:i:s")
                    . " | ID: " . $lastId
                    . " | RESPONSE : "
                    . json_encode($wa)
                    . PHP_EOL,
                FILE_APPEND
            );

            if (
                empty($wa['error']) &&
                $wa['httpcode'] == 200
            ) {

                $updateStmt = $koneksi->prepare("
                    UPDATE sensor_data
                    SET is_notified = 1
                    WHERE id = ?
                ");

                $updateStmt->bind_param("i", $lastId);

                $updateStmt->execute();

                $updateStmt->close();
            }
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
            SELECT device_id, MAX(id) as max_id
            FROM sensor_data
            GROUP BY device_id
        ) latest
        ON sd.device_id = latest.device_id
        AND sd.id = latest.max_id
        ORDER BY sd.id DESC
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

        $alarmStatus = 0;
        $statusArr = [];

        if ($row['temperature1'] >= 140) {
            $alarmStatus = 1;
            $statusArr[] = "OVERHEAT T1";
        }

        if ($row['temperature2'] >= 140) {
            $alarmStatus = 1;
            $statusArr[] = "OVERHEAT T2";
        }

        if ($row['pressure'] >= 32) {
            $alarmStatus = 1;
            $statusArr[] = "OVER PRESS";
        }

        $statusText = empty($statusArr)
            ? "NORMAL"
            : implode(" & ", $statusArr);

        $data[] = [
            "device_id"    => $row['device_id'],
            "temperature1" => (float)$row['temperature1'],
            "temperature2" => (float)$row['temperature2'],
            "pressure"     => (float)$row['pressure'],
            "status_text"  => $statusText,
            "alarm_status" => $alarmStatus,
            "timestamp"    => $row['timestamp']
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

// ==========================
// INVALID METHOD
// ==========================
echo json_encode([
    "status" => "error",
    "message" => "Invalid method"
]);

$koneksi->close();
