<?php
$idunitsite= $_POST['idunitsite'];
$unitnumber= $_POST['unitnumber'];
$unit= $_POST['unit'];
$hm= $_POST['hm'];
include "koneksi.php";
$cekdulu= mysqli_query ($sambung, "SELECT * from unit_site a,unit b where a.unit=b.id_unit and a.id_unit_site='$idunitsite'"); 
$cek = mysqli_fetch_array($cekdulu);
$tire = $cek['tire'];
$life=$hm-$cek['hm'];
$no=1;
$noa=1;
//tambahkan lifetime baru pada tire
while($noa<=$tire){ 
                $perintah2=mysqli_query ($sambung, "SELECT * from tire_movement a, tire_inventory b where unit_number='$idunitsite' and posisi=$noa and a.sn=b.id_inventory order by id_movement desc limit 1");
                $data2=mysqli_fetch_array($perintah2);
                $lifetotal=$life+$data2['lifetime'];
                $idinventory=$data2['id_inventory'];
                $rim1 = mysqli_query($sambung, "SELECT * from assembly a,rim_inventory b where a.id_rim_inventory=b.id_rim_inventory and a.id_inventory=$idinventory order by id_assembly desc limit 1"); 
                $datarim1=mysqli_fetch_array($rim1);
                $idrim=$datarim1['id_rim_inventory'];
                $lifetotalrim=$life+$datarim1['rim_lifetime'];
                $total1 = mysqli_query($sambung, "UPDATE tire_inventory set lifetime = $lifetotal where id_inventory=$idinventory"); 
                $total2 = mysqli_query($sambung, "UPDATE rim_inventory set rim_lifetime = $lifetotalrim where id_rim_inventory=$idrim");        
                $noa++;
            }
//tambahkan HM unit
$perintah7 = mysqli_query($sambung, "UPDATE unit_site set unit_number='$unitnumber',unit= '$unit',hm='$hm' where id_unit_site=$idunitsite");
echo"<script> history.go(-1); </script>";
?>