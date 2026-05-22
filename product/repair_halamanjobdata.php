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
          // include "koneksi.php";
          include "template_menu.php";
        ?>
        
        <?php if($name!=""){ ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title">
                    <h3>Repair job list</h3>   
                  </div>   
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>No</th>
                          <th>Work order</th>
                          <th>job</th>
                          <th>Date</th>
                          <th>Time (Min)</th>
                          <th>Person</th>
                          <th>Note</th>
                          <th>Proses-</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $perintah = mysqli_query($koneksi3, "SELECT DISTINCT wo, job, date, time, person, note, proseske FROM job;");
                        $no=1;
                        while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $no;?> </td>
                              <td><?php echo $data['wo'];?> </td>
                              <td><?php echo $data['job'];?> </td>
                              <td><?php echo $data['date'];?> </td>
                              <td><?php echo $data['time'];?></td>
                              <td><?php echo $data['person'];?> </td>
                              <td><?php echo $data['note'];?></td>
                              <td><?php echo $data['proseske'];?></td>
                            </tr>    
                          <?php $no++; } ?>
                      </tbody>
                  </table>               
                    <!-- form untuk melakukan input tire baru -->
                </div>
              </div>
            </div> 
          </div>
          <div class="clearfix"></div>
        </div>
        <?php } ?>
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
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"></script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

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
              order: [[ 0, "desc" ]]
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
          'order': [[ 0, "desc" ]],
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
