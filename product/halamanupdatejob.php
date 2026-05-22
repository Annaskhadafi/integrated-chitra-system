<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "template_menu.php";
          $idwo = $_GET['idwo'];
          $perintah = mysqli_query($koneksi3, "SELECT * FROM work_order where id_wo=$idwo");
          $data = mysqli_fetch_array($perintah);
          $injury=$data['injury'];
          $wo=$data['wo'];
          $jobtype=$data['job_type'];
          $storeloc=$data['id_store_loc'];
        ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Tire <?php echo $jobtype;?> progress update [WO : <?php echo $wo;?>]<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                      </div>
                      <!-- info row -->
                      <div class="clearfix"></div>
                      <!-- /.row -->
                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>Process</th>
                                <th>Material</th>
                                <th>Qty</th>
                                <th>Time</th>
                                <th>Personil</th>
                                <th>Note</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                              //repair
                              if ($jobtype=="repair"){ ?> 
                              <form  class="form-inline" role="form" action="updatejob.php" method="post">
                                <input class="form" type="hidden" value="<?php echo $idwo;?>" name="wo"/>
                                <input class="form" type="hidden" value="<?php echo $jobtype;?>" name="jobtype"/>
                                <input class="form" type="hidden" value="<?php echo $storeloc;?>" name="storeloc"/>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Skiving' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  
                                  <td>
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job1"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date1"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=1 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv1"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum1"/>
                                          <input type ="number" value="<?php echo $qty;?>" name ="qty1"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time1"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil1">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note1" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Buffing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job2"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date2"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=2 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv2"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum2"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty2"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time2"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil2">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note2" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Cementing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job3"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date3"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td> 
                                    <table>    
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=3 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv3<?php echo $id_inv;?>"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum3<?php echo $id_inv;?>"/>
                                        <tr>
                                          <td><?php echo $data2['desc'];?></td>
                                          <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty3<?php echo $id_inv;?>"/><?php echo $data['smu'];?></td>
                                        </tr>  
                                          <?php
                                        }
                                      ?>
                                    </table>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time3"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil3">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note3" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Buffing innerliner' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job4"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date4"/>
                                  </td>
                                  <!-- button skip -->
                                  <!-- <form  class="form-inline" role="form" action="updatejob.php" method="post"> -->
                                  <td>
                                    <?php echo $data['job'];?>
                                    <input class="form" type ="radio" step="any" value="4" name ="skip4"/> Skip </td>
                                  <!-- </form>  -->
                                  <!-- <td><?php echo $data['job'];?></td> -->
                                  <td><?php echo $data['material_name'];?></td>
                                  <td> 
                                    <div class="pre-scrollable">
                                      <table>
                                        <?php
                                          $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=4 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                          while ($data2 = mysqli_fetch_array($perintah2)){ 
                                            $id_inv=$data2['id_inv'];
                                            $stok=$data2['inv_qty'];
                                            $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                            $datacek = mysqli_fetch_array($cekusage);
                                            $qty=$datacek['qty'];?>
                                            <input type="hidden" value="<?php echo $id_inv;?>" name="inv4<?php echo $id_inv;?>"/>
                                            <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum4<?php echo $id_inv;?>"/>
                                          <tr>
                                            <td><?php echo $data2['desc'];?></td>
                                            <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty4<?php echo $id_inv;?>"/><?php echo $data['smu'];?></td>
                                          </tr>  
                                            <?php
                                          }
                                        ?>
                                      </table>
                                    </div>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time4"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil4">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note4" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE wo=$idwo and job='Install patch' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}    
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job5"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date5"/></td>
                                  <!-- <td>Install patch</td> -->
                                  <!-- button skip -->
                                  <!-- <form  class="form-inline" role="form" action="updatejob.php" method="post"> -->
                                  <td>
                                    <?php echo $data['job'];?>
                                    <input class="form" type ="radio" step="any" value="5" name ="skip5"/> Skip </td>
                                  <!-- </form>  -->
                                  <td><?php echo $data['material_name'];?></td>
                                  <td></td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time5"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil5">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note5" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Built up' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job6"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date6"/>
                                  </td>
                                  <td><?php echo $data['job'];?><?php echo $idjob;?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=5 and a.id_store_loc=$storeloc and a.id_inv!=320 ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv6"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum6"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty6"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time6"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil6">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note6" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Curing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job7"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date7"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=6 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv7"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum7"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty7"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time7"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil7">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                        
                                        
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note7" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Finishing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job8"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date8"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=7 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv8"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum8"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty8"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time8"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil8">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note8" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE wo=$idwo and job='Quality control' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}    
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job9"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date9"/></td>
                                  <td>Quality Control</td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td></td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time9"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil9">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (3,2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note9" placeholder=""/>
                                  </td>
                                </tr>
                                <button class="btn btn-info"><i class="fa fa-pencil"></i> Update</button>
                              </form>
                              <?php 
                              // retread
                              }else { ?>
                                <form  class="form-inline" role="form" action="updatejob.php" method="post">
                                <input class="form" type="hidden" value="<?php echo $jobtype;?>" name="jobtype"/>
                                <input class="form" type="hidden" value="<?php echo $idwo;?>" name="wo"/>
                                <input class="form" type="hidden" value="<?php echo $storeloc;?>" name="storeloc"/>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Buffing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];
                                    if(isset($data['person'])){
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";} }
                                    else{$idrpr="''";}                         
                                                                          
                                  ?>
                                  <td>
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job1"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date1"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>
                                      <table>
                                        <?php
                                          $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=8 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                          while ($data2 = mysqli_fetch_array($perintah2)){ 
                                            $id_inv=$data2['id_inv'];
                                            $stok=$data2['inv_qty'];
                                            $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                            $datacek = mysqli_fetch_array($cekusage);
                                            $qty=$datacek['qty'];?>
                                          <tr>
                                            <td><?php echo $data2['desc'];?></td>
                                            <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty1"/><?php echo $data['smu'];?></td>
                                          </tr>  
                                            <?php
                                          }
                                        ?>
                                      </table>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time1"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil1">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note1" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Skiving & Filling' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job2"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date2"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=2 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv2"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum2"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty2"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time2"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil2">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note2" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Building' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job']; 
                                    // echo $idwo;     
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job3"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date3"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td> 
                                    <table>    
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category IN (5,6,7) and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv3<?php echo $id_inv;?>"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum3<?php echo $id_inv;?>"/>
                                        <tr>
                                          <td><?php echo $data2['desc'];?></td>
                                          <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty3<?php echo $id_inv;?>"/><?php echo $data['smu'];?></td>
                                        </tr>
                                          <?php
                                        }
                                      ?>
                                    </table>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time3"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil3">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note3" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE wo=$idwo and job='Curing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}    
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job4"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date4"/></td>
                                  <td>Curing</td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>
                                    <div class="pre-scrollable"> 
                                      
                                    <table>    
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=4 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv4<?php echo $id_inv;?>"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum4<?php echo $id_inv;?>"/>
                                        <tr>
                                          <td><?php echo $data2['desc'];?></td>
                                          <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty4<?php echo $id_inv;?>"/><?php echo $data['smu'];?></td>
                                        </tr>
                                          <?php
                                        }
                                      ?>
                                    </table>
                                    </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time4"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil4">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                    </div>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note4" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Finishing' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job5"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date5"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=8 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv5"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum5"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty5"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time5"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil5">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note5" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Quality Control' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job6"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date6"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>       
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=8 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv6"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum6"/>
                                          <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty6"/><?php echo $data['smu'];?>
                                          <?php
                                        }
                                      ?>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time6"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil6">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                        
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note6" placeholder=""/>
                                  </td>
                                </tr>
                                <tr>
                                  <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b WHERE a.wo=$idwo and a.job='Painting' and a.material=b.id_matstock");
                                    $data = mysqli_fetch_array($perintah);
                                    $idjob=$data['id_job'];                                    
                                    
                                    if(isset($data['person'])){$idrpr=$data['person'];}
                                    else{$idrpr="''";}                                       
                                  ?>
                                  <td>
                                    <!-- <?php echo $idjob;?> -->
                                    <input type="hidden" value="<?php echo $idjob;?>" name="job7"/>
                                    <input type="date" value="<?php echo $data['date'];?>" name="date7"/>
                                  </td>
                                  <td><?php echo $data['job'];?></td>
                                  <td><?php echo $data['material_name'];?></td>
                                  <td>
                                  <table>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_inventory a WHERE a.category=3 and a.id_store_loc=$storeloc ORDER BY a.desc");
                                        while ($data2 = mysqli_fetch_array($perintah2)){ 
                                          $id_inv=$data2['id_inv'];
                                          $stok=$data2['inv_qty'];
                                          $cekusage = mysqli_query($koneksi3, "SELECT qty FROM mat_usage WHERE job=$idjob and inv=$id_inv");
                                          $datacek = mysqli_fetch_array($cekusage);
                                          $qty=$datacek['qty'];?>
                                          <input type="hidden" value="<?php echo $id_inv;?>" name="inv7<?php echo $id_inv;?>"/>
                                          <input type="hidden" value="<?php echo $datacek['qty'];?>" name="qtysebelum7<?php echo $id_inv;?>"/>
                                        <tr>
                                          <td><?php echo $data2['desc'];?></td>
                                          <td> : <input type ="number" step="any" value="<?php echo $qty;?>" name ="qty7<?php echo $id_inv;?>"/><?php echo $data['smu'];?></td>
                                        </tr> 
                                          <?php
                                        }
                                      ?>
                                    </table>
                                  </td>
                                  <td><input class="form" type ="number" step="any" value="<?php echo $data['time'];?>" name ="time7"/> Hr</td>
                                  <td>
                                    <select class="form-control" name="personil7">
                                      <?php 
                                        $nma = mysqli_query($koneksi3, "SELECT * FROM user WHERE id_user=$idrpr");
                                        $datrpr = mysqli_fetch_array($nma);
                                      ?>
                                      <option value="<?php echo $datrpr['id_user'];?>"selected="selected"><?php echo $datrpr['nama'];?></option>
                                      <?php
                                        $perintah2 = mysqli_query($koneksi3, "SELECT * FROM user WHERE level in (2)");
                                        while ($data2 = mysqli_fetch_array($perintah2)) { 
                                          $iduser=$data2['id_user'];
                                          $nama=$data2['nama'];?>
                                          <option value="<?php echo $iduser;?>"><?php echo $nama;?></option>
                                          <?php 
                                        } 
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type ="text" value="<?php echo $data['note'];?>" name ="note7" placeholder=""/>
                                  </td>
                                </tr>
                                <button class="btn btn-info"><i class="fa fa-pencil"></i> Update</button>
                              </form>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </section>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
       <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"></script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>
