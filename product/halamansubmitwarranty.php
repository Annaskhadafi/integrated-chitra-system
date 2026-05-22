<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <?php include "template_menu.php";?>          
          <!--tampilkan alert jika data tidak semua diinput-->
          <!-- page content -->
          <div class="right_col" role="main">
          <?php 
            if($name!=""){ 
                ?>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        <div class="x_panel">
                        <div class="x_content">                   
                            <div class="clearfix"></div>
                            <div class="col-md-12 left-margin">
                              <form class="form-horizontal form-label-left" action="tambahwarranty.php" method="post"> 
                                <div class="x_panel">
                                  <div class="x_title">
                                    <h2>Tire Spesification</h2>
                                    <div class="clearfix"></div>
                                  </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                      <label>Tire serial number</label>
                                      <input type="text" class="form-control" name="sn_tire" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Tire size</label>
                                      <input type="text" class="form-control" name="size" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Tire brand</label>
                                      <input type="text" class="form-control" name="brand" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Tire pattern</label>
                                      <input type="text" class="form-control" name="tire_desc" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Tire compound</label>
                                      <input type="text" class="form-control" name="compound_tire" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Original tread depth</label>
                                      <input type="number" class="form-control" name="otd" required>
                                    </div>
                                </div> 
                                <div class="x_panel">
                                  <div class="x_title">
                                    <p class="font-gray-dark"><h2>Tire Condition</h2></p>
                                    <div class="clearfix"></div>
                                  </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                      <label>Tire owner</label>
                                      <select class="form-control" name="costumer" required>
                                        <option value="">Customer</option>
                                        <?php 
                                            $perintah=mysqli_query($koneksi2,"SELECT id_customer_master,customer from customer_master order by customer");
                                            while ($data = mysqli_fetch_array($perintah)) {?>    
                                              <option value=<?php echo  $data['id_customer_master']; ?>><?php echo  $data['customer'];?></option>     
                                        <?php }?>
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label>Site project</label>
                                      <input type="text" class="form-control" name="site" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Tire price</label>
                                      <input type="number" class="form-control" name="price" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Owner target lifetime</label>
                                      <input type="number" class="form-control" name="target" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Actual lifetime</label>
                                      <input type="number" class="form-control" name="lifetime" required>
                                    </div>
                                    <div class="form-group">
                                      <label>Remain tread depth</label>
                                      <input type="number" class="form-control" name="rtd" required>
                                    </div>
                                </div>
                                <div class="x_panel">
                                  <div class="x_title">
                                    <p class="font-gray-dark"><h2>Tire Failure</h2></p>
                                    <div class="clearfix"></div>
                                  </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                      <label>Tire injury</label>
                                        <select class="form-control" name="injury" required> 
                                          <!-- form select untuk memilih kategory injury yang tersedia --> 
                                          <option value="">Injury</option>
                                          <option value="Tread Separation">Tread Separation</option>
                                          <option value="Worn Out">Worn Out</option>
                                          <option value="Turn Up Ply Separation">Turn Up Ply Separation</option>
                                          <option value="Bulging & Tread Separation">Bulging & Tread Separation</option>
                                          <option value="Bulging">Bulging</option>
                                          <option value="Bulging to Sidewall Separation">Bulging to Sidewall Separation</option>
                                          <option value="Tread Lifting & Sidewall Separation">Tread Lifting & Sidewall Separation</option>
                                          <option value="Impact">Impact</option>
                                          <option value="Sidewall Cut">Sidewall Cut</option>
                                          <option value="Sidewall Separation">Sidewall Separation</option>
                                          <option value="Tread Lifting & Sidewall Separation">Tread Lifting & Sidewall Separation</option>
                                          <option value="Shoulder Separation">Shoulder Separation</option>
                                          <option value="Bulging on Sidewall">Bulging on Sidewall</option>
                                          <option value="Sidewall Impact">Sidewall Impact</option>
                                          <option value="Tread Cut">Tread Cut</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label>Failure area</label>
                                        <select class="form-control" name="area" required> 
                                          <!-- form select untuk memilih kategory injury yang tersedia --> 
                                          <option value="">Area</option>
                                          <option value="Tread">Tread</option>
                                          <option value="Shoulder">Shoulder</option>
                                          <option value="Sidewall">Sidewall</option>
                                          <option value="Chaffer">Chaffer</option>
                                          <option value="Bulging">Bulging</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label>Probability Cause</label>
                                        <select class="form-control" name="prob_cause" required> 
                                          <!-- form select untuk memilih kategory probable cause yang tersedia --> 
                                          <option value="">Probable Cause</option>
                                          <option value="Material Issue">Material Issue</option>
                                          <option value="ROT & Speed not recommendation">ROT & Speed not recommendation</option>
                                          <option value="Wear Rate">Wear Rate</option>
                                          <option value="Condition Site">Condition Site Undulation</option>
                                          <option value="Operation">Operation</option>
                                          <option value="Cut From Material">Cut From Material</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label>Reported date</label>
                                        <input class="form-control" type="date" name="date_doc" required>
                                    </div>
                                </div>
                                <button type="submit" value="submit" class="btn btn-success"> Submit</button>
                              </form>
                          </div>
                        </div>  
                    </div>
                </div>
                <?php 
            }
            ?>
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
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
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
              responsive: true,
              order: true[[ 0, "asc" ]]
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
          { orderable: true, targets: [0] }
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
    <!-- Mengarahkan ke halaman modal (modalEditMaster-->
    <script>
      $(function(){
        $(document).on('click','.editinvent',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modalEditDataMaster.php',
          {idunit:$(this).attr('data-idunit'),
           item:$(this).attr('data-item')},
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