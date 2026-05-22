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
          include "menu.php";
          $year=date("Y");
        ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-6">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title">
                    <h3>Repair status <?php echo $year;?></h3>
                  </div>
                  <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Cust_name</th>
                          <th>Inspect</th>
                          <th>Reject</th>
                          <th>Progress</th>
                          <th>BAST/PO</th>
                          <th>Complete</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $perintah = mysqli_query($sambung, "SELECT * FROM customer_data");
                        while ($data = mysqli_fetch_array($perintah)) { 
                          $idcust=$data['id_cust'];?>
                          <tr>
                            <th><?php echo $data['cust_name'];?></th>
                            <?php 
                              $perintah2=mysqli_query($sambung, "SELECT count(id_wo) as ins from work_order a,customer b where a.status=1 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datains=mysqli_fetch_assoc($perintah2); 
                              $jumlahinspect=$datains['ins'];
                              $perintah3=mysqli_query($sambung, "SELECT count(id_wo) as pro from work_order a,customer b where a.status=2 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datapro=mysqli_fetch_assoc($perintah3); 
                              $jumlahprog=$datapro['pro'];
                              $perintah4=mysqli_query($sambung, "SELECT count(id_wo) as rej from work_order a,customer b where a.status=3 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datarej=mysqli_fetch_assoc($perintah4); 
                              $jumlahrej=$datarej['rej'];
                              $perintah5=mysqli_query($sambung, "SELECT count(id_wo) as bp from work_order a,customer b where a.status=4 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $databp=mysqli_fetch_assoc($perintah5); 
                              $jumlahbp=$databp['bp'];
                              $perintah6=mysqli_query($sambung, "SELECT count(id_wo) as com from work_order a,customer b where a.status=5 and a.received_date like '$year%' and a.customer=b.id_customer and b.nama_customer=$idcust ");
                              $datacom=mysqli_fetch_assoc($perintah6); 
                              $jumlahcom=$datacom['com'];
                            ?>
                            <td><?php echo $jumlahinspect;?></td>
                            <td><?php echo $jumlahrej;?></td>
                            <td><?php echo $jumlahprog;?></td>
                            <td><?php echo $jumlahbp;?></td>
                            <td><?php echo $jumlahcom;?></td>
                          </tr><?php 
                        }?>
                      </tbody>
                  </table>              
                </div>
              </div>
            </div> 
            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel">
              </div>
            </div> 
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">              
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title"><h3>Material avg consumption <?php echo $tahun;?></h3></div>            
                  <div class="x_content">
                    <?php 
                      $BulanIni= date('n');
                      $bulan = array("01","02","03","04","05","06","07","08","09","10","11","12");
                    ?>                   
                    <table class="table table-bordered">
                      <thead>
                        <tr style="background-color:#B8BFF1;">
                          <th></th>
                          <th>Jan</th>
                          <th>Feb</th>
                          <th>Mar</th>
                          <th>Apr</th>
                          <th>May</th>
                          <th>Jun</th>
                          <th>Jul</th>
                          <th>Aug</th>
                          <th>Sep</th>
                          <th>Oct</th>
                          <th>Nov</th>
                          <th>Dec</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        $perintahInv = mysqli_query($sambung, "SELECT a.desc FROM mat_inventory a group by a.desc");
                          while ($dataInv = mysqli_fetch_array($perintahInv)) { 
                          $nmainv=$dataInv['desc'];?>
                            <tr>
                              <th scope="row"><?php echo $nmainv;?></th>              
                              <?php                 
                                foreach ($bulan as $month){
                                  if ($month <= $BulanIni){
                                    //hitung rata rata penggunaan material CRP76
                                    $perintahAVG = mysqli_query($sambung, "SELECT AVG(a.qty) AS Avg FROM mat_usage a,job b,mat_inventory c where a.job=b.id_job and b.date like '$tahun-$month-%' and b.wo>0 and a.inv=c.id_inv and c.desc='$nmainv'");
                                    $dataAVG=mysqli_fetch_array($perintahAVG);
                                    $avg = str_replace(",", "", number_format($dataAVG['Avg']));
                                            }
                                            else{
                                              $avg = 0;
                                            }?>
                                            <td><?php echo $avg;?></td>
                                            <?php
                                          }
                              ?>
                            </tr>
                            <?php
                          } ?>
                      </tbody>
                    </table>  
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <!-- modal edit data tire inventory -->
    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Repair Jobcard
      </div>
      <div class="clearfix">
      </div>
    </footer>
    <!-- /footer content -->
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
    <!-- Chart.js -->
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
    <!-- Datatables -->

  </body>
</html>
