<?php
// login_helper.php
// Helper untuk mengamankan proses login dari serangan brute force dengan Rate Limiting dan CAPTCHA Bersyarat

define('LOG_FILE', __DIR__ . '/login_attempts.json');
define('LOCKOUT_TIME', 900); // 15 menit (dalam detik)
define('MAX_ATTEMPTS', 5);    // Maksimal percobaan login sebelum lockout
define('CAPTCHA_THRESHOLD', 3); // Gagal login sebanyak ini akan memicu munculnya CAPTCHA

/**
 * Memulai session dengan konfigurasi keamanan cookie jika belum dimulai
 */
function safe_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        if (!headers_sent()) {
            // Cek apakah koneksi menggunakan HTTPS
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
        session_start();
    }
}

/**
 * Mendapatkan IP Address asli pengguna dengan aman
 */
function get_ip_address() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ip_list[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Memuat data percobaan login dari file JSON dengan locking
 */
function load_attempts() {
    if (!file_exists(LOG_FILE)) {
        return [];
    }
    $content = @file_get_contents(LOG_FILE);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Mencatat percobaan login yang gagal ke file JSON
 */
function record_failed_attempt($ip, $username) {
    $attempts = [];
    
    // Gunakan file lock untuk menghindari race condition
    $fp = fopen(LOG_FILE, 'c+');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $attempts = json_decode($content, true);
            if (!is_array($attempts)) {
                $attempts = [];
            }
            
            // Tambahkan data gagal baru
            $attempts[] = [
                'ip' => $ip,
                'username' => strtolower(trim($username)),
                'timestamp' => time()
            ];
            
            // Bersihkan data lama (> 15 menit)
            $now = time();
            $cleaned = [];
            foreach ($attempts as $attempt) {
                if ($now - $attempt['timestamp'] < LOCKOUT_TIME) {
                    $cleaned[] = $attempt;
                }
            }
            
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($cleaned, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

/**
 * Mereset data percobaan login setelah login berhasil
 */
function reset_failed_attempts($ip, $username) {
    $fp = fopen(LOG_FILE, 'c+');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            $content = stream_get_contents($fp);
            $attempts = json_decode($content, true);
            if (!is_array($attempts)) {
                $attempts = [];
            }
            
            $now = time();
            $cleaned = [];
            $lower_username = strtolower(trim($username));
            
            foreach ($attempts as $attempt) {
                // Hapus data hanya jika IP dan Username yang dicari TIDAK cocok (simpan yang lainnya)
                if ($now - $attempt['timestamp'] < LOCKOUT_TIME) {
                    if ($attempt['ip'] !== $ip && $attempt['username'] !== $lower_username) {
                        $cleaned[] = $attempt;
                    }
                }
            }
            
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($cleaned, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

/**
 * Mendapatkan jumlah total kegagalan login untuk IP atau username tertentu
 */
function get_failed_count($ip, $username = '') {
    $attempts = load_attempts();
    $now = time();
    $count = 0;
    $lower_username = strtolower(trim($username));
    
    foreach ($attempts as $attempt) {
        if ($now - $attempt['timestamp'] < LOCKOUT_TIME) {
            if ($attempt['ip'] === $ip || ($lower_username !== '' && $attempt['username'] === $lower_username)) {
                $count++;
            }
        }
    }
    return $count;
}

/**
 * Memeriksa status lockout (blokir) untuk IP atau username
 * Mengembalikan jumlah menit tersisa jika diblokir, atau 0 jika tidak diblokir
 */
function check_lockout($ip, $username = '') {
    $attempts = load_attempts();
    $now = time();
    $ip_attempts = 0;
    $user_attempts = 0;
    $oldest_timestamp = $now;
    
    $lower_username = strtolower(trim($username));
    
    foreach ($attempts as $attempt) {
        if ($now - $attempt['timestamp'] < LOCKOUT_TIME) {
            if ($attempt['ip'] === $ip) {
                $ip_attempts++;
                if ($attempt['timestamp'] < $oldest_timestamp) {
                    $oldest_timestamp = $attempt['timestamp'];
                }
            }
            if ($lower_username !== '' && $attempt['username'] === $lower_username) {
                $user_attempts++;
                if ($attempt['timestamp'] < $oldest_timestamp) {
                    $oldest_timestamp = $attempt['timestamp'];
                }
            }
        }
    }
    
    // Jika jumlah kegagalan mencapai batas maksimal, kembalikan sisa waktu blokir
    if ($ip_attempts >= MAX_ATTEMPTS || ($lower_username !== '' && $user_attempts >= MAX_ATTEMPTS)) {
        $remaining = LOCKOUT_TIME - ($now - $oldest_timestamp);
        return $remaining > 0 ? ceil($remaining / 60) : 0;
    }
    
    return 0;
}

/**
 * Membuat persamaan matematika acak untuk CAPTCHA dan menyimpannya di session
 */
function generate_captcha() {
    safe_session_start();
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $operators = ['+', '-'];
    $operator = $operators[array_rand($operators)];
    
    if ($operator === '+') {
        $result = $num1 + $num2;
    } else {
        // Pastikan tidak menghasilkan nilai negatif agar tidak membingungkan
        if ($num1 < $num2) {
            $temp = $num1;
            $num1 = $num2;
            $num2 = $temp;
        }
        $result = $num1 - $num2;
    }
    
    $_SESSION['captcha_result'] = $result;
    return "$num1 $operator $num2";
}

/**
 * Memverifikasi jawaban CAPTCHA pengguna
 */
function verify_captcha($input) {
    safe_session_start();
    if (!isset($_SESSION['captcha_result'])) {
        return false;
    }
    $correct = ((int)$input === (int)$_SESSION['captcha_result']);
    unset($_SESSION['captcha_result']); // Hapus dari session setelah divalidasi agar tidak bisa digunakan ulang
    return $correct;
}
