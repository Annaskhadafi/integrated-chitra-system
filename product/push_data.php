<?php
// 1. Pindahkan include koneksi ke paling atas agar bisa diakses oleh semua fungsi menggunakan keyword global
include "koneksi.php";

if(function_exists($_GET['function'])) {
     $_GET['function']();
}

function new_tire_repair(){
    // Gunakan keyword global agar bisa membaca variabel koneksi di atas
    global $koneksi3; 
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
        $api_key = "";
        $api_key_value = ""; // Pastikan ini terdefinisi atau disamakan nilainya jika belum menggunakan validasi ketat
        
        if($api_key == $api_key_value){
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            if (!$data) {
                echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
                return;
            }
            
            $id = $data['id'] ?? ''; 
            $customer = $data['customer'] ?? ''; 
            $site = $data['site'] ?? ''; 
            $size = $data['tire_size'] ?? ''; 
            $sn = $data['sn'] ?? ''; 
            $brand = $data['brand'] ?? ''; 
            $type = $data['type_construction'] ?? ''; 
            $pattern = $data['pattern'] ?? ''; 
            $received = $data['date_received'] ?? ''; 
            $receiver = $data['receiver'] ?? ''; 
            $status = 'w/ inspect'; 
                
            $query = mysqli_query($koneksi3,"INSERT INTO work_order(id_wo,status,customer,site,size,tire_sn,brand,pattern,type,received_date,receiver) 
                    VALUES ('$id','$status','$customer','$site','$size','$sn','$brand','$pattern','$type','$received','$receiver')");
            
            // Tambahkan respon sukses agar API tidak blank
            echo json_encode(["status" => "success", "message" => "Data work order berhasil disimpan"]);
        }
        else {
          echo "Wrong API Key provided.";
        }
    }
    else {
        echo "No data posted with HTTP POST.";
    }
}

