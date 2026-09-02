<?php
$name = $_POST['name'] ?? '';
$idwo = $_POST['idwo'] ?? '';
$wo = $_POST['wo'] ?? '';
$date = $_POST['date'] ?? '';
$status = $_POST['status'] ?? '';
$inv = $_POST['inv'] ?? null;
$invdate = $_POST['invdate'] ?? null;
$isAjax = (
    (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_POST['ajax']) && $_POST['ajax'] == '1')
);

if ($isAjax) {
    $status = 'Progress';
}

$statusfix = $status !== '' ? $status : 'Progress';
$inv = ($inv === null || $inv === '') ? null : $inv;
$invdate = ($invdate === null || $invdate === '') ? null : $invdate;

include "koneksi.php";

function repair_updatewo_json_response($payload, $httpCode = 200)
{
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function repair_updatewo_redirect($message, $redirectUrl)
{
    echo "<script>
    alert (" . json_encode($message) . ");
    window.location.replace(" . json_encode($redirectUrl) . ");
    </script>";
    exit;
}

$redirectUrl = 'repair_halamanwo.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
    $referrer = parse_url($_SERVER['HTTP_REFERER']);
    $path = $referrer['path'] ?? '';
    if (basename($path) === 'repair_halamanwo.php') {
        $redirectUrl = 'repair_halamanwo.php';
        if (!empty($referrer['query'])) {
            $redirectUrl .= '?' . $referrer['query'];
        }
    }
}

if ($idwo === '') {
    if ($isAjax) {
        repair_updatewo_json_response(array(
            'success' => false,
            'message' => 'ID WO tidak ditemukan.'
        ), 422);
    }

    repair_updatewo_redirect('Please fill the blank page', $redirectUrl);
}

if ($wo === '' || $date === '') {
    if ($isAjax) {
        repair_updatewo_json_response(array(
            'success' => false,
            'message' => 'WO dan tanggal wajib diisi.'
        ), 422);
    }

    repair_updatewo_redirect('Please fill the blank page', $redirectUrl);
}

try {
    $stmt = mysqli_prepare($koneksi3, "
        UPDATE work_order
        SET
            wo = ?,
            status = ?,
            createby = ?,
            wo_date = ?,
            invoice = ?,
            invoice_date = ?
        WHERE id_wo = ?
    ");
    mysqli_stmt_bind_param($stmt, "sssssss", $wo, $statusfix, $name, $date, $inv, $invdate, $idwo);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
} catch (mysqli_sql_exception $e) {
    error_log("repair_updatewo update failed: " . $e->getMessage());

    if ($isAjax) {
        repair_updatewo_json_response(array(
            'success' => false,
            'message' => 'Gagal menyimpan WO.'
        ), 500);
    }

    repair_updatewo_redirect('Gagal menyimpan WO.', $redirectUrl);
}

if ($isAjax) {
    $updated = array();
    $finishDate = '-';

    try {
        $stmt = mysqli_prepare($koneksi3, "SELECT * FROM work_order WHERE id_wo = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $idwo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $updated = $result ? mysqli_fetch_assoc($result) : array();
        mysqli_stmt_close($stmt);
    } catch (mysqli_sql_exception $e) {
        error_log("repair_updatewo refresh row failed: " . $e->getMessage());
    }

    try {
        $stmt = mysqli_prepare($koneksi3, "
            SELECT date
            FROM job
            WHERE wo = ?
            AND (job = 'Painting' OR job = 'painting')
            LIMIT 1
        ");
        mysqli_stmt_bind_param($stmt, "s", $idwo);
        mysqli_stmt_execute($stmt);
        $finishResult = mysqli_stmt_get_result($stmt);
        if ($finishResult && ($finishData = mysqli_fetch_assoc($finishResult))) {
            $finishDate = $finishData['date'];
        }
        mysqli_stmt_close($stmt);
    } catch (mysqli_sql_exception $e) {
        error_log("repair_updatewo finish date lookup failed: " . $e->getMessage());
    }

    $updatedStatus = $updated['status'] ?? $statusfix;
    $statusClass = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $updatedStatus));
    $statusClass = trim($statusClass, '-');

    repair_updatewo_json_response(array(
        'success' => true,
        'message' => 'WO submited.',
        'id_wo' => $updated['id_wo'] ?? $idwo,
        'wo' => $updated['wo'] ?? $wo,
        'wo_date' => $updated['wo_date'] ?? $date,
        'size' => $updated['size'] ?? '',
        'tire_sn' => $updated['tire_sn'] ?? '',
        'injury' => $updated['injury'] ?? '',
        'job_type' => $updated['job_type'] ?? '',
        'type' => $updated['type'] ?? '',
        'customer' => $updated['customer'] ?? '',
        'site' => $updated['site'] ?? '',
        'received_date' => $updated['received_date'] ?? '',
        'inspect_date' => $updated['inspect_date'] ?? '',
        'finish_date' => $finishDate,
        'invoice' => $updated['invoice'] ?? '',
        'invoice_date' => $updated['invoice_date'] ?? '',
        'store_loc' => $updated['store_loc'] ?? '',
        'createby' => $updated['createby'] ?? $name,
        'status' => $updatedStatus,
        'statusClass' => $statusClass,
        'detailAction' => 'progress'
    ));
}

repair_updatewo_redirect('WO submited.', $redirectUrl);
?>
