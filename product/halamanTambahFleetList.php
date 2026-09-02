<?php
include_once "koneksi.php";
include_once "auth_check.php";
include_once "csrf.php";
require_user_levels($koneksi, array(1, 910)); // Admin & Super Admin
?>
<!DOCTYPE html>
<html lang="en">
<?php include'header.php';?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
        <a href="halamanDataMaster.php" class="site_title"></a>
          <!-- <div class="left_col scroll-view"> -->
            <div class="navbar nav_title" style="border: 0;">
              <!-- <a href="halamanDataMaster.php" class="site_title">
                <span>Chitra Paratama</span>
              </a> -->
            </div>
            <div class="clearfix"></div>
            <div class="profile">
              <div class="profile_info">
                <?php 
                  $username = $_SESSION['username'];
                  $stmt = mysqli_prepare($koneksi, "SELECT * FROM user a, department b WHERE a.username = ? AND a.department = b.id_dept");
                  mysqli_stmt_bind_param($stmt, "s", $username);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  $user = mysqli_fetch_array($result);
                  $dept = $user ? $user['department'] : null;
                  $name = $user ? $user['name'] : '';
                  mysqli_stmt_close($stmt);
                ?>
                <h2>Technical <br><?php echo $name;?></h2>
              </div>
            </div>
            <br/>
            <?php include('template_menu.php');?>
          <!-- </div> -->
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
                    <h2>Add Fleetlist <?php echo $name ?></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">

                        <form  class="form-inline" role="form" action="tambahData.php" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="tab_id" value="<?= enforce_single_tab(); ?>">

                            <input class="form-control" type = "hidden" name ="item" value="fleet">  

                            <select class="form-control" name="customer">
                             <option value="">Customer/Site</option>     
                              <?php 
                                $perintah=mysqli_query($koneksi2,"SELECT * from site_master a,customer_master b where a.id_customer=b.id_customer_master order by customer,site");
                                while ($data = mysqli_fetch_array($perintah)) {?>    
                                  <option value='<?php echo $data['id_site_master']; ?>'><?php echo $data['customer'];?>/ <?php echo $data['site'];?></option>     
                              <?php }?>         
                            </select>

                            <select class="form-control" name="unit">
                             <option value="">Manufacture/Model</option>     
                              <?php 
                                $perintah=mysqli_query($koneksi2,"SELECT id_unit_master,unit_manufacture,model from unit_master order by unit_manufacture,model");
                                while ($data = mysqli_fetch_array($perintah)) {?>    
                                  <option value='<?php echo  $data['id_unit_master']; ?>'><?php echo $data['unit_manufacture'];?>/<?php echo $data['model'];?></option>     
                              <?php }?>         
                            </select>

                            <input style="width:100px;" class="form-control" type = "number" name = "qty" placeholder="Quantity">                        

                            <input style="width:130px;" class="form-control" type = "number" name = "rotasi" placeholder="Est serv / year">

                            <input style="width:130px;" class="form-control" type = "number" name = "scrap" placeholder="Est perf live">                                              

                            <select class="form-control" name="segment">
                             <option value=''>Segment</option>  
                             <option value='mining'>Mining</option> 
                             <option value='infra'>Infra</option>           
                            </select>  
                            <input class="form-control" value="<?php echo $name ?>" type="hidden" name="name"/>

                            <br>
                            <br>
                            <button type="submit" value="submit" class="btn btn-success"><span>Submit</button>
                            
                        </form> 

                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Recent Update</h1>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Id</th>
                              <th>Customer</th>
                              <th>Site</th>
                              <th>Location</th>
                              <th>Kabupaten</th>
                              <th>Model</th>
                              <th>Size</th>
                              <th>Unit qty</th>
                              <th>Est serv/year</th>
                              <th>Est perf life</th>
                              <th>Segment</th>
                              <th>Update</th>
                              <th>Update By</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi2,"SELECT * from fleet_list a,unit_master b,site_master c,customer_master d where a.id_site=c.id_site_master and c.id_customer=d.id_customer_master and a.id_unit=b.id_unit_master order by id_fleet_list desc limit 10");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { 
                              $totalQuantity=$data['unit_qty']*$data['tire_quantity'];
                              $annualConsumption=($data['rotasi']/$data['scrap'])*$data['tire_quantity'];
                              $forecast=$annualConsumption*$data['unit_qty'];?>
                            <tr>
                              <td><?php echo $data['id_fleet_list'];?></td>
                              <td><?php echo $data['customer'];?></td>
                              <td><?php echo $data['site'];?></td>
                              <td><?php echo $data['location'];?></td>
                              <td><?php echo $data['kabupaten'];?></td>
                              <td><?php echo $data['model'];?></td>
                              <td><?php echo $data['tire_size'];?></td>
                              <td><?php echo $data['unit_qty'];?></td>
                              <td><?php echo $data['rotasi'];?></td>
                              <td><?php echo $data['scrap'];?></td>
                              <td><?php echo $data['segment'];?></td>
                              <td><?php echo $data['date'];?></td>
                              <td><?php echo isset($data['name']) ? $data['name'] : ''; ?></td>
                              <!-- <td style="width:70px"><a href="#" class="editinvent" data-idfleet="<?php echo $data['id_fleet_list']; ?>"data-item="fleet">Edit</a> -->
                              <td style="width:70px"><a href="#" class="editinvent" data-idfleet="<?php echo $data['id_fleet_list']; ?>"data-item="fleet" data-user="<?php echo $name; ?>">Edit</a>
                            </td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Edit fleet list</h4>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- footer content -->
                      </div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    </div>
                <!-- footer content -->
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
    <script>
      $(function(){
        $(document).on('click','.editinvent',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modalEditData.php',
          {idfleet:$(this).attr('data-idfleet'),
           item:$(this).attr('data-item'),name:$(this).attr('data-user')
          },
          function(html){
            $(".modal-body").html(html);
              }   
          );
        });
      });
    </script>
    <script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });
    </script>
  </body>
</html>