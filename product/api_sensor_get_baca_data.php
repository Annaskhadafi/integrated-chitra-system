<?php

require_once "koneksi.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Ambil optional filter device
$device_id = $_GET['device_id'] ?? null;

if ($device_id) {
    $stmt = $koneksi->prepare("
        SELECT *
        FROM sensor_data
        WHERE device_id = ?
        ORDER BY timestamp DESC
        LIMIT 50
    ");
    $stmt->bind_param("s", $device_id);
} else {
    $stmt = $koneksi->prepare("
        SELECT *
        FROM sensor_data
        ORDER BY timestamp DESC
        LIMIT 50
    ");
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $data
]);

$stmt->close();
$koneksi->close();