<?php
session_start();

require_once __DIR__ . '/koneksi.php';

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    http_response_code(500);
    echo 'Dompdf belum terpasang. Jalankan composer install di folder product.';
    exit;
}

require_once $autoloadPath;

use Dompdf\Dompdf;
use Dompdf\Options;

function repair_jobcard_pdf_h($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function repair_jobcard_pdf_fetch_one($connection, $sql, $types = '', $params = array())
{
    $stmt = mysqli_prepare($connection, $sql);
    if ($types !== '') {
        $refs = array();
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }
        mysqli_stmt_bind_param($stmt, $types, ...$refs);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $row;
}

function repair_jobcard_pdf_fetch_all($connection, $sql, $types = '', $params = array())
{
    $stmt = mysqli_prepare($connection, $sql);
    if ($types !== '') {
        $refs = array();
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }
        mysqli_stmt_bind_param($stmt, $types, ...$refs);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while ($result && ($row = mysqli_fetch_assoc($result))) {
        $rows[] = $row;
    }
    mysqli_stmt_close($stmt);

    return $rows;
}

function repair_jobcard_pdf_filename($workOrder)
{
    $parts = array(
        $workOrder['wo'] ?? '',
        $workOrder['tire_sn'] ?? '',
        $workOrder['customer'] ?? '',
        $workOrder['site'] ?? ''
    );

    $parts = array_map(function ($value) {
        $value = preg_replace('/\s+/', ' ', trim((string) $value));
        return preg_replace('/[\/\\\\:*?"<>|]/', '', $value);
    }, $parts);

    $parts = array_filter($parts, function ($value) {
        return $value !== '';
    });

    $filename = implode('-', $parts);
    return $filename !== '' ? $filename : 'Repair Jobcard';
}

$idwo = $_GET['id'] ?? '';
if ($idwo === '') {
    http_response_code(400);
    echo 'ID WO tidak ditemukan.';
    exit;
}

$workOrder = repair_jobcard_pdf_fetch_one(
    $koneksi3,
    'SELECT * FROM work_order WHERE id_wo = ? LIMIT 1',
    's',
    array($idwo)
);

if (!$workOrder) {
    http_response_code(404);
    echo 'WO tidak ditemukan.';
    exit;
}

$remark = $workOrder['remark'] ?? '';
$progressDateRow = repair_jobcard_pdf_fetch_one(
    $koneksi3,
    "SELECT DATE(date) AS date FROM job WHERE wo = ? AND job = 'Skiving' ORDER BY date ASC LIMIT 1",
    's',
    array($idwo)
);
$finishDateRow = repair_jobcard_pdf_fetch_one(
    $koneksi3,
    "SELECT DATE(date) AS date FROM job WHERE wo = ? AND (job = 'Painting' OR job = 'painting') ORDER BY date DESC LIMIT 1",
    's',
    array($idwo)
);

$processRows = repair_jobcard_pdf_fetch_all(
    $koneksi3,
    'SELECT DISTINCT proseske FROM job WHERE wo = ? ORDER BY proseske ASC',
    's',
    array($idwo)
);

$processes = array();
$materialSummary = array();

foreach ($processRows as $processRow) {
    $processNo = $processRow['proseske'];
    $jobMaterialRows = repair_jobcard_pdf_fetch_all(
        $koneksi3,
        "SELECT
            a.wo,
            a.job,
            b.material_name,
            b.smu,
            DATE(a.date) AS date,
            a.time,
            a.person,
            a.note,
            a.qty,
            a.proseske,
            CONCAT(a.wo, a.job, a.proseske) AS kunci
        FROM job a
        LEFT JOIN material_stock b ON a.material = b.id_matstock
        WHERE a.wo = ? AND a.proseske = ?
        ",
        'ss',
        array($idwo, $processNo)
    );

    $jobs = array();
    $materialsByJob = array();
    $qtyByJob = array();
    $uomByJob = array();

    foreach ($jobMaterialRows as $row) {
        $key = $row['kunci'];
        if (!isset($jobs[$key])) {
            $jobs[$key] = $row;
            $materialsByJob[$key] = '';
            $qtyByJob[$key] = 0;
            $uomByJob[$key] = '';
        }

        $materialName = trim((string) ($row['material_name'] ?? ''));
        $uom = trim((string) ($row['smu'] ?? ''));
        $qty = is_numeric($row['qty'] ?? null) ? (float) $row['qty'] : 0;

        if ($materialName !== '') {
            $materialsByJob[$key] .= ($materialsByJob[$key] !== '' ? ', ' : '') . $materialName;
            $qtyByJob[$key] += $qty;
            $uomByJob[$key] = $uom;

            $summaryKey = $materialName . '|' . $uom;
            if (!isset($materialSummary[$summaryKey])) {
                $materialSummary[$summaryKey] = array(
                    'material' => $materialName,
                    'qty' => 0,
                    'uom' => $uom
                );
            }
            $materialSummary[$summaryKey]['qty'] += $qty;
        }
    }

    $processes[] = array(
        'proseske' => $processNo,
        'jobs' => array_values($jobs),
        'materialsByJob' => $materialsByJob,
        'qtyByJob' => $qtyByJob,
        'uomByJob' => $uomByJob
    );
}

$logoDataUri = '';
$logoPdfPath = __DIR__ . '/images/cp_logo_pdf.jpg';
$logoPath = __DIR__ . '/images/cp_logo.png';
if (is_file($logoPdfPath)) {
    $logoDataUri = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPdfPath));
} elseif (extension_loaded('gd') && is_file($logoPath)) {
    $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
}

