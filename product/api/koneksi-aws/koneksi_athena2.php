<?php
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed. Use GET.']);
    exit;
}

$aws = require __DIR__ . '/../../../../../athena_config.php';
require 'vendor/autoload.php';

use Aws\Athena\AthenaClient;
use Aws\Exception\AwsException;

$credentials = [
    'region'      => 'ap-southeast-1',
    'version'     => 'latest',
    'credentials' => [
        'key'    => $aws['key'],
        'secret' => $aws['secret'],
    ],
];

$athenaClient = new AthenaClient($credentials);

$currentYear = date('Y');
$prevYear    = $currentYear - 1;

$query = "
SELECT 
    \"po_no.#2\" AS po,
    SUM(\"po_quantity#8\") AS qty_ordered,
    SUM(\"gr_quantity#11\") AS qty_received,
    CASE
        WHEN SUM(\"gr_quantity#11\") = 0 THEN 'waiting supply'
        WHEN SUM(\"gr_quantity#11\") > 0 AND SUM(\"gr_quantity#11\") < SUM(\"po_quantity#8\") THEN 'partial'
        WHEN SUM(\"gr_quantity#11\") = SUM(\"po_quantity#8\") THEN 'completed'
        ELSE 'unknown'
    END AS status_gr
FROM \"dev_cp\".\"act_sts_po\"
WHERE year(\"po_date#7\") BETWEEN {$prevYear} AND {$currentYear}
GROUP BY \"po_no.#2\"
";
        
$outputLocation = "s3://dev-mdu-dataplatform-cp-landing/actpo/";
$workGroup = "mdu-dataplatform-cp";

try {
    $startQueryExecutionResponse = $athenaClient->startQueryExecution([
        'QueryString' => $query,
        'ResultConfiguration' => [
            'OutputLocation' => $outputLocation,
        ],
        'WorkGroup' => $workGroup,
    ]);

    $queryExecutionId = $startQueryExecutionResponse->get('QueryExecutionId');

    // tunggu query selesai
    do {
        $getQueryExecutionResponse = $athenaClient->getQueryExecution([
            'QueryExecutionId' => $queryExecutionId,
        ]);
        $status = $getQueryExecutionResponse->get('QueryExecution')['Status']['State'];
        sleep(2);
    } while ($status === 'RUNNING' || $status === 'QUEUED');

    if ($status === 'SUCCEEDED') {
        $allRows = [];
        $headers = [];
        $nextToken = null;
        $rowNumber = 1;

        do {
            $params = ['QueryExecutionId' => $queryExecutionId];
            if ($nextToken) {
                $params['NextToken'] = $nextToken;
            }

            $getQueryResultsResponse = $athenaClient->getQueryResults($params);

            $resultSet = $getQueryResultsResponse->get('ResultSet');
            $rows = $resultSet['Rows'];

            // ambil header sekali di awal
            if (empty($headers)) {
                $headers = array_map(function ($col) {
                    return $col['VarCharValue'];
                }, $rows[0]['Data']);
                array_shift($rows); // buang baris header
            }

            foreach ($rows as $row) {
                $rowData = [];
                foreach ($row['Data'] as $idx => $col) {
                    $rowData[$headers[$idx]] = $col['VarCharValue'] ?? null;
                }
                // tambahkan nomor urut
                $rowData['row_numb'] = $rowNumber++;
                $allRows[] = $rowData;
            }

            $nextToken = $getQueryResultsResponse->get('NextToken');
        } while ($nextToken);

        header('Content-Type: application/json');
        echo json_encode($allRows, JSON_PRETTY_PRINT);
    } else {
        $reason = $getQueryExecutionResponse->get('QueryExecution')['Status']['StateChangeReason'];
        echo json_encode(['error' => "Query failed: " . $reason]);
    }

} catch (AwsException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
