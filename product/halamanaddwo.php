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
          <!-- <?php echo "levelnya adalah ".$level ?> -->
            <?php 
            if($level==1 OR $level==3){?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="x_title">
                      <h3>Received tire</h3>   
                    </div>                
                      <form  class="form-inline" role="form" action="tambahtire.php" method="post">
                        <input class="form-control" type ="text" name ="sn" placeholder="SN tire"/>  
                        <input class="form-control" type ="text" name ="size" placeholder="Size"/>    
                        <input class="form-control" type ="text" name ="brand" placeholder="Brand"/>
                        <input class="form-control" type ="text" name ="nocargo" placeholder="No.Cargo Manifest"/>
                        <select class="form-control" name="tipe">
                          <option value="radial">Radial</option>
                          <option value="bias">Bias</option>
                        </select>
                        <input class="form-control" value="1" type="hidden" name="status"/>                      
                        <select class="form-control" name="customer">
                          <option value="">Customer - Site</option>
                          <?php 
                            $perintah=mysqli_query($koneksi3, "SELECT * from customer a, customer_data b where a.nama_customer=b.id_cust order by b.cust_name asc");
                            while ($data = mysqli_fetch_array($perintah)) {?>
                              <option value="<?php echo  $data['id_customer'];?>"> <?php echo $data['cust_name'];?> - <?php echo $data['site'];?></option>
                              <?php 
                            } 
                          ?> 
                        </select>
                        <input class="form-control" type="date" name="date"/>
                        <select class="form-control" name="job_type">
                          <option value="repair">Repair</option>
                          <option value="retread">Retread</option>
                          <option value="retread">Service</option>
                        </select>
                        <br>
                        <br>
                        <button type="submit" value="submit" class="btn btn-success"> Submit</button>
                      </form>
                  </div>
                </div>
              </div> 
            </div>
            <?php } 
            else {}?>
            
        <?php if($name!=""){ ?>
            <div class="clearfix"></div>
            <div class="row">
            <div class="col-md-12 col-sm-6 col-xs-6">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title">
                    <h3>Work order list </h3>  
                  </div>                  
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                  <thead style="background:#f5f5f5;">
                        <tr>
                          <th>No</th>
                          <th>Work_order</th>
                          <th>Job_type</th>
                          <th>Size</th>
                          <th>SN</th>
                          <th>Injury</th>
                          <th>Type</th>
                          <th>No.Cargo Manifest</th>
                          <th>Customer</th>
                          <th>Site</th>
                          <th>Rcvd_dte</th>
                          <th>Location</th>
                          <th>Create_by</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
                        $perintah = mysqli_query($koneksi3, "SELECT * FROM work_order a,customer b,customer_data c,store_loc d,user e where a.customer=b.id_customer and b.nama_customer=c.id_cust and a.id_store_loc=d.id_store_loc and a.createby=e.id_user ORDER BY id_wo DESC LIMIT 10");
                        $no=1;
                        while ($data = mysqli_fetch_array($perintah)) { 
                          $bast=$data['bast'];
                          $status=$data['status'];
                          $jobtype=$data['job_type'];
                          $tiretype=$data['type'];
                          if($status==6 && $level!=1){}                          
                          else{?>
                          <tr>                            
                              <td><?php $idwo=$data['id_wo'];echo $idwo;?> </td>
                                <td><?php echo $data['wo'];?> </td>
                                <td><?php echo $data['job_type'];?> </td>
                                <td><?php echo $data['size'];?> </td>
                                <td><?php echo $data['tire_sn'];?> </td>
                                <td><?php echo $data['injury'];?> </td>
                                <td><?php echo $tiretype;?></td>
                                <td><?php echo $data['nocargo'];?></td>
                                <td><?php echo $data['cust_name'];?></td>
                                <td><?php echo $data['site'];?> </td>
                                <td><?php echo $data['received_date'];?></td>
                                <td><?php echo $data['store_location'];?></td>
                                <td><?php echo $data['nama']; ?></td>
                            <?php 
                            }
                          $no++;
                        } ?>
                    </tbody>
                    </table>            
                </div>
              </div>
            </div> 
          </div>
          </div>
        </div>
        <?php } ?>
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
    <script src="../vendors/js/jquery.min.js"></script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <!-- JS pop up modal -->
    <script>
      $(document).ready(function(){
          $('[data-toggle="popover"]').popover();   
      });
    </script>
  </body>
</html>