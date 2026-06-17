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
        ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
                <?php 
                    $idwo = isset($_GET['id']) ? $_GET['id'] : '';
                    $perintah = mysqli_query($koneksi3, "SELECT * FROM work_order WHERE id_wo='$idwo'");
                    $data = mysqli_fetch_array($perintah);
                    $remark=$data['remark'];
                    
                ?>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col">
                <div class="x_panel">
                  <div class="x_title" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="margin: 0;">Repair Jobcard : <u><?php echo $data['wo']; ?></u></h2>
                    <img src="images/cp_logo.png" alt="Logo" style="height: 60px;">
                  </div>
                  <div class="x_content">
                    <section class="content invoice">
                      <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                          <address>
                            <table style="border-collapse: collapse;">
                              <tr>
                                <td><strong>Customer</strong></td>
                                <td>: <?php echo $data['customer']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Project</strong></td>
                                <td>: <?php echo $data['site']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Serial Number</strong></td>
                                <td>: <?php echo $data['tire_sn']; ?></td>
                              </tr>
                            </table>
                          </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                          <address>
                            <table style="border-collapse: collapse;">
                              <tr>
                                <td><strong>Tyre Brand</strong></td>
                                <td>: <?php echo $data['brand']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Tyre Pattern</strong></td>
                                <td>: <?php echo $data['pattern']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Tyre Size</strong></td>
                                <td>: <?php echo $data['size']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Tyre Type</strong></td>
                                <td>: <?php echo $data['type']; ?></td>
                              </tr>
                            </table>
                          </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                          <address>
                            <table style="border-collapse: collapse;">
                              <!--<tr>-->
                              <!--  <td><strong>Received Date</strong></td>-->
                              <!--  <td>: <?php echo $data['received_date']; ?></td>-->
                              <!--</tr>-->
                              <!--<tr>-->
                              <!--  <td><strong>Inspection Date</strong></td>-->
                              <!--  <td>: <?php echo $data['inspect_date']; ?></td>-->
                              <!--</tr>-->
                              <tr>
                                <td><strong>Wo Date</strong></td>
                                <td>: <?php echo $data['wo_date'];?></td>
                              </tr>
                              <tr>
                                <?php
                                    $perintah = mysqli_query($koneksi3, "SELECT date(date) as date FROM `job`where wo='$idwo' and job='Skiving' ORDER BY date ASC limit 1");
                                    $data = mysqli_fetch_array($perintah);
                                    $progress_date=$data['date'];
                                    $perintah = mysqli_query($koneksi3, "SELECT date(date) as date FROM `job`where wo='$idwo' and job='Painting' ORDER BY date DESC limit 1");
                                    $data = mysqli_fetch_array($perintah);
                                    $finish_date=$data['date'];
                                ?>
                                <td><strong>Progress Date</strong></td>
                                <td>: <?php echo $progress_date;?></td>
                              </tr>
                              <tr>
                                <td><strong>Finish Date</strong></td>
                                <td>: <?php echo $finish_date;?></td>
                              </tr>
                              <tr>
                                <td><strong>Injury</strong></td>
                                <td>: <?php echo $remark; ?></td>
                              </tr>
                            </table>
                          </address>
                        </div>

                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="  table">
                            <?php
                                $perintah1 = mysqli_query($koneksi3, "SELECT DISTINCT proseske FROM job WHERE wo='$idwo' ORDER BY proseske ASC");
                                while($data1 = mysqli_fetch_array($perintah1)){
                                    $proseske_val = $data1['proseske'];
                                ?>
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th colspan="7" style="background: #eee;">Process #<?php echo $proseske_val; ?></th>
                                      </tr>
                                      <tr>
                                        <th>Injuries</th>
                                        <th>Date</th>
                                        <th>Process</th>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>Duration (Min)</th>
                                        <th>Manpower</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $material = array();
                                            $satuan = array();
                                            $jmlh = array();
                                            $perintah2 = mysqli_query($koneksi3, "SELECT 
                                                                                  a.wo, 
                                                                                  a.job,
                                                                                  b.material_name,
                                                                                  b.smu, 
                                                                                  DATE(a.date) AS date, 
                                                                                  a.time, 
                                                                                  a.person, 
                                                                                  a.note, 
                                                                                  a.qty,
                                                                                  a.proseske,
                                                                                  CONCAT(a.wo, a.job, a.proseske) AS kunci 
                                                                                FROM job a
                                                                                LEFT JOIN material_stock b ON a.material = b.id_matstock
                                                                                WHERE a.wo = '$idwo' AND a.proseske = '$proseske_val'");
                                            while ($data2 = mysqli_fetch_array($perintah2)) {
                                                $kunci = $data2['kunci'];
                                                $nama = $data2['material_name'];
                                                $smu = $data2['smu'];
                                                
                                                if (!isset($jmlh[$kunci])) {
                                                    $jmlh[$kunci] = 0;
                                                }
                                                $jmlh[$kunci] += $data2['qty'];
                                                
                                                if (!isset($material[$kunci])) {
                                                    $material[$kunci] = $nama ?? '';
                                                    $satuan[$kunci] = $smu ?? '';
                                                } else if ($nama) {
                                                    $material[$kunci] .= ($material[$kunci] ? ", " : "") . $nama;
                                                    // Typically keep the first unit or list them
                                                }
                                            }
                                            
                                            $total_time = 0;                                            
                                            $perintah3 = mysqli_query($koneksi3, "SELECT DISTINCT wo, job, date(date) as date, time, person, note, proseske,
                                                                                  CONCAT(wo, job, proseske) AS kunci  
                                                                                  FROM job 
                                                                                  WHERE wo='$idwo' AND proseske = '$proseske_val'");
                                            while($data3 = mysqli_fetch_array($perintah3)){
                                                $job = $data3['job'];
                                                $time = $data3['time'];
                                                $kunci = $data3['kunci'];
                                                $total_time += $time;
                                                ?>
                                                <tr>
                                                    <td><?php if($job=='Dimensi Luka'){ echo $data3['note'];} elseif($job=='Skiving') {echo $remark;}?></td>
                                                    <td><?php echo $data3['date']; ?></td>
                                                    <td><?php if ($time==0){echo "<s>".$job."</s>";} else{echo $job;} ?></td>
                                                    <td><?php echo $material[$kunci] ?? '-'; ?></td>
                                                    <td><?php echo ($jmlh[$kunci] ?? 0) . " " . ($satuan[$kunci] ?? ""); ?> </td>
                                                    <td><?php echo $data3['time']; ?></td>
                                                    <td><?php echo $data3['person']; ?></td>
                                                </tr>  
                                            <?php    
                                            }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>Total Duration</strong></td>
                                            <td><?php echo number_format($total_time/60,2); ?></td>
                                            <td><strong>Hours</strong></td>
                                        </tr> 
                                    </tbody>
                                  </table>
                                <?php    
                                }
                            ?>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-md-6">
                          <p class="lead">Quality Check:</p>
                            <div style="border: 1px solid #000; height: 80px; width: 100px;"></div>
                            <p style="margin-top: 5px;">Sign</p>
                        </div>
                        <div class="col-md-3">

                        </div>
                        <div class="col-md-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>Uom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($material as $key => $value): ?>
                                        <?php if (trim($value) !== ''): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($value); ?></td>
                                                <td><?php echo htmlspecialchars($jmlh[$key] ?? 0); ?></td>
                                                <td><?php echo htmlspecialchars($satuan[$key] ?? "-"); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- this row will not appear when printing -->
                      <div class="row no-print">
                        <div class=" ">
                          <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            F.RPR.REM.003.00
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
   <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>