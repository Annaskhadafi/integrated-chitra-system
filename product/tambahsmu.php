<?php
$value= $_POST['value']; //data long string
$arrayCode = array(); //buat arraycode
$error = array(); //array unit yang bermasalah
$pecah = explode("\n", $value); //pecah longstring perbaris
foreach($pecah as $idx => $row) //setiap baris melakukan
{
    $data = explode( "\t", $row ); //pecah tiap baris perkolom    
    foreach( $data as $field )
    {        
        $arrayCode[$idx][] = $field; //masukkan data ke arrayCode
    }     
}
for ($baris=0;$baris<(count($arrayCode)-1);$baris++)//setiap baris arrayCode melakukan :
{   
    $unit=$arrayCode[$baris][0];
    $smu=$arrayCode[$baris][1];
    include "koneksi.php";
    //pengecekan unit number dan SMU dibawah last update
    $cekdulu= mysqli_query ($sambung, "SELECT * from unit_site a,unit b where a.unit=b.id_unit and a.unit_number='$unit'");
    $data=mysqli_fetch_array($cekdulu);
    $actualsmu=$data['hm'];
    $tire = $data['tire'];
    $life=$smu-$actualsmu;
    $idunitsite=$data['id_unit_site'];

    if(mysqli_num_rows($cekdulu)==0){   
        $error[]=$unit." Wrong unit number or unit not registered yet";
    }
    elseif ($smu<$actualsmu) {
        $error[]=$unit." SMU too low than SMU last update";
    }
    else {
        $sum = 0;
        //setiap $loop mewakili 1 posisi, unit dengan 6 tire akan loop sebanyak 6 kali
        for ($loop=1; $loop < $tire+1; $loop++){
            $perintahcek=mysqli_query($sambung, "SELECT * from tire_movement where unit_number=$idunitsite and posisi=$loop order by id_movement desc limit 1");
            $cek=mysqli_fetch_array($perintahcek);
            $nilai=$cek['job'];
            $sum += $nilai;   // jika tire terpasang, tambahkan value 1 ke variabel $sum 
        }
        if ($sum!=$tire){
            $error[]=$unit." Tire not compleated";
        }
        else{
            $noa=1;
            //tambahkan lifetime baru pada tire
            while($noa<=$tire){ 
                $perintah2=mysqli_query ($sambung, "SELECT * from tire_movement a, tire_inventory b where unit_number='$idunitsite' and posisi=$noa and a.sn=b.id_inventory order by id_movement desc limit 1");
                $data2=mysqli_fetch_array($perintah2);
                $lifetotal=$life+$data2['lifetime'];
                $idinventory=$data2['id_inventory'];
                $total1 = mysqli_query($sambung, "UPDATE tire_inventory set lifetime = $lifetotal where id_inventory=$idinventory");        
                $noa++;
            }
            $perintah7 = mysqli_query($sambung, "UPDATE unit_site set hm='$smu' where id_unit_site=$idunitsite");
        }
    }
}
if(count($error)==0){
    echo"<script>
    alert('Data submitted');
    history.go(-1);
    </script>";
}
else{
    $passedArray ='"'.implode('"\n"',$error).'"';
    echo"<script>
    alert('$passedArray');
    history.go(-1);
    </script>";
}
?>