<?php

require __DIR__ . "/../koneksi.php";

// Header Setup
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Cek Method
$method = $_SERVER['REQUEST_METHOD'];

// Handle Preflight Request
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 1. Ambil parameter 'function' dari URL
$functionName = isset($_GET['function']) ? $_GET['function'] : '';

if (empty($functionName)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Parameter 'function' missing in URL"]);
    exit;
}

// Variabel untuk menampung data input (khusus POST) dan hasil
$data = [];
$result = [];

// 2. Logic Routing
switch ($functionName) {

    // ===========================
    // SECTION POST (INSERT DATA)
    // ===========================
    case 'insert_goodreceive':
        if ($method !== 'POST') {
            sendMethodNotAllowed("POST");
        }
        $data = getJsonInput();
        
        if (is_array($data) && isset($data[0])) {
            $items = $data; 
        } else {
            $items = isset($data['items']) ? $data['items'] : (isset($data['po']) ? $data['po'] : []);
        }

        $result = processGoodReceive($koneksi, $items);
        break;

    case 'insert_inventory':
        if ($method !== 'POST') {
            sendMethodNotAllowed("POST");
        }
        $data = getJsonInput();
        $items = isset($data['items']) ? $data['items'] : [];
        $result = processInventory($koneksi, $items);
        break;


    case 'insert_material':
        if ($method !== 'POST') {
            sendMethodNotAllowed("POST");
        }
        $data = getJsonInput();
        // Mendukung input satu data (object) atau banyak data (array of objects)
        $items = isset($data['items']) ? $data['items'] : (isset($data[0]) ? $data : [$data]);
        $result = processInsertMaterial($koneksi, $items);
        break;
    // ===========================
    // SECTION GET (READ DATA)
    // ===========================
    case 'get_goodreceive':
        if ($method !== 'GET') {
            sendMethodNotAllowed("GET");
        }
        
        // Ambil parameter start_date dan end_date
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date   = isset($_GET['end_date'])   ? $_GET['end_date']   : '';

        // Validasi: Wajib diisi
        if (empty($start_date) || empty($end_date)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Parameters 'start_date' and 'end_date' are required (Format: YYYY-MM-DD)"]);
            exit;
        }

        $result = getDataGoodReceive($koneksi, $start_date, $end_date);
        break;

    case 'get_inventory':
        if ($method !== 'GET') {
            sendMethodNotAllowed("GET");
        }
        // Opsional: Filter berdasarkan plant
        // $plant = isset($_GET['plant']) ? $_GET['plant'] : null;
        $result = getDataInventory($koneksi);
        break;

    default:
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Function parameter invalid"]);
        exit;
}

// Output Final
echo json_encode([
    'status' => 'OK',
    'function_executed' => $functionName,
    'method' => $method,
    'result' => $result
]);


// ==========================================
// HELPER FUNCTIONS
// ==========================================

function getJsonInput() {
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON data body"]);
        exit;
    }
    return $data;
}

function sendMethodNotAllowed($expected) {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed. Use $expected."]);
    exit;
}

// ==========================================
// DB READ FUNCTIONS (GET)
// ==========================================

function getDataInventory($koneksi) {
    $sql = "SELECT idinv, plant, plantname, material, oldmaterial, `desc`, sloc, slocdesc, qtystock, valuestock 
            FROM data_inventory ORDER BY material ASC";
    
    $query = $koneksi->query($sql);
    
    if (!$query) {
        return ["status" => "ERROR", "reason" => $koneksi->error];
    }

    $data = [];
    while ($row = $query->fetch_assoc()) {
        $row['qtystock'] = (int)$row['qtystock'];
        $row['valuestock'] = (float)$row['valuestock'];
        $data[] = $row;
    }
    return $data;
}

function getDataGoodReceive($koneksi, $start_date, $end_date) {
    // Escape string untuk keamanan query
    $start = $koneksi->real_escape_string($start_date);
    $end   = $koneksi->real_escape_string($end_date);

    // Query dengan range tanggal
    $sql = "SELECT ponumb, vendor, prnumb, podate, materialnumb, material, poqty, togr, toinvo, grqty 
            FROM data_goodreceive 
            WHERE podate >= '$start' AND podate <= '$end'
            ORDER BY podate DESC";

    $query = $koneksi->query($sql);

    if (!$query) {
        return ["status" => "ERROR", "reason" => $koneksi->error];
    }

    $data = [];
    while ($row = $query->fetch_assoc()) {
        $row['poqty']  = (int)$row['poqty'];
        $row['togr']   = (int)$row['togr'];
        $row['toinvo'] = (int)$row['toinvo'];
        $row['grqty']  = (int)$row['grqty'];
        $data[] = $row;
    }
    return $data;
}

