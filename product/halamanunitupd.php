<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "sectionhead.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "koneksi.php";
          include "template_menu.php";
        ?>
        <!-- page content -->        
        <div class="right_col" role="main">
            <div class="clearfix"></div>
              <div class="row" style="margin-top:50px">  
              <h3>Unit Onsite Update</h3>    
              <div class="col-md-12 col-sm-8 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <!-- form input data unit saesuai dengan spesifikasi unit yang telah tersedia -->
                  <form  class="form-inline" role="form" action="tambahunitsite.php" method="post">
                  <input class="form-control" type = "text" name ="unitnumber" placeholder="Unit Number"/>
                  <select class="form-control" name="unit">  
                  <option value="">Unit Model</option>
                  <?php 
                  //select seluruh data unit dari tabel unit
                  $perintah=mysqli_query($sambung, "SELECT * from unit ORDER BY unit ASC");
                  $no =1;
                  while ($data = mysqli_fetch_array($perintah)) {?>
                  <option value=<?php echo  $data['id_unit']; ?>><?php echo $data['unit']; ?></option>
                  <?php $no++;}?> 
                  </select>               
                  <input class="form-control" type = "text" name ="hm" placeholder="HM Unit"/>
                  <input class="form-control" value= "<?php echo $idsite;?>" type="hidden" name="site"/>
                  <input class="form-control" value= "Active" type="hidden" name="status"/>
                  <button type="submit" value="submit" class="btn btn-success "></span> Submit</button>
                  </form>                   
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="col-md-6 col-sm-8 col-xs-12">
                    <button type="button"><a href="#" class="tambahsmu">Bulk SMU update</a></button>                                                                
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>Unit Number</th>
                          <th>Unit Model</th>
                          <th>SMU</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        //form input data unit saesuai dengan spesifikasi unit yang telah tersedia
                        $perintah = mysqli_query($sambung, "SELECT * FROM unit_site as a WHERE site=$idsite order by unit_number");
                        $no=1;
                        while ($data = mysqli_fetch_array($perintah)) { 
                          $idunitmaster=$data['unit'];
                          $perintah1 = mysqli_query($sambung, "SELECT * FROM unit WHERE id_unit=$idunitmaster");
                          $data1 = mysqli_fetch_array($perintah1);
                          ?>
                          <tr>
                          <td><?php echo $data['unit_number'];?></td>
                          <td><?php echo $data1['unit'];?></td>
                          <td><?php echo $data['hm'];?></td>
                          <td><?php $status=$data['status'];
                            echo $status;?>
                          </td>
                          <td>
                            <?php
                              if ($status=='Active'){ ?>
                                <a href="#" class="editunitsite" data-idunitsite="<?php echo  $data['id_unit_site']; ?>">Edit</a>
                              <?php }
                              else {}
                              ?>
                          </td>
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
            </div>
          </div>
        <!-- /page content -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Unit Update</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">SMU update</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Chitra Tire System by Chitra Paratama @2017
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
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
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <script>
      $(function(){
                $(document).on('click','.tambahsmu',function(e){
                    e.preventDefault();
                    $("#myModal2").modal('show');
                    $.post('modaltambahsmu.php',
                        function(html){
                            $(".modal-body").html(html);
                        }   
                    );
                });
      });
    </script>
    <script>
      $(function(){
                $(document).on('click','.editunitsite',function(e){
                    e.preventDefault();
                    $("#myModal").modal('show');
                    $.post('modaleditunitsite.php',
                        {idunitsite:$(this).attr('data-idunitsite')},
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