<?php
    include "koneksi.php";
    $loop = $_POST['loop']; // jumlah semua unit yg ada di site
    $date = $_POST['date']; // tanggal daily check inspection
    $no =1;
    $batas1 = $_POST['batas1'];
    $batas2 = $_POST['batas2'];
    $batas3 = $_POST['batas3'];  
    
    //$indexBan1 = $_POST['id11']; // cek variabel ini :  id , posisi , unit 
    
    // tanggal daily check harus di isi
    if ($date !="")
    {

        while ($no <=$loop)
        {
            $idunit = $_POST['idunit'.$no];
            // lakukan semua operasi pada masing-masing baris posisi ban : 
            
            //siapkan data inventory menurut posisi ban baris pertama;
            for ($posBan=1 ; $posBan <= $batas1; $posBan++)
            {
                $indexBan1 = $_POST['id'.$posBan.$no];
                
                // persiapkan untuk ambil data reccomended pressure. 
                $qRecPress1 = mysqli_query($sambung, "select a.id_inventory, b.recc_pressure from tire_inventory a, tire_size b where a.size = b.id_size and a.id_inventory='$indexBan1'");
                if($qRecPress1 == FALSE)
                {
                    error_log("Error in tambahdcp.php (qRecPress1): " . mysqli_error($sambung));
                    die("Terjadi kesalahan saat memproses data.");
                }
                $recPress1 = mysqli_fetch_array($qRecPress1);
                $reccPress1 = $recPress1['recc_pressure'];
                $dailyPress1 = $reccPress1 - ($reccPress1 * 0.05); // pressure harian normal 5 % dari recc pressure.    
                // persiapan untuk ambil data tekanan ban posisi pertama. 
                $tekBan1 = $_POST['psi1'.$posBan.$no];
                // perbandingan tekanan posisi ban baris pertama 
            
                if ($tekBan1 < $dailyPress1)
                {
                    $condLine1 = "Low Pressure";
                    
                }
                else 
                {
                    $condLine1 = "Normal";
                    
                }
                // pilih data yang terisi saja 
                if (!empty($tekBan1))
                {
                    // echo $date; echo " - "; echo $indexBan1; echo " = "; echo $tekBan1; echo " > "; echo $condLine1; echo " unit ke : ".$no; echo " idunit : ".$idunit; echo " pos : ".$posBan;echo "<br/>";
                    // simpan kedalam database
                    $insertDaily1 = mysqli_query($sambung, "INSERT INTO daily (id_inventory,id_unit_site,pos,tanggal_daily,pressure,kondisi) VALUES 
                    ('$indexBan1','$idunit','$posBan','$date','$tekBan1','$condLine1')");
                }
                
            }
            
            // siapkan data inventory menurut posisi ban baris ke dua;
            for ($posBan=7; $posBan <= $batas2; $posBan++)
            {
                $indexBan2 = $_POST['id1'.$posBan.$no];

                // persiapkan untuk ambil data recomended pressure. 
                $qRecPress2 = mysqli_query($sambung, "select a.id_inventory, b.recc_pressure from tire_inventory a, tire_size b where a.size = b.id_size and a.id_inventory='$indexBan2'");
                if($qRecPress2 == FALSE)
                {
                    error_log("Error in tambahdcp.php (qRecPress2): " . mysqli_error($sambung));
                    die("Terjadi kesalahan saat memproses data.");
                }
                $recPress2 = mysqli_fetch_array($qRecPress2);
                $reccPress2 = $recPress2['recc_pressure'];
                $dailyPress2 = $reccPress2 - ($reccPress2 * 0.05); // pressure harian normal 10 % dari recc pressure. 

                // persiapan untuk ambil data tekanan ban pada posisi kedua.
                $tekBan2 = $_POST['psi2'.$posBan.$no];

                // perbandingan tekanan posisi ban baris kedua
                if ($tekBan2 < $dailyPress2)
                {
                    $condLine2 = "Low Pressure";
                    
                }
                else 
                {
                    $condLine2 = "Normal";
                    
                }
                // pilih data yang terisi saja 
                if(!empty($tekBan2))
                {
                    //echo $date; echo " - "; echo $indexBan2; echo " = "; echo $tekBan2; echo " > "; echo $condLine2; echo " unit ke : ".$no; echo "<br/>";
                    // simpan kedalam database
                    $insertDaily1 = mysqli_query($sambung, "INSERT INTO daily (id_inventory,id_unit_site,pos,tanggal_daily,pressure,kondisi) VALUES 
                    ('$indexBan2','$idunit','$posBan','$date','$tekBan2','$condLine2')");

                }


            }
            // siapkan data inventory menurut posisi ban baris ke tiga;
            for ($posBan=13; $posBan <= $batas3; $posBan++)
            {
                $indexBan3 = $_POST['id2'.$posBan.$no]; 
                
                // persiapkan untuk ambil data recomended pressure. 
                $qRecPress3 = mysqli_query($sambung, "select a.id_inventory, b.recc_pressure from tire_inventory a, tire_size b where a.size = b.id_size and a.id_inventory='$indexBan3'");
                if($qRecPress3 == FALSE)
                {
                    error_log("Error in tambahdcp.php (qRecPress3): " . mysqli_error($sambung));
                    die("Terjadi kesalahan saat memproses data.");
                }
                $recPress3 = mysqli_fetch_array($qRecPress3);
                $reccPress3 = $recPress3['recc_pressure'];
                $dailyPress3 = $reccPress3 - ($reccPress3 * 0.05); // pressure harian normal 10 % dari recc pressure. 

                // persiapan untuk ambil data tekanan ban pada posisi ketiga.
                $tekBan3 = $_POST['psi3'.$posBan.$no];
                
                // perbandingan tekanan posisi ban baris ketiga
                if ($tekBan3 < $dailyPress3)
                {
                    $condLine3 = "Low Pressure";
                   
                }
                else
                {
                    $condLine3 = "Normal";
                   
                }
                // pilih data yang terisi saja 
                if(!empty($tekBan3))
                {
                    //echo $date; echo " - "; echo $indexBan3; echo " = "; echo $tekBan3; echo " > "; echo $condLine3; echo " unit ke : ".$no; echo "<br/>";
                    // simpan kedalam database. 
                    $insertDaily1 = mysqli_query($sambung, "INSERT INTO daily (id_inventory,id_unit_site,pos,tanggal_daily,pressure,kondisi) VALUES 
                    ('$indexBan3','$idunit','$posBan','$date','$tekBan3','$condLine3')");
                }

            }
            
            $no++;

        } 
        // tampilkan info jika data berhasil di input
        echo "<script> 
            alert('Daily Check Pressure berhasil di Input');
            history.go(-1);
            </script>";
    }
    else
    {
        // tampilkan pesan jika tanggal tidak diisi, kembali ke halaman sebelumnya.
        echo "<script> 
            alert('Isi Tanggal dan Tekanan Ban lebih dulu');
            history.go(-1);
            </script>";

    }
?>