$filename = repair_jobcard_pdf_filename($workOrder);

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo repair_jobcard_pdf_h($filename); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 8mm;
        }

        body {
            margin: 0;
            color: #000;
            background: #fff;
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }

        .jobcard-header {
            width: 100%;
            border-bottom: 1px solid #d6dbe1;
            margin-bottom: 10px;
            padding-bottom: 9px;
        }

        .jobcard-title {
            width: 72%;
            font-size: 15px;
            font-weight: normal;
        }

        .jobcard-logo {
            width: 28%;
            text-align: right;
        }

        .jobcard-logo img {
            height: 42px;
        }

        .jobcard-logo-text {
            font-size: 13px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            margin-bottom: 32px;
            border-collapse: collapse;
        }

        .info-table td {
            width: 33.33%;
            vertical-align: top;
            padding-right: 10px;
        }

        .info-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table table td {
            width: auto;
            padding: 2px 0;
        }

        .label {
            width: 105px;
            font-weight: bold;
        }

        .process-table,
        .material-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        .process-table {
            margin-bottom: 60px;
        }

        .material-table {
            margin-bottom: 20px;
        }

        .process-table th,
        .process-table td,
        .material-table th,
        .material-table td {
            padding: 7px 8px;
            font-size: 11px;
            border: 0;
            border-bottom: 1px solid #d9d9d9;
            vertical-align: middle;
            text-align: left;
        }

        .process-table th,
        .material-table th {
            font-weight: bold;
            border-bottom: 1px solid #d0d0d0;
        }

        .material-table thead tr:first-child th {
            border-top: 1px solid #d0d0d0;
        }

        .process-title th {
            border-top: 1px solid #d0d0d0;
            border-bottom: 1px solid #d0d0d0;
            background: #fff;
        }

        .total-row td {
            background: #fff;
        }

        .is-struck {
            text-decoration: line-through;
        }

        .footer-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .footer-table td {
            vertical-align: top;
        }

        .sign-area {
            width: 35%;
        }

        .footer-spacer {
            width: 10%;
        }

        .material-area {
            width: 55%;
        }

        .lead {
            font-weight: bold;
            font-size: 13px;
            margin: 0 0 8px;
        }

        .sign-box {
            width: 120px;
            height: 75px;
            border: 1px solid #000;
            background: #fff;
        }

        .muted {
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="jobcard-header">
        <tr>
            <td class="jobcard-title">Repair Jobcard : <u><?php echo repair_jobcard_pdf_h($workOrder['wo'] ?? ''); ?></u></td>
            <td class="jobcard-logo">
                <?php if ($logoDataUri !== ''): ?>
                    <img src="<?php echo $logoDataUri; ?>" alt="Logo">
                <?php else: ?>
                    <span class="jobcard-logo-text">Integrated Chitra System</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>
                <table>
                    <tr>
                        <td class="label">Customer</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['customer'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Project</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['site'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Serial Number</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['tire_sn'] ?? ''); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td class="label">Tyre Brand</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['brand'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tyre Pattern</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['pattern'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tyre Size</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['size'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tyre Type</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['type'] ?? ''); ?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td class="label">Wo Date</td>
                        <td>: <?php echo repair_jobcard_pdf_h($workOrder['wo_date'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Progress Date</td>
                        <td>: <?php echo repair_jobcard_pdf_h($progressDateRow['date'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Finish Date</td>
                        <td>: <?php echo repair_jobcard_pdf_h($finishDateRow['date'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Injury</td>
                        <td>: <?php echo repair_jobcard_pdf_h($remark); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php foreach ($processes as $process): ?>
        <?php $totalTime = 0; ?>
        <table class="process-table">
            <thead>
                <tr class="process-title">
                    <th colspan="7">Process #<?php echo repair_jobcard_pdf_h($process['proseske']); ?></th>
                </tr>
                <tr>
                    <th style="width: 15%;">Injuries</th>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 15%;">Process</th>
                    <th style="width: 25%;">Material</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 13%;">Duration (Min)</th>
                    <th style="width: 10%;">Manpower</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($process['jobs'] as $job): ?>
                    <?php
                        $key = $job['kunci'];
                        $jobName = $job['job'] ?? '';
                        $time = is_numeric($job['time'] ?? null) ? (float) $job['time'] : 0;
                        $totalTime += $time;
                        $injury = '';
                        if ($jobName === 'Dimensi Luka') {
                            $injury = $job['note'] ?? '';
                        } elseif ($jobName === 'Skiving') {
                            $injury = $remark;
                        }
                        $materialName = $process['materialsByJob'][$key] !== '' ? $process['materialsByJob'][$key] : '-';
                        $qty = $process['qtyByJob'][$key] ?? 0;
                        $uom = $process['uomByJob'][$key] ?? '';
                        $qtyText = $qty . ($uom !== '' ? ' ' . $uom : '');
                    ?>
                    <tr>
                        <td><?php echo repair_jobcard_pdf_h($injury); ?></td>
                        <td><?php echo repair_jobcard_pdf_h($job['date'] ?? ''); ?></td>
                        <td>
                            <?php if ($time == 0): ?>
                                <span class="is-struck"><?php echo repair_jobcard_pdf_h($jobName); ?></span>
                            <?php else: ?>
                                <?php echo repair_jobcard_pdf_h($jobName); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo repair_jobcard_pdf_h($materialName); ?></td>
                        <td><?php echo repair_jobcard_pdf_h($qtyText); ?></td>
                        <td><?php echo repair_jobcard_pdf_h($job['time'] ?? ''); ?></td>
                        <td><?php echo repair_jobcard_pdf_h($job['person'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right;"><strong>Total Duration:</strong></td>
                    <td><strong><?php echo number_format($totalTime / 60, 2); ?></strong></td>
                    <td><strong>Hours</strong></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>

    <table class="footer-table">
        <tr>
            <td class="sign-area">
                <p class="lead">Quality Check:</p>
                <div class="sign-box"></div>
                <p style="margin-top: 5px; font-size: 11px;">Sign</p>
            </td>
            <td class="footer-spacer"></td>
            <td class="material-area">
                <table class="material-table">
                    <thead>
                        <tr>
                            <th>Material Summary</th>
                            <th>Qty</th>
                            <th>Uom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($materialSummary) > 0): ?>
                            <?php foreach ($materialSummary as $summary): ?>
                                <tr>
                                    <td><?php echo repair_jobcard_pdf_h($summary['material']); ?></td>
                                    <td><?php echo repair_jobcard_pdf_h($summary['qty']); ?></td>
                                    <td><?php echo repair_jobcard_pdf_h($summary['uom']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="muted">No material used</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
<?php
$html = ob_get_clean();

$options = new Options();
$options->setDefaultFont('Helvetica');
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(false);

$dompdf = new Dompdf($options);
$dompdf->setPaper('A4', 'portrait');
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->addInfo('Title', $filename);

while (ob_get_level() > 0) {
    ob_end_clean();
}

$dompdf->stream($filename . '.pdf', array('Attachment' => false));
exit;
?>
