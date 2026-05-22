<?php
require_once __DIR__ . "/../../koneksi.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Use GET.']);
    exit;
}

try {
    $aws = require __DIR__ . '/../../../../../athena_config.php';
    if (!$aws || !isset($aws['api_key'])) {
        throw new Exception('File athena_config.php tidak valid atau api_key tidak ditemukan.');
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Gagal memuat file konfigurasi internal.',
        'detail' => $e->getMessage()
    ]);
    exit;
}

$headers = array_change_key_case(getallheaders(), CASE_LOWER);
$clientApiKey = $headers['apikey'] ?? null;

if ($clientApiKey !== $aws['api_key']) {
    http_response_code(401);
    echo json_encode([
        'error'     => 'Unauthorized. Invalid API key.',
        'clientApi' => $clientApiKey
    ]);
    exit;
}

function stockImport() {
    return [
        [
            "Item" => "110109A301",
            "Material Description" => "6.00 R 9 XZM TL",
            "Old Material No." => "200-110204",
            "Valuation Value" => 3331.93,
            "Store Loc" => "CP TRD BPN",
            "SLoc" => 101,
            "Total Stock" => 14
        ],
        [
            "Item" => "110109A301",
            "Material Description" => "6.00 R 9 XZM TL",
            "Old Material No." => "200-110204",
            "Valuation Value" => 0,
            "Store Loc" => "CP-Sangata",
            "SLoc" => 103,
            "Total Stock" => 0
        ],
        [
            "Item" => "110109A301",
            "Material Description" => "6.00 R 9 XZM TL",
            "Old Material No." => "200-110204",
            "Valuation Value" => 237.99,
            "Store Loc" => "KPC Sangatta",
            "SLoc" => 104,
            "Total Stock" => 1
        ],
        [
            "Item" => "110112A301",
            "Material Description" => "7.00 R 12 XZM",
            "Old Material No." => "200-110195",
            "Valuation Value" => 0,
            "Store Loc" => "CP TRD BPN",
            "SLoc" => 101,
            "Total Stock" => 0
        ],
        [
            "Item" => "110112A301",
            "Material Description" => "7.00 R 12 XZM",
            "Old Material No." => "200-110195",
            "Valuation Value" => 0,
            "Store Loc" => "CP-Sangata",
            "SLoc" => 103,
            "Total Stock" => 0
        ],
        [
            "Item" => "110112A301",
            "Material Description" => "7.00 R 12 XZM",
            "Old Material No." => "200-110195",
            "Valuation Value" => 1520.81,
            "Store Loc" => "KPC Sangatta",
            "SLoc" => 104,
            "Total Stock" => 4
        ],
        [
            "Item" => "110115C301",
            "Material Description" => "8.25 R 15 XZM TL 153 A5",
            "Old Material No." => "200-110218",
            "Valuation Value" => 1263.51,
            "Store Loc" => "CP TRD BPN",
            "SLoc" => 101,
            "Total Stock" => 2
        ],
        [
            "Item" => "110115C301",
            "Material Description" => "8.25 R 15 XZM TL 153 A5",
            "Old Material No." => "200-110218",
            "Valuation Value" => 2527.02,
            "Store Loc" => "CP-Sangata",
            "SLoc" => 103,
            "Total Stock" => 4
        ],
        [
            "Item" => "110115C301",
            "Material Description" => "8.25 R 15 XZM TL 153 A5",
            "Old Material No." => "200-110218",
            "Valuation Value" => 0,
            "Store Loc" => "Petrosea - FRP",
            "SLoc" => 130,
            "Total Stock" => 0
        ]
    ];
}

function poTrading() {
      global $koneksi;    
      $query = $koneksi->query("SELECT 
                                    ponumb,
                                    SUM(poqty) AS qtypo,
                                    SUM(grqty) AS qtygr,
                                    podate,
                                    CASE
                                        WHEN SUM(grqty) = 0 THEN 'waiting supply'
                                        WHEN SUM(poqty) = SUM(grqty) THEN 'complete'
                                        WHEN SUM(grqty) > SUM(poqty) THEN 'over gr'
                                        WHEN SUM(grqty) < SUM(poqty) THEN 'partial'
                                    END AS status
                                FROM data_goodreceive
                                GROUP BY ponumb;");            
      while($row=mysqli_fetch_object($query))
      {
         $data[] =$row;
      }
      $response=array(
                     'data' => $data
                  );
      header('Content-Type: application/json');
      echo json_encode($response);
    mysqli_close($koneksi3); 
}

$functionName = $_GET['function'] ?? null;
$data = [];

$allowedFunctions = [
    'stockImport',
    'poTrading'
];

if ($functionName && in_array($functionName, $allowedFunctions)) {
    $data = $functionName();
} else {
    http_response_code(400); 
    $errorMessage = 'Parameter "function" wajib diisi dan harus valid. ';
    $errorMessage .= 'Pilihan yang tersedia: ' . implode(', ', $allowedFunctions);
    echo json_encode(['error' => $errorMessage]);
    exit;
}

http_response_code(200);
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

?>