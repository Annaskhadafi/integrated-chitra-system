<?php

// ==============================
// DEBUG
// ==============================
error_reporting(E_ALL);
ini_set('display_errors', '0');

// ==============================
// KONEKSI DATABASE
// ==============================
include "koneksi.php";

/*
 * Pastikan file koneksi.php membuat variabel:
 *
 * $koneksi
 */
if (
    !isset($koneksi) ||
    !($koneksi instanceof mysqli)
) {
    die("Koneksi database tidak tersedia.");
}

// ==============================
// CONFIG WA
// ==============================
// Isi menggunakan API key dan group ID asli
// yang sama seperti di api_dummy.php.
$apiKey = "09b3e08979d1474cb81c55c040744ca9";
$groupId = "120363425240446101@g.us";

// ==============================
// QUERY DATA SENSOR ALARM
// ==============================
// Mengambil maksimal 20 data alarm yang:
// 1. Belum berhasil dikirim ke WhatsApp.
// 2. Memiliki salah satu status alarm aktif.
//
// Fallback lama tetap dipertahankan:
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
$perintah = mysqli_query(
    $koneksi,
    $query
);

if (!$perintah) {
    error_log("Query Error in notifikasi_wa_controlpanel.php: " . mysqli_error($koneksi));
    die("Terjadi kesalahan saat memproses data.");
}

// ==============================
// ARRAY PESAN DAN ID
// ==============================
$listPesan = [];
$listId = [];

