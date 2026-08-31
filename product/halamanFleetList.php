<?php
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(1, 4, 8));
?>
<!DOCTYPE html>
<?php include 'header.php';?>
<html lang="en">
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include('template_menu.php');?>
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
                        <?php echo date("l");echo date(", Y-m-d");?>
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
                    <h2>Fleet List</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                        // hanya admin bisa edit
                        $isAdmin = ($level == 1);
                        
                        // ambil data mining_company
                        $miningcompany = [];
                        $perintah = mysqli_query($koneksi2, "SELECT id_mining, mining_company FROM mining_company");
                        while ($data = mysqli_fetch_assoc($perintah)) {
                            $miningcompany[$data['id_mining']] = $data['mining_company'];
                        }
                        
                        // ambil data utama
                        $query = "
                        SELECT a.*, b.unit_manufacture, b.model, b.tire_size, b.tire_quantity,
                               c.site, c.location, c.kabupaten, c.status, c.mining_company, d.customer,
                               (a.unit_qty*b.tire_quantity) AS totaltire,
                               ROUND(((a.rotasi/a.scrap)*b.tire_quantity),0) AS annual,
                               (ROUND(((a.rotasi/a.scrap)*b.tire_quantity),0)*a.unit_qty) AS forecast
                        FROM fleet_list a
                        JOIN unit_master b ON a.id_unit=b.id_unit_master
                        JOIN site_master c ON a.id_site=c.id_site_master
                        JOIN customer_master d ON c.id_customer=d.id_customer_master
                        ";
                        $perintah = mysqli_query($koneksi2, $query);
                        ?>
                        
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Id</th>
                              <th>Customer</th>
                              <th>Site</th>
                              <th>Status</th>
                              <th>Area</th>
                              <th>Province</th>
                              <th>Kabupaten</th>
                              <th>Brand</th>
                              <th>Model</th>
                              <th>Size</th>
                              <th>Tire qty</th>
                              <th>Unit qty</th>
                              <th>Total tire qty</th>
                              <th>Est serv/year</th>
                              <th>Est perf life</th>
                              <th>Segment</th>
                              <th>Last Update</th>
                              <th>Annual consumption /unit</th>
                              <th>Tire forecast</th>
                              <th>Update By</th>
                              <?php if ($isAdmin) echo "<th></th>"; ?>
                            </tr>
                          </thead>
                          <tbody>
                            <?php while ($data = mysqli_fetch_assoc($perintah)) { ?>
                              <tr>
                                <td><?= $data['id_fleet_list'] ?></td>
                                <td><?= $data['customer'] ?></td>
                                <td><?= $data['site'] ?></td>
                                <td><?= $data['status'] ?></td>
                                <td><?= isset($miningcompany[$data['mining_company']]) ? $miningcompany[$data['mining_company']] : '' ?></td>
                                <td><?= $data['location'] ?></td>
                                <td><?= $data['kabupaten'] ?></td>
                                <td><?= $data['unit_manufacture'] ?></td>
                                <td><?= $data['model'] ?></td>
                                <td><?= $data['tire_size'] ?></td>
                                <td><?= $data['tire_quantity'] ?></td>
                                <td><?= $data['unit_qty'] ?></td>
                                <td><?= $data['totaltire'] ?></td>
                                <td><?= $data['rotasi'] ?></td>
                                <td><?= $data['scrap'] ?></td>
                                <td><?= $data['segment'] ?></td>
                                <td><?= $data['date'] ?></td>
                                <td><?= number_format($data['annual'], 0) ?></td>
                                <td><?= number_format($data['forecast'], 0) ?></td>
                                <td><?= $data['updateby'] ?></td>
                                <?php if ($isAdmin) { ?>
                                  <td style="width:70px">
                                    <a href="#" class="editinvent" 
                                       data-idfleet="<?= $data['id_fleet_list'] ?>" 
                                       data-item="fleet" 
                                       data-user="<?= $name ?>">Edit</a>
                                  </td>
                                <?php } ?>
                              </tr>
                            <?php } ?>
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
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
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
                  responsive: true
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
