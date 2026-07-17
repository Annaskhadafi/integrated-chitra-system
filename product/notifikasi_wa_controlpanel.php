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
// Gunakan API key dan group ID Anda yang sekarang.
$apiKey = "MASUKKAN_API_KEY_ANDA";
$groupId = "MASUKKAN_GROUP_ID_ANDA";

// ==============================
// QUERY DATA SENSOR ALARM
// ==============================
// Mengambil data yang:
// 1. Belum pernah dikirim notifikasi.
// 2. Memiliki salah satu status alarm aktif.
//
// Rule lama tetap dipertahankan sebagai fallback:
// TEMP 1 >= 140
// TEMP 2 >= 140
// PRESSURE >= 32
// ==============================
$query = "
SELECT
    id,
    device_id,
    unit,
    timestamp,
    is_notified,
    alarm_status,
    pressure,
    status_pressure,
    status_overall,
    temperature1,
    status_temp1,
    temperature2,
    status_temp2
FROM sensor_data
WHERE
    is_notified = 0
AND
(
    alarm_status = 1
    OR status_pressure = 1
    OR status_temp1 = 1
    OR status_temp2 = 1
    OR temperature1 >= 140
    OR temperature2 >= 140
    OR pressure >= 32
)
ORDER BY id ASC
LIMIT 20
";

// ==============================
// EKSEKUSI QUERY
// ==============================
$perintah = mysqli_query($koneksi, $query);

if (!$perintah) {
    die("Query Error : " . mysqli_error($koneksi));
}

// ==============================
// ARRAY PESAN DAN ID
// ==============================
$list_pesan = [];
$list_id = [];

// ==============================
// LOOP DATA
// ==============================
if (mysqli_num_rows($perintah) > 0) {

    while ($data = mysqli_fetch_assoc($perintah)) {

        $id = (int)$data['id'];

        $device_id = $data['device_id'];
        $unit = $data['unit'];
        $timestamp = $data['timestamp'];

        $temperature1 = (float)$data['temperature1'];
        $temperature2 = (float)$data['temperature2'];
        $pressure = (float)$data['pressure'];

        /*
         * Status dari database.
         */
        $statusTemp1 = (int)$data['status_temp1'];
        $statusTemp2 = (int)$data['status_temp2'];
        $statusPressure = (int)$data['status_pressure'];
        $alarmStatus = (int)$data['alarm_status'];

        $statusOverall = isset($data['status_overall'])
            ? trim($data['status_overall'])
            : "";

        // ==============================
        // FALLBACK RULE LAMA
        // ==============================
        // Status database tetap diprioritaskan.
        // Rule nilai sensor dipakai untuk data lama
        // yang statusnya belum tersimpan.
        if ($temperature1 >= 140) {
            $statusTemp1 = 1;
        }

        if ($temperature2 >= 140) {
            $statusTemp2 = 1;
        }

        if ($pressure >= 32) {
            $statusPressure = 1;
        }

        if (
            $statusTemp1 === 1 ||
            $statusTemp2 === 1 ||
            $statusPressure === 1
        ) {
            $alarmStatus = 1;
        }

        // ==============================
        // SUSUN STATUS
        // ==============================
        $status = [];

        if ($statusTemp1 === 1) {
            $status[] = "OVERHEAT T1";
        }

        if ($statusTemp2 === 1) {
            $status[] = "OVERHEAT T2";
        }

        if ($statusPressure === 1) {
            $status[] = "OVER PRESS";
        }

        /*
         * Apabila alarm_status = 1 tetapi tidak ada
         * status sensor yang aktif.
         */
        if (
            $alarmStatus === 1 &&
            empty($status)
        ) {
            $status[] = "ALARM ACTIVE";
        }

        $statusText = empty($status)
            ? "NORMAL"
            : implode(" & ", $status);

        /*
         * Jika status_overall kosong, buat berdasarkan
         * status sensor.
         */
        if ($statusOverall === "") {

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

            $statusOverall = empty($overall)
                ? "normal"
                : implode("_", $overall);
        }

        $overallText = strtoupper(
            str_replace(
                ["_", "-"],
                " ",
                $statusOverall
            )
        );

        // ==============================
        // FORMAT PESAN
        // ==============================
        $msg  = "🚨 *ALARM INDUSTRIAL SENSOR*\n";
        $msg .= "Device ID : " . $device_id . "\n";
        $msg .= "Unit      : " . $unit . "\n";

        $msg .= "Temp 1    : " .
            number_format($temperature1, 2, '.', '') .
            " °C\n";

        $msg .= "Temp 2    : " .
            number_format($temperature2, 2, '.', '') .
            " °C\n";

        $msg .= "Pressure  : " .
            number_format($pressure, 2, '.', '') .
            " psi\n";

        $msg .= "Status    : *" .
            $statusText .
            "*\n";

        $msg .= "Overall   : *" .
            $overallText .
            "*\n";

        $msg .= "Time      : " .
            $timestamp;

        $list_pesan[] = $msg;
        $list_id[] = $id;

        /*
         * Tidak melakukan UPDATE di sini.
         *
         * is_notified baru diubah menjadi 1
         * setelah WhatsApp berhasil dikirim.
         */
    }
}

// ==============================
// KIRIM WA
// ==============================
if (!empty($list_pesan)) {

    $full_message  = "🔔 *MONITORING ALARM SENSOR*\n";

    $full_message .= "Total Alarm : *" .
        count($list_pesan) .
        "*\n\n";

    $full_message .= implode(
        "\n\n-------------------\n\n",
        $list_pesan
    );

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
        CURLOPT_URL =>
            "http://103.82.92.181/api/sendMessageGroup",

        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POSTFIELDS => $jsonPayload,

        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "Content-Length: " . strlen($jsonPayload)
        ],
    ]);

    $response = curl_exec($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $curlError = curl_error($curl);

    // ==============================
    // CEK HASIL PENGIRIMAN
    // ==============================
    if (!empty($curlError)) {

        echo "CURL ERROR : " . $curlError;

        /*
         * Jangan ubah is_notified karena WA gagal.
         */

    } elseif ($httpCode == 200) {

        echo "STATUS PENGIRIMAN : BERHASIL";
        echo "<pre>";
        print_r($response);
        echo "</pre>";

        // ==============================
        // UPDATE SETELAH WA BERHASIL
        // ==============================
        $updateStmt = $koneksi->prepare("
            UPDATE sensor_data
            SET is_notified = 1
            WHERE id = ?
        ");

        if (!$updateStmt) {

            echo "<br>UPDATE ERROR : " .
                $koneksi->error;

        } else {

            foreach ($list_id as $idSensor) {

                $updateStmt->bind_param(
                    "i",
                    $idSensor
                );

                if (!$updateStmt->execute()) {

                    echo "<br>Gagal update ID " .
                        $idSensor .
                        " : " .
                        $updateStmt->error;
                }
            }

            $updateStmt->close();
        }

    } else {

        echo "STATUS PENGIRIMAN : GAGAL";
        echo "<br>HTTP CODE : " . $httpCode;
        echo "<pre>";
        print_r($response);
        echo "</pre>";

        /*
         * Jangan ubah is_notified karena server WA
         * tidak mengembalikan HTTP 200.
         */
    }


    curl_close($curl);

} else {

    echo "Tidak ada alarm aktif.";
}

// ==============================
// CLOSE DB
// ==============================
// Kode sebelumnya menggunakan $koneksi3,
// padahal variabel koneksinya adalah $koneksi.
mysqli_close($koneksi);

?>