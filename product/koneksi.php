<?php
/**
 * Database Connection Hub - Integrated Chitra System (ICS)
 * Konfigurasi sepenuhnya menggunakan Environment Variables (ENV) untuk Dokploy / Docker.
 */

// 1. Muat file .env jika tersedia (untuk local development)
$envFiles = [
    dirname(__DIR__) . '/.env',
    __DIR__ . '/.env'
];

foreach ($envFiles as $envFile) {
    if (file_exists($envFile) && is_readable($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (str_contains($line, '=')) {
                list($envKey, $envVal) = explode('=', $line, 2);
                $envKey = trim($envKey);
                $envVal = trim($envVal);
                $envVal = trim($envVal, '"\'');

                // Hanya set jika belum ada di environment sistem (Dokploy / Docker environment lebih prioritas)
                if (getenv($envKey) === false && !isset($_ENV[$envKey])) {
                    putenv("{$envKey}={$envVal}");
                    $_ENV[$envKey] = $envVal;
                    $_SERVER[$envKey] = $envVal;
                }
            }
        }
        break;
    }
}

// 2. Helper untuk mengambil nilai ENV dari getenv(), $_ENV, atau $_SERVER
if (!function_exists('ics_get_env')) {
    function ics_get_env($key, $default = '') {
        $val = getenv($key);
        if ($val !== false && $val !== '') {
            return $val;
        }
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }
        return $default;
    }
}

// 3. Konfigurasi Database dari Environment Variables Dokploy / Docker
$db_host = ics_get_env('DB_HOST', 'db');
$db_port = (int)ics_get_env('DB_PORT', 3306);
$db_user = ics_get_env('DB_USER', 'root');
$db_pass = ics_get_env('DB_PASS', '');

$db_name  = ics_get_env('DB_NAME', 'chitraparatama_ics');
$db_name2 = ics_get_env('DB_NAME2', 'chitraparatama_fleetlist');
$db_name3 = ics_get_env('DB_NAME3', 'chitraparatama_repair_job_card');
$db_name5 = ics_get_env('DB_NAME5', 'chitraparatama_warranty');
$db_name6 = ics_get_env('DB_NAME6', 'chitraparatama_vhs_stock');
$db_name8 = ics_get_env('DB_NAME8', 'chitraparatama_competitor');

// Error reporting untuk mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi    = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
    $koneksiall = mysqli_connect($db_host, $db_user, $db_pass, "", $db_port);
    $koneksi2   = mysqli_connect($db_host, $db_user, $db_pass, $db_name2, $db_port);
    $koneksi3   = mysqli_connect($db_host, $db_user, $db_pass, $db_name3, $db_port);
    $koneksi5   = mysqli_connect($db_host, $db_user, $db_pass, $db_name5, $db_port);
    $koneksi6   = mysqli_connect($db_host, $db_user, $db_pass, $db_name6, $db_port);
    $koneksi8   = mysqli_connect($db_host, $db_user, $db_pass, $db_name8, $db_port);

    // Variabel alias $sambung untuk kompatibilitas upload_aksi.php
    $sambung = $koneksi;
} catch (mysqli_sql_exception $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Gagal koneksi ke database: " . htmlspecialchars($e->getMessage()));
}
