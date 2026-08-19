<?php

require_once "koneksi.php";

date_default_timezone_set('Asia/Makassar');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

// ==========================
// CONFIG WA
// ==========================

// WAJIB diganti dengan API key asli Anda.
$apiKey = "09b3e08979d1474cb81c55c040744ca9";

// WAJIB diganti dengan group ID asli Anda.
$groupId = "120363425240446101@g.us";

/*
 * 0  = setiap data alarm langsung kirim WA.
 * 60 = device yang sama hanya boleh kirim 1 kali per jam.
 *
 * Untuk testing Postman, gunakan 0.
 */
$cooldownMinutes = 0;

// ==========================
// FUNCTION KIRIM WA
// ==========================
function sendWhatsapp($message, $apiKey, $groupId)
{
    $payload = json_encode([
        "apiKey"   => $apiKey,
        "id_group" => $groupId,
        "message"  => $message
    ], JSON_UNESCAPED_UNICODE);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://103.82.92.181/api/sendMessageGroup",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "Content-Length: " . strlen($payload)
        ],
    ]);

    $response = curl_exec($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $curlError = curl_error($curl);

    curl_close($curl);

    return [
        "response" => $response,
        "httpcode" => $httpCode,
        "error"    => $curlError
    ];
}

// ==========================
// HELPER STATUS
// ==========================
function normalizeStatus($value)
{
    return (int)$value === 1 ? 1 : 0;
}

// ==========================
// MEMBUAT STATUS OVERALL
// ==========================
function createStatusOverall(
    $statusPressure,
    $statusTemp1,
    $statusTemp2
) {
    $overall = [];

    if ($statusPressure === 1) {
        $overall[] = "overpressure";
    }

    if ($statusTemp1 === 1) {
        $overall[] = "overtemp1";
    }

    if ($statusTemp2 === 1) {
        $overall[] = "overtemp2";
    }

    if (empty($overall)) {
        return "normal";
    }

    return implode("_", $overall);
}