// ==========================================
// DB WRITE FUNCTIONS (POST)
// ==========================================

function processGoodReceive($koneksi, $items) {
    if (empty($items) || !is_array($items)) {
        return ["status" => "FAILED", "reason" => "No items data provided"];
    }

    $stmt = $koneksi->prepare("
        INSERT INTO data_goodreceive 
        (ponumb, vendor, prnumb, podate, materialnumb, material, poqty, togr, toinvo, grqty)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        return ["status" => "ERROR", "reason" => "Prepare failed: " . $koneksi->error];
    }

    $response = [];

    foreach ($items as $index => $item) {
        // 1. MAPPING DATA (JSON Image -> Variabel)
        // Menggunakan key sesuai gambar yang Anda kirim
        
        $json_ponumb   = isset($item['purchasing_doc'])      ? $item['purchasing_doc']      : null;
        $json_vendor   = isset($item['vendor_name']) ? $item['vendor_name'] : null;
        $json_podate   = isset($item['doc_date'])      ? $item['doc_date']      : null;
        $json_prnumb   = isset($item['tracking_no'])     ? $item['tracking_no']     : null;
        $json_matnumb  = isset($item['material'])       ? $item['material']       : null;
        $json_shorttxt = isset($item['short_text'])     ? $item['short_text']     : null;
        $json_qty      = isset($item['order_qty'])       ? $item['order_qty']       : null;
        $json_todel    = isset($item['delivered_qty'])     ? $item['delivered_qty']     : null;
        $json_toinv    = isset($item['invoiced_qty'])   ? $item['invoiced_qty']   : null; 

        // 2. VALIDASI DATA WAJIB
        if (
            empty($json_ponumb) || 
            empty($json_vendor) || 
            empty($json_podate) || 
            $json_qty === null  || // Cek null karena qty bisa 0
            $json_todel === null
        ) {
            $response[] = ['index' => $index, 'status' => 'FAILED', 'reason' => 'Incomplete data (Check keys: purchasing_doc, vendor_name, etc)'];
            continue;
        }

        // 3. ASSIGN KE VARIABEL DB & CASTING TIPE DATA
        $ponumb = $json_ponumb;
        $vendor = $json_vendor;
        $podate = $json_podate;
        
        $poqty  = (int)$json_qty;
        $togr   = (int)$json_todel;
        $toinvo = (int)$json_toinv; // Bisa null jika tidak ada di JSON, akan jadi 0
        
        // Hitung GR Qty
        $grqty  = $poqty - $togr; 

        // Field Opsional
        $prnumb       = !empty($json_prnumb) ? (int)$json_prnumb : null;
        $materialnumb = !empty($json_matnumb) ? $json_matnumb : null;
        $material     = !empty($json_shorttxt) ? $json_shorttxt : null;

        // 4. EKSEKUSI INSERT
        // Tipe data bind: s=string, i=integer
        // ponumb(s), vendor(s), prnumb(i), podate(s), materialnumb(s), material(s), poqty(i), togr(i), toinvo(i), grqty(i)
        $stmt->bind_param("ssisssiiii", $ponumb, $vendor, $prnumb, $podate, $materialnumb, $material, $poqty, $togr, $toinvo, $grqty);

        if ($stmt->execute()) {
            $response[] = ['ponumb' => $ponumb, 'status' => 'INSERTED', 'grqty' => $grqty];
        } else {
            $response[] = ['ponumb' => $ponumb, 'status' => 'FAILED', 'reason' => $stmt->error];
        }
    }
    $stmt->close();
    return $response;
}

