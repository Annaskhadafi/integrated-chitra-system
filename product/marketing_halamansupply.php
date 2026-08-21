<?php
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(4));
?>
<!DOCTYPE html>
<html lang="en">
<?php include'header.php';?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
        <a href="halamanDataMaster.php" class="site_title"></a>
            <div class="navbar nav_title" style="border: 0;">
            </div>
            <div class="clearfix"></div>
            <div class="profile">
              <div class="profile_info">
                <?php 
                  include_once "koneksi.php";
                  $username = $_SESSION['username'];
                  $stmt = mysqli_prepare($koneksi, "SELECT * FROM user a, department b WHERE a.username = ? AND a.department = b.id_dept");
                  mysqli_stmt_bind_param($stmt, "s", $username);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  $user = mysqli_fetch_array($result);
                  $dept = $user ? $user['department'] : null;
                  $name = $user ? $user['name'] : '';
                  $idlogin = $user ? $user['id_user'] : null;
                  mysqli_stmt_close($stmt);
                ?>
                <h2>Technical <br><?php echo $name;?></h2>
              </div>
            </div>
            <br/>
            <?php include('template_menu.php');?>
        </div>
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                  <a id="menu_toggle">
                    <i class="fa fa-bars">
                    </i>
                  </a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                  <li class="">
                    <h3>
                      <a style="margin-right:20px;">
                        <?php echo date("l");echo date(", d-m-Y");?>
                      </a>
                    </h3>
                  </li>
                </ul>
            </div>
        </div>
        <?php if($name!=""){?>      
          <div class="right_col" role="main">
            <div class="clearfix"></div>
            <div class="row" style="margin-top:0px">
              <div class="x_panel">
                <div class="x_title">
                    <h2>Add Tire Supply Data</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <form  class="form-inline" role="form" action="tambahData.php" method="post">
                            <input class="form-control" type = "hidden" name ="item" value="supply" required> 
                            <select class="form-control" name="customer" required>
                             <option value="">Customer</option>     
                              <?php 
                                $perintah=mysqli_query($koneksi2,"SELECT * from customer_master WHERE TRIM(customer) <> '' order by customer");
                                while ($data = mysqli_fetch_array($perintah)) {?>    
                                  <option value='<?php echo $data['id_customer_master']; ?>'><?php echo $data['customer'];?></option>     
                              <?php }?>         
                            </select>
                            <select class="form-control" name="supplier" required>
                             <option value="">Supplier</option>     
                              <?php 
                                $perintah=mysqli_query($koneksi8,"SELECT * from competitor ORDER BY competitor_name");
                                while ($data = mysqli_fetch_array($perintah)) {?>    
                                  <option value='<?php echo $data['id_competitor']; ?>'><?php echo $data['competitor_name'];?></option>     
                              <?php }?>         
                            </select>

                            <input style="width:200px;" class="form-control" type = "text" name = "brand" placeholder="Brand" required>                        

                            <input style="width:180px;" class="form-control" type = "text" name = "size" placeholder="Size" required>                       

                            <input style="width:180px;" class="form-control" type = "number" name = "qty" placeholder="Qty" required>

                            <input style="width:130px;" class="form-control" type = "date" name = "period" required>                                              
 
                            <input class="form-control" value="<?php echo $idlogin ?>" type="hidden" name="user">

                            <br>
                            <br>
                            <button type="submit" value="submit" class="btn btn-success">Submit</button>
                        </form> 
                    </div>
                </div>
              </div>
              <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_title">
                            <h2>Summary Positioning</h2>
                            <div class="clearfix"></div>
                        </div>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>No</th>
                              <th>Customer</th>
                              <th>Supplier</th>
                              <th>Size</th>
                              <th>Supply Qty</th>
                              <th>Month</th>
                              <th>Year</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi8,"SELECT 
                                                                    c.customer,
                                                                    b.competitor_name,
                                                                    a.size,
                                                                    SUM(a.qty_supply) AS qty_supply,
                                                                    DATE_FORMAT(a.periode, '%b') AS periode,
                                                                    DATE_FORMAT(a.periode, '%Y') AS year
                                                                FROM chitraparatama_competitor.allSupply a
                                                                JOIN chitraparatama_competitor.competitor b 
                                                                    ON a.supplier=b.id_competitor
                                                                JOIN chitraparatama_fleetlist.customer_master c 
                                                                    ON a.id_customer_master=c.id_customer_master
                                                                JOIN chitraparatama_ics.user d 
                                                                    ON a.id_user=d.id_user
                                                                GROUP BY 
                                                                    c.customer,
                                                                    b.competitor_name,
                                                                    a.size,
                                                                    DATE_FORMAT(a.periode, '%b')
                                                                ORDER BY 
                                                                    c.customer,
                                                                    qty_supply DESC;
                                                                ");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $no;?></td>
                              <td><?php echo $data['customer'];?></td>
                              <td><?php echo $data['competitor_name'];?></td>
                              <td><?php echo $data['size'];?></td>
                              <td><?php echo $data['qty_supply'];?></td>
                              <td><?php echo $data['periode'];?></td>
                              <td><?php echo $data['year'];?></td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
              <div class="x_panel">
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_title">
                            <h2>Recent Update</h2>
                            <div class="clearfix"></div>
                        </div>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Id</th>
                              <th>Customer</th>
                              <th>Supplier</th>
                              <th>Brand</th>
                              <th>Size</th>
                              <th>Qty</th>
                              <th>Periode</th>
                              <th>Update by</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi8,"SELECT a.id_allsupply,a.brand,a.size,a.qty_supply,date(a.periode) as periode,b.competitor_name,c.customer,d.name 
                                                                FROM chitraparatama_competitor.allSupply a
                                                                JOIN chitraparatama_competitor.competitor b ON a.supplier=b.id_competitor
                                                                JOIN chitraparatama_fleetlist.customer_master c ON a.id_customer_master=c.id_customer_master 
                                                                JOIN chitraparatama_ics.user d ON a.id_user=d.id_user;");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $data['id_allsupply'];?></td>
                              <td><?php echo $data['customer'];?></td>
                              <td><?php echo $data['competitor_name'];?></td>
                              <td><?php echo $data['brand'];?></td>
                              <td><?php echo $data['size'];?></td>
                              <td><?php echo $data['qty_supply'];?></td>
                              <td><?php echo $data['periode'];?></td>
                              <td><?php echo $data['name'];?></td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                </div>
              </div>
            </div>
          </div>          
        <?php }
          else {}
        ?>
      </div>
    </div>
    <footer>
      <div class="pull-right">
          
      </div>
      <div class="clearfix"></div>
    </footer>

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


    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Datatables -->
        
        <script>
          $(document).ready(function() {
            var handleDataTableButtons = function() {
              if ($("#datatable-buttons").length) {
                $("#datatable-buttons").DataTable({
                  dom: "Bfrtip",
                  buttons: [
                    {
                      extend: "copy",
                      className: "btn-sm"
                    },
                    {
                      extend: "csv",
                      className: "btn-sm"
                    },
                    {
                      extend: "excel",
                      className: "btn-sm"
                    },
                    {
                      extend: "pdfHtml5",
                      className: "btn-sm"
                    },
                    {
                      extend: "print",
                      className: "btn-sm"
                    },
                  ],
                  responsive: false,
                  sort:false
                });
              }
            };

            TableManageButtons = function() {
              "use strict";
              return {
                init: function() {
                  handleDataTableButtons();
                }
              };
            }();

            $('#datatable').dataTable();

            $('#datatable-keytable').DataTable({
              keys: true
            });

            $('#datatable-responsive').DataTable();

            $('#datatable-scroller').DataTable({
              ajax: "js/datatables/json/scroller-demo.json",
              deferRender: true,
              scrollY: 380,
              scrollCollapse: true,
              scroller: true
            });

            $('#datatable-fixed-header').DataTable({
              fixedHeader: true
            });

            var $datatable = $('#datatable-checkbox');

            $datatable.dataTable({
              'order': [[ 1, 'asc' ]],
              'columnDefs': [
                { orderable: false, targets: [0] }
              ]
            });
            $datatable.on('draw.dt', function() {
              $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green'
              });
            });

            TableManageButtons.init();
          });
        </script>
  </body>
</html>