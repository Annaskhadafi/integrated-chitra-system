<?php

require_once "koneksi.php";

date_default_timezone_set('Asia/Makassar');

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

// ==========================
// CONFIG WA
// ==========================

// Gunakan API key yang sekarang Anda pakai.
$apiKey = "MASUKKAN_API_KEY_ANDA";

// Gunakan group ID yang sekarang Anda pakai.
$groupId = "MASUKKAN_GROUP_ID_ANDA";

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

    if (!$data) {
        echo json_encode([
            "status" => "error",
            "message" => "No input data"
        ]);

        exit;
    }

    /*
     * Tetap mendukung temperature1 seperti kode lama.
     * Juga mendukung nama temperature dari format baru.
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

    /*
     * Memastikan data sensor menjadi angka.
     */
    $temperature1 = (float)$temperature1;
    $temperature2 = (float)$temperature2;
    $pressure = (float)$pressure;

    // ==========================
    // STATUS TEMPERATURE 1
    // ==========================
    /*
     * Jika status_temp1 dikirim device,
     * gunakan status dari device.
     *
     * Jika tidak dikirim, tetap gunakan
     * perhitungan lama: temperature1 >= 140.
     */
    if (isset($data['status_temp1'])) {
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
    if (isset($data['status_temp2'])) {
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
    if (isset($data['status_pressure'])) {
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
    /*
     * Mendukung status_alarm dari format baru.
     * Mendukung alarm_status jika nama tersebut dikirim.
     *
     * Jika keduanya tidak dikirim, alarm tetap dihitung
     * menggunakan status sensor seperti kode lama.
     */
    if (isset($data['status_alarm'])) {

        $statusAlarm = normalizeStatus(
            $data['status_alarm']
        );

    } elseif (isset($data['alarm_status'])) {

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
        trim($data['status_overall']) !== ""
    ) {
        $statusOverall = trim(
            $data['status_overall']
        );
    } else {
        $statusOverall = createStatusOverall(
            $statusPressure,
            $statusTemp1,
            $statusTemp2
        );
    }

    /*
     * Membatasi panjang status_overall
     * sesuai kolom VARCHAR(50).
     */
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
     * Jika status_alarm aktif tetapi semua status sensor 0,
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

    /*
     * d = double
     * s = string
     * i = integer
     *
     * Urutan:
     * d temperature1
     * d temperature2
     * d pressure
     * s unit
     * s device_id
     * s timestamp
     * i alarm_status
     * i status_pressure
     * s status_overall
     * i status_temp1
     * i status_temp2
     */
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
            }
        }

        $checkStmt->close();

        // ==========================
        // KIRIM WA
        // ==========================
        if ($allowSend) {

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
                $temperature1 .
                " °C\n";

            $msg .= "Temp 2    : " .
                $temperature2 .
                " °C\n";

            $msg .= "Pressure  : " .
                $pressure .
                " psi\n";

            $msg .= "Time      : " .
                $timestamp;

            $wa = sendWhatsapp(
                $msg,
                $apiKey,
                $groupId
            );

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
                $updateStmt =
                    $koneksi->prepare("
                        UPDATE sensor_data
                        SET is_notified = 1
                        WHERE id = ?
                    ");

                $updateStmt->bind_param(
                    "i",
                    $lastId
                );

                $updateStmt->execute();

                $updateStmt->close();
            }
        }
    }

    // ==========================
    // RESPONSE POST
    // ==========================
    echo json_encode([
        "status" => "success",
        "message" => "Data tersimpan",

        /*
         * Field lama tetap dipertahankan.
         */
        "alarm_triggered" => $isCritical,

        /*
         * Field tambahan.
         */
        "data" => [
            "device_id" => $device_id,
            "unit" => $unit,

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
    ]);

    $stmt->close();
    $koneksi->close();

    exit;
}

// ==========================
// MODE GET
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    /*
     * Query ini tetap sama:
     * mengambil data terakhir setiap device.
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
            "message" => "Query gagal"
        ]);

        exit;
    }

    $data = [];

    while (
        $row =
            $result->fetch_assoc()
    ) {
        /*
         * Status dari database.
         */
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
         * Perhitungan lama tetap dipertahankan.
         *
         * Ini penting agar data lama yang statusnya masih 0
         * tetapi nilainya sudah melewati batas tetap terbaca
         * sebagai kondisi critical.
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

        /*
         * Alarm menggunakan data database.
         * Namun perhitungan lama tetap menjadi fallback.
         */
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

        // ==========================
        // STATUS TEXT LAMA
        // ==========================
        $statusArr = [];

        if ($statusTemp1 === 1) {
            $statusArr[] =
                "OVERHEAT T1";
        }

        if ($statusTemp2 === 1) {
            $statusArr[] =
                "OVERHEAT T2";
        }

        if ($statusPressure === 1) {
            $statusArr[] =
                "OVER PRESS";
        }

        $statusText =
            empty($statusArr)
                ? "NORMAL"
                : implode(
                    " & ",
                    $statusArr
                );

        // ==========================
        // STATUS OVERALL
        // ==========================
        $statusOverall =
            isset($row['status_overall'])
                ? trim($row['status_overall'])
                : "";

        /*
         * Jika status overall kosong, buat otomatis.
         *
         * Jika status overall masih "normal" tetapi nilai lama
         * sudah critical, status overall juga dibuat ulang.
         */
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

            /*
             * Unit ditambahkan karena digunakan dashboard.
             */
            "unit" =>
                $row['unit'],

            /*
             * Field lama tetap ada.
             */
            "temperature1" =>
                (float)$row['temperature1'],

            /*
             * Field baru untuk halamansensor.php.
             */
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

            /*
             * Field lama dipertahankan.
             */
            "status_text" =>
                $statusText,

            "alarm_status" =>
                $alarmStatus,

            /*
             * Field tambahan yang dibaca dashboard.
             */
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