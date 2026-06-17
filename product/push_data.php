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
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            if (!$data) {
                echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
                return;
            }
            
            $idwo=$data['id'] ?? '';
            $date = $data['date_inspect'] ?? ''; 
            $jobtype = $data['status'] ?? ''; 
            $injury= $data['repair_duration'] ?? '';
            $storeloc=$data['repair_location'] ?? '';
            $remark = $data['remarks'] ?? '';
            $inspector=$data['report_by'] ?? '';
            
            if($jobtype=="REJECT"){
                $status="Reject";
            }
            else{
                $status="w/ work_order";
            }
            
            $query = mysqli_query($koneksi3,"UPDATE work_order SET inspect_date='$date',job_type='$jobtype',injury='$injury',store_loc='$storeloc',status='$status',inspector='$inspector',remark='$remark' WHERE id_wo like '$idwo';");
            
            echo json_encode(["status" => "success", "message" => "Data inspect berhasil diperbarui"]);
        }
        else {
          echo "Wrong API Key provided.";
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