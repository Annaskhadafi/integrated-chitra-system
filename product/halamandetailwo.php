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
          $perintah = mysqli_query($koneksi3, "SELECT * FROM work_order a,customer b,user c,customer_data d where a.id_wo='$idwo' and a.customer=b.id_customer and b.nama_customer=d.id_cust and a.createby=c.id_user");
          $data = mysqli_fetch_array($perintah);
          $injury=$data['injury'];
        ?>
        <!-- page content -->        
        <div class="right_col" role="main">  
          <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Tire <?php echo ($data['job_type']!=null ? $data['job_type'] : "repair"); ?> jobcard<small></small></h2><button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                      </div>
                      <!-- info row -->
                      <div class="row">
                        <div class="col-sm-2">
                          <u>Work order</u>
                          <br>WO : <strong><?php echo $data['wo'];?></strong>
                          <br>WO date: <?php echo $data['input_date'];?>
                          <br>Created by: <?php echo $data['nama'];?>
                          <br>Received date : <?php echo $data['received_date'];?>   
                          <br>Finished date : <?php 
                                                $perintah3 = mysqli_query($koneksi3, "SELECT date FROM job where wo='$idwo' ORDER BY id_job DESC LIMIT 1");
                                                $data3 = mysqli_fetch_array($perintah3);
                                                echo $data3['date'];
                                              ?>                    
                        </div>
                        <div class="col-sm-2">
                          <u>Tire details :</u>
                          <address>
                            Brand : <strong><?php echo $data['brand'];?></strong>
                            <br>Size : <?php echo $data['size'];?>
                            <br>Construction : <?php echo $data['type'];?>
                            <br>Serial Number : <strong><?php echo $data['tire_sn'];?></strong>
                            <br>Tire injury : <?php echo $injury;?>
                            <br>Type : <?php echo $data['repair_type'];?>
                          </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-2">
                          <u>Customer :</u>
                          <address>
                            Name : <strong><?php echo $data['cust_name'];?></strong>
                            <br>Site : <?php echo $data['site'];?>
                            <br>ID : <?php echo $data['idsap'];?>
                          </address>
                        </div>
                        <div class="col-sm-2"></div
                        <div class="col-sm-2">
                         <img width="250" height="100" src="images/cp_logo.png"/>
                        </div>
                        <!-- /.col -->
                      </div>
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
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $perintah = mysqli_query($koneksi3, "SELECT * FROM job a,material_stock b,user c WHERE wo=$idwo and a.person=c.id_user and a.material=b.id_matstock ORDER BY a.id_job");
                                $no=1;
                                while ($data = mysqli_fetch_array($perintah)) {
                                  $idjob=$data['id_job'];
                              ?>
                              <tr>
                                <td><?php echo $data['date'];?></td>
                                <td><?php echo $data['job'];?></td>
                                <td>
                                  <?php
                                    $qtyusage=array();
                                    $perintah2 = mysqli_query($koneksi3, "SELECT * FROM mat_usage a,mat_inventory b WHERE a.job=$idjob and a.inv=b.id_inv and a.qty>0 ORDER BY b.desc");
                                    while ($data2 = mysqli_fetch_array($perintah2)){ 
                                      echo $data2['desc']."<br>";
                                      $qtyusage[]=$data2['qty'];
                                    }
                                  ?>  
                                </td>
                                <td>
                                  <?php 
                                        foreach($qtyusage as $qtyusage){
                                          echo $qtyusage;echo " ".$data['smu']."<br>";
                                        } 
                                  ?>
                                </td>
                                <td><?php echo $data['time'];?> Hr</td>
                                <td><?php echo $data['nama'];?></td>
                              </tr>
                              <?php } ?>
                            </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                      <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-3">
                          <p class="lead"><strong>QC Sign :</strong></p>
                          date :
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-3">
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-3">
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-3">
                          <p class="lead"><strong>Supervisor Sign :</strong></p>
                          date :
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
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

  </body>
</html>