function inspect(){
    global $koneksi3;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
        $api_key = "";
        $api_key_value = "";
        if($api_key == $api_key_value){
            header('Content-Type: application/json');
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            if (!$data) {
                echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
                return;
            }
            
            $idwo       = $data['id'] ?? ($data['id_wo'] ?? '');
            if (empty($idwo)) {
                echo json_encode(["status" => "error", "message" => "Missing ID"]);
                return;
            }

            $date       = $data['date_inspect'] ?? ($data['inspect_date'] ?? ''); 
            $jobtype    = $data['status'] ?? ($data['job_type'] ?? ''); 
            $injury     = $data['repair_duration'] ?? ($data['injury'] ?? '');
            $storeloc   = $data['repair_location'] ?? ($data['store_loc'] ?? '');
            $remark     = $data['remarks'] ?? ($data['remark'] ?? '');
            $inspector  = $data['report_by'] ?? ($data['inspector'] ?? '');
            $customer   = $data['customer'] ?? '';
            $site       = $data['site'] ?? '';
            $size       = $data['tire_size'] ?? ($data['size'] ?? '');
            $sn         = $data['sn'] ?? ($data['tire_sn'] ?? ($data['sn_tire'] ?? ''));
            $brand      = $data['brand'] ?? '';
            $pattern    = $data['pattern'] ?? '';
            $type       = $data['type_construction'] ?? ($data['type'] ?? '');
            $received   = $data['date_received'] ?? ($data['received_date'] ?? '');
            $nocargo    = $data['no_cargo_manifest'] ?? ($data['nocargo'] ?? '');
            $receiver   = $data['receiver'] ?? ($data['email'] ?? '');

            if (strtoupper(trim($jobtype)) === "REJECT") {
                $status = "Reject";
            } else {
                $status = "w/ work_order";
            }

            // Ambil daftar kolom yang tersedia di tabel work_order untuk mencegah error Unknown Column
            $existing_cols = [];
            $cols_res = mysqli_query($koneksi3, "SHOW COLUMNS FROM work_order");
            if ($cols_res) {
                while ($col = mysqli_fetch_assoc($cols_res)) {
                    $existing_cols[] = $col['Field'];
                }
            }
            if (empty($existing_cols)) {
                $existing_cols = [
                    'id_wo', 'status', 'customer', 'site', 'size', 'tire_sn', 'brand',
                    'pattern', 'type', 'received_date', 'receiver', 'inspect_date',
                    'job_type', 'injury', 'store_loc', 'inspector', 'remark', 'nocargo'
                ];
            }

            $fields = [
                'tire_sn'       => $sn,
                'size'          => $size,
                'customer'      => $customer,
                'site'          => $site,
                'brand'         => $brand,
                'pattern'       => $pattern,
                'type'          => $type,
                'received_date' => $received,
                'inspect_date'  => $date,
                'job_type'      => $jobtype,
                'injury'        => $injury,
                'store_loc'     => $storeloc,
                'inspector'     => $inspector,
                'remark'        => $remark,
                'nocargo'       => $nocargo,
                'receiver'      => $receiver,
            ];

            if (isset($data['email'])) {
                $fields['email'] = $data['email'];
            }
            if (isset($data['rtd1'])) {
                $fields['rtd1'] = $data['rtd1'];
            }
            if (isset($data['rtd2'])) {
                $fields['rtd2'] = $data['rtd2'];
            }

            // Filter hanya kolom yang benar-benar ada di tabel database
            $valid_fields = [];
            foreach ($fields as $col => $val) {
                if (in_array($col, $existing_cols)) {
                    $valid_fields[$col] = $val;
                }
            }

            $idwo_escaped = mysqli_real_escape_string($koneksi3, $idwo);
            $check_query = mysqli_query($koneksi3, "SELECT id_wo, status FROM work_order WHERE id_wo = '$idwo_escaped' LIMIT 1");
            $exists = ($check_query && mysqli_num_rows($check_query) > 0);

            if ($exists) {
                $row = mysqli_fetch_assoc($check_query);
                // Pertahankan status progress/complete jika status bukan REJECT
                if (strtoupper(trim($jobtype)) !== "REJECT" && in_array(strtolower($row['status'] ?? ''), ['progress', 'complete'])) {
                    $final_status = $row['status'];
                } else {
                    $final_status = $status;
                }

                if (in_array('status', $existing_cols)) {
                    $valid_fields['status'] = $final_status;
                }

                $set_clauses = [];
                foreach ($valid_fields as $col => $val) {
                    $val_escaped = mysqli_real_escape_string($koneksi3, (string)$val);
                    $set_clauses[] = "`$col` = '$val_escaped'";
                }

                $set_sql = implode(', ', $set_clauses);
                $query = mysqli_query($koneksi3, "UPDATE work_order SET $set_sql WHERE id_wo = '$idwo_escaped'");

                if ($query) {
                    echo json_encode(["status" => "success", "message" => "Data inspect berhasil diperbarui"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Gagal memperbarui data inspect: " . mysqli_error($koneksi3)]);
                }
            } else {
                // Jika data belum ada (post data pertama kali), lakukan INSERT
                if (in_array('status', $existing_cols)) {
                    $valid_fields['status'] = $status;
                }
                $valid_fields['id_wo'] = $idwo;

                $col_names = [];
                $col_values = [];
                foreach ($valid_fields as $col => $val) {
                    $col_names[] = "`$col`";
                    $col_values[] = "'" . mysqli_real_escape_string($koneksi3, (string)$val) . "'";
                }

                $sql = "INSERT INTO work_order (" . implode(', ', $col_names) . ") VALUES (" . implode(', ', $col_values) . ")";
                $query = mysqli_query($koneksi3, $sql);

                if ($query) {
                    echo json_encode(["status" => "success", "message" => "Data inspect berhasil disimpan"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Gagal menyimpan data inspect: " . mysqli_error($koneksi3)]);
                }
            }
        }
        else {
          header('Content-Type: application/json');
          echo json_encode(["status" => "error", "message" => "Wrong API Key provided."]);
        }
    }
    else {
        echo "No data posted with HTTP POST.";
    }
}

function new_job() {
    global $koneksi3;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $api_key_value = ""; 
        $api_key = $_SERVER['HTTP_API_KEY'] ?? '';

        if ($api_key == $api_key_value) {
            $json_data = file_get_contents("php://input");
            $job = json_decode($json_data, true);

            if (!$job) {
                echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
                return;
            }

            $bywhom = $job['bywhom'] ?? '';
            $date = $job['date'] ?? '';
            $fulldate = $job['fulldate'] ?? '';
            $hours = $job['hours'] ?? '0';
            $minutes = $job['minutes'] ?? '0';
            $total_minutes = ((int)$hours * 60) + (int)$minutes;
            $name = $job['name'] ?? '';
            $remarks = $job['remarks'] ?? '';
            $id_wo = $job['id_wo'] ?? '';
            $wo = $job['wo'] ?? '';
            $dimensi = $job['dimensi'] ?? '';
            $repair_count = $job['process_repair_count'] ?? '';
                
            $status_sql = ($name == 'Painting') ? ", status='Complete'" : "";
            mysqli_query($koneksi3, "UPDATE work_order SET remark='$remarks' $status_sql WHERE id_wo = '$id_wo'");

            if (isset($job['material']) && is_array($job['material'])) {
                foreach ($job['material'] as $mat) {
                    $id_matstock = isset($mat['id_matstock']) && $mat['id_matstock'] !== '' ? $mat['id_matstock'] : 'NULL';
                    $qty = isset($mat['qty']) && $mat['qty'] !== '' ? $mat['qty'] : 'NULL';
                    mysqli_query($koneksi3, "INSERT INTO job(wo, job, material, qty, date, time, person, note, proseske) 
                        VALUES ('$id_wo', '$name', $id_matstock, $qty, '$fulldate', '$total_minutes', '$bywhom', '$dimensi', '$repair_count')");
                }
            }
            
            echo json_encode(["status" => "success", "message" => "Job berhasil ditambahkan"]);
        } else {
            echo "Wrong API Key provided.";
        }
    } else {
        echo "No data posted with HTTP POST.";
    }
}
    
function update_job() {
    global $koneksi3;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $api_key_value = ""; 
        $api_key = $_SERVER['HTTP_API_KEY'] ?? '';

        if ($api_key == $api_key_value) {
            $json_data = file_get_contents("php://input");
            $job = json_decode($json_data, true);

            if (!$job) {
                echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
                return;
            }

            $id_wo = $job['id_wo'] ?? '';
            $name = $job['name'] ?? ''; 

            if (empty($id_wo) || empty($name)) {
                echo json_encode(["status" => "error", "message" => "Missing id_wo or job name"]);
                return;
            }

            $bywhom = $job['bywhom'] ?? '';
            $fulldate = $job['fulldate'] ?? '';
            $hours = $job['hours'] ?? '0';
            $minutes = $job['minutes'] ?? '0';
            $total_minutes = ((int)$hours * 60) + (int)$minutes;
            $remarks = $job['remarks'] ?? '';
            $dimensi = $job['dimensi'] ?? '';
            $repair_count = $job['process_repair_count'] ?? '';

            $status_sql = ($name == 'Painting') ? ", status='Complete'" : "";
            mysqli_query($koneksi3, "UPDATE work_order SET remark='$remarks' $status_sql WHERE id_wo = '$id_wo'");

            mysqli_query($koneksi3, "DELETE FROM job WHERE wo = '$id_wo' AND job = '$name'");

            if (isset($job['material']) && is_array($job['material'])) {
                foreach ($job['material'] as $mat) {
                    $id_matstock = (isset($mat['id_matstock']) && $mat['id_matstock'] !== '') ? $mat['id_matstock'] : 'NULL';
                    $qty = (isset($mat['qty']) && $mat['qty'] !== '') ? $mat['qty'] : 'NULL';

                    $query = mysqli_query($koneksi3, "INSERT INTO job(wo, job, material, qty, date, time, person, note, proseske) 
                                                      VALUES ('$id_wo', '$name', $id_matstock, $qty, '$fulldate', '$total_minutes', '$bywhom', '$dimensi', '$repair_count')");
                }
            }

            echo json_encode(["status" => "success", "message" => "Data updated (replaced) successfully"]);

        } else {
            echo "Wrong API Key.";
        }
    }
}

// 2. Sekarang aman ditutup di scope paling luar (global)
if (isset($koneksi3)) {
    mysqli_close($koneksi3);
}
?>