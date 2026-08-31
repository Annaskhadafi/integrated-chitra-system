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
            <?php if($name!=""){ ?>
              <div class="right_col" role="main">
                <div class="clearfix"></div>
                <div class="row" style="margin-top:0px">            
                  <?php include "koneksi.php";?>
                  <div class="x_panel">
                    <div class="x_title">
                        <h2>Customer Fleetlist</h2>
                        <div class="clearfix"></div>
                    </div>               
                     <div class="x_content">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="row">                    
                          <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead style="background:#f5f5f5;">
                                <tr>
                                  <th>Customer</th>
                                  <th>Size</th>
                                  <th>Unit Qty</th>
                                  <th>Tire forecast</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $perintah = mysqli_query($koneksi2,"SELECT * from customer");
                                $no=1;
                                while ($data = mysqli_fetch_array($perintah)) { ?>
                                <tr>
                                  <td><?php echo $data['customer'];?></td>
                                  <td><?php echo $data['size'];?></td>
                                  <td><?php echo $data['unit_qty'];?></td>
                                  <td><?php echo $data['forecast_tire'];?></td>
                                </tr>
                                <?php $no++; } ?>
                            </tbody>
                          </table>
                        </div>  
                      </div>
                    </div>
                  </div>
                </div>
              </div>          
            <?php }
            else {}?>
      </div>
    </div>
    <footer>
      <div class="pull-right">
       
      </div>
      <div class="clearfix"></div>
    </footer>

    <script src="codebase/dhtmlx.js"></script>
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
    <!-- ECharts -->
    <script src="../vendors/echarts/dist/echarts.min.js"></script>
    <script src="../vendors/echarts/map/js/world.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
  </body>
</html>