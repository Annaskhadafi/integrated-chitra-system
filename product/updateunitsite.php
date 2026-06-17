<?php
$idunitsite = $_POST['idunitsite'] ?? '';
$unitnumber = $_POST['unitnumber'] ?? '';
$unit = $_POST['unit'] ?? '';
$hm = (float)($_POST['hm'] ?? 0);
include "koneksi.php";

$cekdulu = mysqli_query($sambung, "SELECT * FROM unit_site a, unit b WHERE a.unit=b.id_unit AND a.id_unit_site='$idunitsite'"); 
$cek = mysqli_fetch_array($cekdulu);
$tire = $cek['tire'] ?? 0;
$prev_hm = $cek['hm'] ?? 0;
$life = $hm - $prev_hm;
$noa=1;

//tambahkan lifetime baru pada tire
while($noa <= $tire){ 
    $perintah2 = mysqli_query($sambung, "SELECT * FROM tire_movement a, tire_inventory b WHERE unit_number='$idunitsite' AND posisi='$noa' AND a.sn=b.id_inventory ORDER BY id_movement DESC LIMIT 1");
    $data2 = mysqli_fetch_array($perintah2);
    if ($data2) {
        $prev_lifetime = $data2['lifetime'] ?? 0;
        $lifetotal = $life + $prev_lifetime;
        $idinventory = $data2['id_inventory'] ?? '';
        
        $rim1 = mysqli_query($sambung, "SELECT * FROM assembly a, rim_inventory b WHERE a.id_rim_inventory=b.id_rim_inventory AND a.id_inventory='$idinventory' ORDER BY id_assembly DESC LIMIT 1"); 
        $datarim1 = mysqli_fetch_array($rim1);
        if ($datarim1) {
            $idrim = $datarim1['id_rim_inventory'] ?? '';
            $prev_rim_lifetime = $datarim1['rim_lifetime'] ?? 0;
            $lifetotalrim = $life + $prev_rim_lifetime;
            mysqli_query($sambung, "UPDATE rim_inventory SET rim_lifetime = '$lifetotalrim' WHERE id_rim_inventory='$idrim'");
        }
        
        mysqli_query($sambung, "UPDATE tire_inventory SET lifetime = '$lifetotal' WHERE id_inventory='$idinventory'");
    }
    $noa++;
}
//tambahkan HM unit
mysqli_query($sambung, "UPDATE unit_site SET unit_number='$unitnumber', unit='$unit', hm='$hm' WHERE id_unit_site='$idunitsite'");
echo "<script> history.go(-1); </script>";
?>