// ==========================
// MODE POST
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (!is_array($data)) {
        echo json_encode([
            "status" => "error",
            "message" => "No input data"
        ]);

        exit;
    }

    /*
     * Tetap mendukung temperature1 seperti kode lama.
     * Juga mendukung temperature untuk dashboard baru.
     */
    $temperature1 =
        $data['temperature1'] ??
        $data['temperature'] ??
        null;

    $temperature2 =
        $data['temperature2'] ??
        null;

    $pressure =
        $data['pressure'] ??
        null;

    $unit =
        $data['unit'] ??
        'PPA-BIB';

    $device_id =
        $data['device_id'] ??
        null;

    $timestamp = date("Y-m-d H:i:s");

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

    if (
        !is_numeric($temperature1) ||
        !is_numeric($temperature2) ||
        !is_numeric($pressure)
    ) {
        echo json_encode([
            "status" => "error",
            "message" => "Temperature dan pressure harus berupa angka"
        ]);

        exit;
    }

    $temperature1 = (float)$temperature1;
    $temperature2 = (float)$temperature2;
    $pressure = (float)$pressure;

    // ==========================
    // STATUS TEMPERATURE 1
    // ==========================
    if (array_key_exists('status_temp1', $data)) {

        $statusTemp1 = normalizeStatus(
            $data['status_temp1']
        );

    } else {

        $statusTemp1 =
            $temperature1 >= 140
                ? 1
                : 0;
    }

    // ==========================
    // STATUS TEMPERATURE 2
    // ==========================
    if (array_key_exists('status_temp2', $data)) {

        $statusTemp2 = normalizeStatus(
            $data['status_temp2']
        );

    } else {

        $statusTemp2 =
            $temperature2 >= 140
                ? 1
                : 0;
    }

    // ==========================
    // STATUS PRESSURE
    // ==========================
    if (array_key_exists('status_pressure', $data)) {

        $statusPressure = normalizeStatus(
            $data['status_pressure']
        );

    } else {

        $statusPressure =
            $pressure >= 32
                ? 1
                : 0;
    }

    // ==========================
    // STATUS ALARM
    // ==========================
    if (array_key_exists('status_alarm', $data)) {

        $statusAlarm = normalizeStatus(
            $data['status_alarm']
        );

    } elseif (array_key_exists('alarm_status', $data)) {

        $statusAlarm = normalizeStatus(
            $data['alarm_status']
        );

    } else {

        $statusAlarm = (
            $statusTemp1 === 1 ||
            $statusTemp2 === 1 ||
            $statusPressure === 1
        ) ? 1 : 0;
    }

    // ==========================
    // STATUS OVERALL
    // ==========================
    if (
        isset($data['status_overall']) &&
        trim((string)$data['status_overall']) !== ""
    ) {
        $statusOverall = trim(
            (string)$data['status_overall']
        );
    } else {
        $statusOverall = createStatusOverall(
            $statusPressure,
            $statusTemp1,
            $statusTemp2
        );
    }

    $statusOverall = substr(
        $statusOverall,
        0,
        50
    );

    // ==========================
    // STATUS UNTUK WA
    // ==========================
    $status = [];
    $isCritical = false;

    if ($statusTemp1 === 1) {
        $status[] = "OVERHEAT T1";
        $isCritical = true;
    }

    if ($statusTemp2 === 1) {
        $status[] = "OVERHEAT T2";
        $isCritical = true;
    }

    if ($statusPressure === 1) {
        $status[] = "OVER PRESS";
        $isCritical = true;
    }

    /*
     * Jika status alarm aktif tetapi semua status sensor 0,
     * alarm tetap dianggap critical.
     */
    if (
        $statusAlarm === 1 &&
        empty($status)
    ) {
        $status[] = "ALARM ACTIVE";
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
            is_notified,
            alarm_status,
            status_pressure,
            status_overall,
            status_temp1,
            status_temp2
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            0,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ");

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "Prepare insert gagal: " .
                $koneksi->error
        ]);

        exit;
    }

    $stmt->bind_param(
        "dddsssiisii",
        $temperature1,
        $temperature2,
        $pressure,
        $unit,
        $device_id,
        $timestamp,
        $statusAlarm,
        $statusPressure,
        $statusOverall,
        $statusTemp1,
        $statusTemp2
    );

    if (!$stmt->execute()) {
        echo json_encode([
            "status" => "error",
            "message" => "Insert gagal: " .
                $stmt->error
        ]);

        $stmt->close();
        exit;
    }

    $lastId = $stmt->insert_id;

    /*
     * Nilai awal hasil proses WhatsApp.
     * Nilai ini akan ikut ditampilkan di Postman.
     */
    $waAttempted = false;
    $waSent = false;
    $waStatus = "not_attempted";
    $waHttpCode = null;
    $waError = "";
    $waResponse = "";
    $waSkippedReason = "";

    // ==========================
    // PROSES WHATSAPP
    // ==========================
    if ($isCritical) {

        $allowSend = true;

        /*
         * Cooldown hanya diperiksa jika nilainya lebih dari 0.
         * Saat testing dengan cooldown 0, WA langsung dikirim.
         */
        if ($cooldownMinutes > 0) {

            $checkStmt = $koneksi->prepare("
                SELECT timestamp
                FROM sensor_data
                WHERE device_id = ?
                AND is_notified = 1
                ORDER BY id DESC
                LIMIT 1
            ");

            if ($checkStmt) {

                $checkStmt->bind_param(
                    "s",
                    $device_id
                );

                $checkStmt->execute();

                $resultCheck =
                    $checkStmt->get_result();

                if (
                    $rowCheck =
                        $resultCheck->fetch_assoc()
                ) {
                    $lastNotifTime = strtotime(
                        $rowCheck['timestamp']
                    );

                    $currentTime = time();

                    $diffMinutes = (
                        $currentTime -
                        $lastNotifTime
                    ) / 60;

                    if (
                        $diffMinutes <
                        $cooldownMinutes
                    ) {
                        $allowSend = false;

                        $waStatus =
                            "skipped";

                        $waSkippedReason =
                            "Device masih dalam cooldown notifikasi";
                    }
                }

                $checkStmt->close();
            }
        }

        // ==========================
        // KIRIM WA
        // ==========================
        if ($allowSend) {

            $waAttempted = true;
            $waStatus = "processing";

            $statusText = implode(
                " & ",
                $status
            );

            $msg  = "🚨 *MONITORING ALARM SENSOR*\n";

            $msg .= "Status : *" .
                $statusText .
                "*\n\n";

            $msg .= "⚠️ *ALARM INDUSTRIAL SENSOR*\n";

            $msg .= "Device ID : " .
                $device_id .
                "\n";

            $msg .= "Unit      : " .
                $unit .
                "\n";

            $msg .= "Temp 1    : " .
                number_format(
                    $temperature1,
                    2,
                    '.',
                    ''
                ) .
                " °C\n";

            $msg .= "Temp 2    : " .
                number_format(
                    $temperature2,
                    2,
                    '.',
                    ''
                ) .
                " °C\n";

            $msg .= "Pressure  : " .
                number_format(
                    $pressure,
                    2,
                    '.',
                    ''
                ) .
                " psi\n";

            $msg .= "Overall   : " .
                strtoupper(
                    str_replace(
                        ["_", "-"],
                        " ",
                        $statusOverall
                    )
                ) .
                "\n";

            $msg .= "Time      : " .
                $timestamp;

            $wa = sendWhatsapp(
                $msg,
                $apiKey,
                $groupId
            );

            $waHttpCode = $wa['httpcode'];
            $waError = $wa['error'];
            $waResponse = $wa['response'];

            // ==========================
            // LOG WA
            // ==========================
            file_put_contents(
                __DIR__ . "/log_wa.txt",
                date("Y-m-d H:i:s")
                    . " | ID: " . $lastId
                    . " | DEVICE: " . $device_id
                    . " | HTTP: " . $waHttpCode
                    . " | ERROR: " . $waError
                    . " | RESPONSE: " . $waResponse
                    . PHP_EOL,
                FILE_APPEND
            );

            /*
             * Anggap berhasil untuk seluruh kode HTTP 200-299.
             */
            if (
                empty($waError) &&
                $waHttpCode >= 200 &&
                $waHttpCode < 300
            ) {
                $waSent = true;
                $waStatus = "success";

                $updateStmt =
                    $koneksi->prepare("
                        UPDATE sensor_data
                        SET is_notified = 1
                        WHERE id = ?
                    ");

                if ($updateStmt) {

                    $updateStmt->bind_param(
                        "i",
                        $lastId
                    );

                    $updateStmt->execute();

                    $updateStmt->close();
                }

            } else {

                $waStatus = "failed";
            }
        }

    } else {

        $waStatus = "not_required";
        $waSkippedReason =
            "Data sensor tidak dalam kondisi alarm";
    }

    // ==========================
    // RESPONSE POST
    // ==========================
    echo json_encode([
        "status" => "success",
        "message" => "Data tersimpan",

        "alarm_triggered" => $isCritical,

        /*
         * Hasil pengiriman WhatsApp.
         */
        "whatsapp" => [
            "attempted" => $waAttempted,
            "sent" => $waSent,
            "status" => $waStatus,
            "http_code" => $waHttpCode,
            "error" => $waError,
            "response" => $waResponse,
            "skipped_reason" => $waSkippedReason
        ],

        "data" => [
            "id" => $lastId,

            "device_id" =>
                $device_id,

            "unit" =>
                $unit,

            "temperature" =>
                $temperature1,

            "temperature1" =>
                $temperature1,

            "temperature2" =>
                $temperature2,

            "pressure" =>
                $pressure,

            "status_pressure" =>
                $statusPressure,

            "status_temp1" =>
                $statusTemp1,

            "status_temp2" =>
                $statusTemp2,

            "status_alarm" =>
                $statusAlarm,

            "alarm_status" =>
                $statusAlarm,

            "status_overall" =>
                $statusOverall,

            "timestamp" =>
                $timestamp
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $stmt->close();
    $koneksi->close();

    exit;
}

// ==========================
// MODE GET
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    /*
     * Mengambil data terakhir dari setiap device.
     */
    $query = "
        SELECT sd.*
        FROM sensor_data sd

        INNER JOIN
        (
            SELECT
                device_id,
                MAX(id) AS max_id
            FROM sensor_data
            GROUP BY device_id
        ) latest

        ON sd.device_id = latest.device_id
        AND sd.id = latest.max_id

        ORDER BY sd.id DESC
    ";

    $result = $koneksi->query(
        $query
    );

    if (!$result) {
        echo json_encode([
            "status" => "error",
            "message" => "Query gagal: " .
                $koneksi->error
        ]);

        exit;
    }

    $data = [];

    while (
        $row =
            $result->fetch_assoc()
    ) {
        $statusPressure =
            isset($row['status_pressure'])
                ? (int)$row['status_pressure']
                : 0;

        $statusTemp1 =
            isset($row['status_temp1'])
                ? (int)$row['status_temp1']
                : 0;

        $statusTemp2 =
            isset($row['status_temp2'])
                ? (int)$row['status_temp2']
                : 0;

        /*
         * Fallback rule lama.
         */
        if (
            (float)$row['temperature1'] >= 140
        ) {
            $statusTemp1 = 1;
        }

        if (
            (float)$row['temperature2'] >= 140
        ) {
            $statusTemp2 = 1;
        }

        if (
            (float)$row['pressure'] >= 32
        ) {
            $statusPressure = 1;
        }

        $alarmStatus =
            isset($row['alarm_status'])
                ? (int)$row['alarm_status']
                : 0;

        if (
            $statusTemp1 === 1 ||
            $statusTemp2 === 1 ||
            $statusPressure === 1
        ) {
            $alarmStatus = 1;
        }

        $statusArr = [];

        if ($statusTemp1 === 1) {
            $statusArr[] = "OVERHEAT T1";
        }

        if ($statusTemp2 === 1) {
            $statusArr[] = "OVERHEAT T2";
        }

        if ($statusPressure === 1) {
            $statusArr[] = "OVER PRESS";
        }

        $statusText =
            empty($statusArr)
                ? "NORMAL"
                : implode(
                    " & ",
                    $statusArr
                );

        $statusOverall =
            isset($row['status_overall'])
                ? trim($row['status_overall'])
                : "";

        if (
            $statusOverall === "" ||
            (
                strtolower($statusOverall) === "normal" &&
                (
                    $statusTemp1 === 1 ||
                    $statusTemp2 === 1 ||
                    $statusPressure === 1
                )
            )
        ) {
            $statusOverall =
                createStatusOverall(
                    $statusPressure,
                    $statusTemp1,
                    $statusTemp2
                );
        }

        $data[] = [
            "device_id" =>
                $row['device_id'],

            "unit" =>
                $row['unit'],

            "temperature1" =>
                (float)$row['temperature1'],

            "temperature" =>
                (float)$row['temperature1'],

            "temperature2" =>
                (float)$row['temperature2'],

            "pressure" =>
                (float)$row['pressure'],

            "status_pressure" =>
                $statusPressure,

            "status_temp1" =>
                $statusTemp1,

            "status_temp2" =>
                $statusTemp2,

            "status_text" =>
                $statusText,

            "alarm_status" =>
                $alarmStatus,

            "status_alarm" =>
                $alarmStatus,

            "status_overall" =>
                $statusOverall,

            "timestamp" =>
                $row['timestamp']
        ];
    }

    echo json_encode([
        "status" => "success",
        "total_device" => count($data),
        "data" => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

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