function processInsertMaterial($koneksi, $items) {
    if (empty($items) || !is_array($items)) {
        return ["status" => "FAILED", "reason" => "No material data provided"];
    }

    // 1. Prepare Statement untuk Check Duplikasi
    $stmtCheck = $koneksi->prepare("SELECT id FROM material_price WHERE material_name = ? AND price_date = ?");
    
    // 2. Prepare Statement untuk Insert Data
    // FIX 1: Pastikan ada 6 kolom dan 6 tanda tanya (?)
    $stmtInsert = $koneksi->prepare("
        INSERT INTO material_price 
        (material_name, material_type, material_price, material_unit, material_currency, price_date)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmtCheck || !$stmtInsert) {
        return ["status" => "ERROR", "reason" => "Prepare failed: " . $koneksi->error];
    }

    $response = [];

    foreach ($items as $index => $item) {
        $name     = isset($item['material_name'])     ? $item['material_name']     : null;
        $type     = isset($item['material_type'])     ? $item['material_type']     : null; // Beri default jika kosong
        $price    = isset($item['material_price'])    ? $item['material_price']    : null;
        $unit     = isset($item['material_unit'])     ? $item['material_unit']     : null;
        $currency = isset($item['material_currency']) ? $item['material_currency'] : null;
        $p_date   = isset($item['price_date'])        ? $item['price_date']        : null;

        if (empty($name) || $price === null || empty($unit) || empty($p_date)) {
            $response[] = [
                'index' => $index, 
                'status' => 'FAILED', 
                'reason' => 'Data tidak lengkap'
            ];
            continue;
        }

        // --- PROSES CEK DUPLIKASI ---
        $stmtCheck->bind_param("ss", $name, $p_date);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $response[] = [
                'material_name' => $name,
                'price_date' => $p_date,
                'status' => 'SKIPPED',
                'reason' => 'Data untuk tanggal ini sudah ada'
            ];
        } else {
            // --- PROSES INSERT ---
            // FIX 2: Bind param harus sesuai jumlah tanda tanya (6)
            // Urutan: name(s), type(s), price(d/s), unit(s), currency(s), p_date(s)
            // Gunakan "ssdsss" jika price adalah angka desimal, atau "ssssss" jika string
            $stmtInsert->bind_param("ssdsss", $name, $type, $price, $unit, $currency, $p_date);

            if ($stmtInsert->execute()) {
                $response[] = [
                    'material_name' => $name, 
                    'status' => 'INSERTED', 
                    'id' => $stmtInsert->insert_id,
                    'price_date' => $p_date
                ];
            } else {
                $response[] = ['material_name' => $name, 'status' => 'FAILED', 'reason' => $stmtInsert->error];
            }
        }
    }

    $stmtCheck->close();
    $stmtInsert->close();
    return $response;
}

function processInventory($koneksi, $items) {
    if (empty($items) || !is_array($items)) {
        return ["status" => "FAILED", "reason" => "No items data provided"];
    }

    $stmtCheck = $koneksi->prepare("SELECT idinv FROM data_inventory WHERE idinv = ?");

    $stmtInsert = $koneksi->prepare("
        INSERT INTO data_inventory 
        (idinv, plant, plantname, material, oldmaterial, `desc`, sloc, slocdesc, qtystock, valuestock)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmtUpdate = $koneksi->prepare("
        UPDATE data_inventory 
        SET qtystock = ?, valuestock = ? 
        WHERE idinv = ?
    ");

    if (!$stmtCheck || !$stmtInsert || !$stmtUpdate) {
        return ["status" => "ERROR", "reason" => "Prepare failed: " . $koneksi->error];
    }

    $response = [];

    foreach ($items as $index => $item) {
        if (
            empty($item['plant']) || empty($item['plantname']) ||
            empty($item['material']) || empty($item['oldmaterial']) ||
            empty($item['desc']) || empty($item['sloc']) ||
            empty($item['slocdesc']) ||
            !isset($item['qtystock']) || !isset($item['valuestock'])
        ) {
            $response[] = ['index' => $index, 'status' => 'FAILED', 'reason' => 'Incomplete data'];
            continue;
        }

        $idinv = $item['material'] . "-" . $item['sloc']; 
        
        $plant       = $item['plant'];
        $plantname   = $item['plantname'];
        $material    = $item['material'];
        $oldmaterial = $item['oldmaterial'];
        $desc        = $item['desc'];
        $sloc        = $item['sloc'];
        $slocdesc    = $item['slocdesc'];
        $qtystock    = (int)$item['qtystock'];
        $valuestock  = (float)$item['valuestock'];

        $stmtCheck->bind_param("s", $idinv);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $stmtUpdate->bind_param("iis", $qtystock, $valuestock, $idinv);
            if ($stmtUpdate->execute()) {
                $response[] = ['idinv' => $idinv, 'status' => 'UPDATED', 'stock' => $qtystock];
            } else {
                $response[] = ['idinv' => $idinv, 'status' => 'FAILED_UPDATE', 'reason' => $stmtUpdate->error];
            }
        } else {
            $stmtInsert->bind_param(
                "ssssssssii",
                $idinv, $plant, $plantname, $material, $oldmaterial, 
                $desc, $sloc, $slocdesc, $qtystock, $valuestock
            );
            if ($stmtInsert->execute()) {
                $response[] = ['idinv' => $idinv, 'status' => 'INSERTED'];
            } else {
                $response[] = ['idinv' => $idinv, 'status' => 'FAILED_INSERT', 'reason' => $stmtInsert->error];
            }
        }
    }

    $stmtCheck->close();
    $stmtInsert->close();
    $stmtUpdate->close();

    return $response;
}
?>