// ==============================
// LOOP DATA
// ==============================
if (mysqli_num_rows($perintah) > 0) {

    while (
        $data = mysqli_fetch_assoc($perintah)
    ) {

        $id = (int)$data['id'];

        $deviceId = $data['device_id'];
        $unit = $data['unit'];
        $timestamp = $data['timestamp'];

        $temperature1 =
            (float)$data['temperature1'];

        $temperature2 =
            (float)$data['temperature2'];

        $pressure =
            (float)$data['pressure'];

        // ==============================
        // STATUS DARI DATABASE
        // ==============================
        $statusTemp1 =
            (int)$data['status_temp1'];

        $statusTemp2 =
            (int)$data['status_temp2'];

        $statusPressure =
            (int)$data['status_pressure'];

        $alarmStatus =
            (int)$data['alarm_status'];

        $statusOverall =
            isset($data['status_overall'])
                ? trim($data['status_overall'])
                : "";

        // ==============================
        // FALLBACK RULE LAMA
        // ==============================
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
        // SUSUN STATUS PESAN
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
         * Jika alarm_status aktif tetapi tidak ada
         * status sensor yang aktif.
         */
        if (
            $alarmStatus === 1 &&
            empty($status)
        ) {
            $status[] = "ALARM ACTIVE";
        }

        /*
         * Query seharusnya hanya mengambil data alarm.
         * Proteksi ini mencegah data normal ikut terkirim.
         */
        if (
            $alarmStatus !== 1 &&
            empty($status)
        ) {
            continue;
        }

        $statusText = implode(
            " & ",
            $status
        );

        // ==============================
        // STATUS OVERALL
        // ==============================
        $isSensorCritical = (
            $statusTemp1 === 1 ||
            $statusTemp2 === 1 ||
            $statusPressure === 1
        );

        /*
         * Buat ulang status overall apabila kosong,
         * atau masih normal padahal sensor critical.
         */
        if (
            $statusOverall === "" ||
            (
                strtolower($statusOverall) === "normal" &&
                $isSensorCritical
            )
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
        $message  = "🚨 *ALARM INDUSTRIAL SENSOR*\n";
        $message .= "Device ID : " . $deviceId . "\n";
        $message .= "Unit      : " . $unit . "\n";

        $message .= "Temp 1    : " .
            number_format(
                $temperature1,
                2,
                '.',
                ''
            ) .
            " °C\n";

        $message .= "Temp 2    : " .
            number_format(
                $temperature2,
                2,
                '.',
                ''
            ) .
            " °C\n";

        $message .= "Pressure  : " .
            number_format(
                $pressure,
                2,
                '.',
                ''
            ) .
            " psi\n";

        $message .= "Status    : *" .
            $statusText .
            "*\n";

        $message .= "Overall   : *" .
            $overallText .
            "*\n";

        $message .= "Time      : " .
            $timestamp;

        $listPesan[] = $message;
        $listId[] = $id;

        /*
         * is_notified belum diubah di sini.
         * Update dilakukan setelah WA benar-benar
         * diterima oleh server WhatsApp.
         */
    }
}

// ==============================
// KIRIM WA
// ==============================
if (!empty($listPesan)) {

    $fullMessage  =
        "🔔 *MONITORING ALARM SENSOR*\n";

    $fullMessage .=
        "Total Alarm : *" .
        count($listPesan) .
        "*\n\n";

    $fullMessage .= implode(
        "\n\n-------------------\n\n",
        $listPesan
    );

    // ==============================
    // PAYLOAD JSON
    // ==============================
    $dataToSend = [
        "apiKey"   => $apiKey,
        "id_group" => $groupId,
        "message"  => $fullMessage
    ];

    $jsonPayload = json_encode(
        $dataToSend,
        JSON_UNESCAPED_UNICODE
    );

    if ($jsonPayload === false) {
        die(
            "JSON Error : " .
            json_last_error_msg()
        );
    }

    // ==============================
    // CURL
    // ==============================
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        [
            CURLOPT_URL =>
                "http://103.82.92.181/api/sendMessageGroup",

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,

            /*
             * Batas waktu koneksi dan keseluruhan request.
             */
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,

            CURLOPT_POSTFIELDS =>
                $jsonPayload,

            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Accept: application/json",
                "Content-Length: " .
                    strlen($jsonPayload)
            ]
        ]
    );

    $response = curl_exec($curl);

    $httpCode = curl_getinfo(
        $curl,
        CURLINFO_HTTP_CODE
    );

    $curlError = curl_error($curl);

    // ==============================
    // SIMPAN LOG PENGIRIMAN
    // ==============================
    file_put_contents(
        __DIR__ . "/log_notifikasiwa.txt",
        date("Y-m-d H:i:s") .
        " | TOTAL: " .
        count($listPesan) .
        " | HTTP: " .
        $httpCode .
        " | ERROR: " .
        $curlError .
        " | RESPONSE: " .
        $response .
        PHP_EOL,
        FILE_APPEND
    );

    // ==============================
    // CEK HASIL PENGIRIMAN
    // ==============================
    if (!empty($curlError)) {

        echo "STATUS PENGIRIMAN : GAGAL";
        echo "<br>CURL ERROR : " .
            htmlspecialchars($curlError);

        /*
         * is_notified tetap 0 supaya dapat
         * dicoba lagi pada proses berikutnya.
         */

    } elseif (
        $httpCode >= 200 &&
        $httpCode < 300
    ) {

        echo "STATUS PENGIRIMAN : BERHASIL";
        echo "<br>HTTP CODE : " .
            $httpCode;

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
                htmlspecialchars(
                    $koneksi->error
                );

        } else {

            foreach ($listId as $idSensor) {

                $updateStmt->bind_param(
                    "i",
                    $idSensor
                );

                if (!$updateStmt->execute()) {

                    echo "<br>Gagal update ID " .
                        $idSensor .
                        " : " .
                        htmlspecialchars(
                            $updateStmt->error
                        );
                }
            }

            $updateStmt->close();
        }

    } else {

        echo "STATUS PENGIRIMAN : GAGAL";
        echo "<br>HTTP CODE : " .
            $httpCode;

        echo "<pre>";
        print_r($response);
        echo "</pre>";

        /*
         * is_notified tetap 0 karena server WA
         * mengembalikan kode di luar 200–299.
         */
    }

    curl_close($curl);

} else {

    echo "Tidak ada alarm aktif yang belum dikirim.";
}

// ==============================
// CLOSE DB
// ==============================
mysqli_close($koneksi);

?>