<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md repair-jobcard-print-page">
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
                    $printFileParts = array(
                        $data['wo'] ?? '',
                        $data['tire_sn'] ?? '',
                        $data['customer'] ?? '',
                        $data['site'] ?? ''
                    );
                    $printFileParts = array_map(function($value) {
                        $value = preg_replace('/\s+/', ' ', trim((string) $value));
                        return preg_replace('/[\/\\\\:*?"<>|]/', '', $value);
                    }, $printFileParts);
                    $printFileParts = array_filter($printFileParts, function($value) {
                        return $value !== '';
                    });
                    $printFileName = implode('-', $printFileParts);
                    if ($printFileName === '') {
                        $printFileName = 'Repair Jobcard';
                    }
                ?>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Repair Jobcard : <u><?php echo $data['wo']; ?></u></h2>
                    <img src="images/cp_logo.png" alt="Logo">
                  </div>
                  <div class="x_content">
                    <section class="content invoice">
                      <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                          <address>
                            <table>
                              <tr>
                                <td style="width: 100px;"><strong>Customer</strong></td>
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
                            <table>
                              <tr>
                                <td style="width: 100px;"><strong>Tyre Brand</strong></td>
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
                            <table>
                              <tr>
                                <td style="width: 110px;"><strong>Wo Date</strong></td>
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
                        <div class="table-responsive" style="width: 100%;">
                            <?php
                                $perintah1 = mysqli_query($koneksi3, "SELECT DISTINCT proseske FROM job WHERE wo='$idwo' ORDER BY proseske ASC");
                                while($data1 = mysqli_fetch_array($perintah1)){
                                    $proseske_val = $data1['proseske'];
                                ?>
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th colspan="7" style="background: #f2f2f2; font-weight: bold; border: 1px solid #666;">Process #<?php echo $proseske_val; ?></th>
                                      </tr>
                                      <tr>
                                        <th style="width: 15%;">Injuries</th>
                                        <th style="width: 12%;">Date</th>
                                        <th style="width: 15%;">Process</th>
                                        <th style="width: 25%;">Material</th>
                                        <th style="width: 10%;">Qty</th>
                                        <th style="width: 13%;">Duration (Min)</th>
                                        <th style="width: 10%;">Manpower</th>
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
                                        <tr style="background-color: #fafafa;">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align: right;"><strong>Total Duration:</strong></td>
                                            <td><strong><?php echo number_format($total_time/60,2); ?></strong></td>
                                            <td><strong>Hours</strong></td>
                                        </tr> 
                                    </tbody>
                                  </table>
                                <?php    
                                }
                            ?>
                        </div>
                      </div>
                      <!-- /.row -->

                      <!-- Footer Area -->
                      <div class="repair-print-footer">
                        <div class="repair-print-sign">
                          <p class="lead">Quality Check:</p>
                          <div class="repair-sign-box"></div>
                          <p style="margin-top: 5px; font-size: 11px;">Sign</p>
                        </div>
                        
                        <div class="repair-print-material">
                            <table class="table table-striped" style="margin: 0;">
                                <thead>
                                    <tr>
                                        <th>Material Summary</th>
                                        <th>Qty</th>
                                        <th>Uom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $has_material = false;
                                    foreach ($material as $key => $value): 
                                        if (trim($value) !== ''): 
                                            $has_material = true;
                                    ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($value); ?></td>
                                                <td><?php echo htmlspecialchars($jmlh[$key] ?? 0); ?></td>
                                                <td><?php echo htmlspecialchars($satuan[$key] ?? "-"); ?></td>
                                            </tr>
                                    <?php 
                                        endif; 
                                    endforeach; 
                                    if(!$has_material):
                                    ?>
                                        <tr>
                                            <td colspan="3" style="text-align: center; color: #999;">No material used</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                      </div>
                      <!-- /.row -->

                      <!-- Tombol Print di Web Web Screen -->
                      <div class="row no-print" style="margin-top: 20px;">
                        <div class="col-xs-12">
                          <button class="btn btn-default" onclick="printJobcard();"><i class="fa fa-print"></i> Print Jobcard</button>
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
      </div>
    </div>

    <!-- jQuery & Bootstrap -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../build/js/custom.min.js"></script>
    <script>
      var repairJobcardPrintTitle = <?php echo json_encode($printFileName); ?>;

      function printJobcard() {
        var originalTitle = document.title;
        var titleRestored = false;

        function restoreTitle() {
          if (!titleRestored) {
            document.title = originalTitle;
            titleRestored = true;
          }
        }

        document.title = repairJobcardPrintTitle;

        if ('onafterprint' in window) {
          window.addEventListener('afterprint', restoreTitle, { once: true });
        }

        window.print();
        setTimeout(restoreTitle, 1000);
      }
    </script>
  </body>
</html>
