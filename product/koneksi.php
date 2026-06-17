<?php
// Menggunakan getenv() untuk mengambil konfigurasi dari environment variables Dokploy
// Jika tidak ada, maka akan menggunakan default (untuk fallback)
$db_host = getenv('DB_HOST') ?: 'integrated-chitra-system-database-x77gvi';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: 'userdatabaseics27693';



$db_name  = getenv('DB_NAME') ?: "chitraparatama_ics";
$db_name2 = getenv('DB_NAME2') ?: "chitraparatama_fleetlist";
$db_name3 = getenv('DB_NAME3') ?: "chitraparatama_repair_job_card";
$db_name5 = getenv('DB_NAME5') ?: "chitraparatama_warranty";
$db_name6 = getenv('DB_NAME6') ?: "chitraparatama_vhs_stock";
$db_name8 = getenv('DB_NAME8') ?: "chitraparatama_competitor";

// Menambahkan error reporting yang lebih baik untuk debugging di Docker
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    $koneksiall = mysqli_connect($db_host, $db_user, $db_pass);
    $koneksi2 = mysqli_connect($db_host, $db_user, $db_pass, $db_name2);
    $koneksi3 = mysqli_connect($db_host, $db_user, $db_pass, $db_name3);
    $koneksi5 = mysqli_connect($db_host, $db_user, $db_pass, $db_name5);
    $koneksi6 = mysqli_connect($db_host, $db_user, $db_pass, $db_name6);
    $koneksi8 = mysqli_connect($db_host, $db_user, $db_pass, $db_name8);

    // Variabel $sambung yang sebelumnya error karena tidak terdefinisi di upload_aksi.php
    $sambung = $koneksi;
} catch (mysqli_sql_exception $e) {
    // Tampilkan pesan error asli dari MySQL
    error_log("Database Connection Error: " . $e->getMessage());
    die("Gagal koneksi ke database: " . htmlspecialchars($e->getMessage()));
}
