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
                  <div class="x_title"><h3>User</h3></div>
                  <div class="x_title">
                    <form  class="form-inline" role="form" action="tambahuser.php" method="post">
                      <input class="form-control" type ="text" name ="nama" placeholder="User name"/> 
                      <input class="form-control" type ="number" name ="sn" placeholder="SN"/>  
                      <input class="form-control" type ="text" name ="password" placeholder="Password"/>    
                      <select class="form-control" name="level">
                        <option value="">Level</option>
                        <option value="1">Admin</option>
                        <option value="2">Repairman</option>
                        <option value="3">Quality Control</option>
                        <option value="4">Coordinator</option>
                      </select>  
                      <button type="submit" value="submit" class="btn btn-success"> Submit</button>
                    </form>
                  </div>
                  <div class="pre-scrollable">
                    <table class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>SN</th>
                          <th>Password</th>
                          <th>Nama</th>
                          <th>Level</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                              <?php 
                              $perintah = mysqli_query($koneksi3, "SELECT * from user");
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['sn'];?></td>
                                <td><?php echo $data['password'];?></td>
                                <td><?php echo $data['nama'];?></td>
                                <td>
                                  <?php 
                                    if($data['level']==1){echo "Admin";} 
                                    elseif ($data['level']==2) {echo "Repairman";}
                                    elseif ($data['level']==3) {echo "Quality control";}
                                    elseif ($data['level']==4) {echo "Koordinator";}
                                  ?>
                                </td>
                                <td>
                                <a href="hapususer.php?iduser=<?php echo $data['id_user']; ?>"onclick="javascript: return confirm('Delete [<?php echo $data['nama']; ?>] from System User ?')">Delete</a>
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
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title"><h3>Customer</h3></div>
                  <div class="x_title">
                    <form  class="form-inline" role="form" action="tambahcustomer.php" method="post">
                      <input class="form-control" type ="text" name ="namacustomer" placeholder="Nama Customer"/>
                      <button type="submit" value="submit" class="btn btn-success"> Submit</button>
                    </form>
                  </div>
                  <div class="pre-scrollable">
                    <table class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                              <th>Nama Customer</th>
                              <th></th>
                        </tr>
                      </thead>
                      <tbody>
                              <?php 
                              $perintah = mysqli_query($koneksi3, "SELECT * from customer_data");
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['cust_name'];?></td>
                                <td>
                                <a href="hapuscustomer.php?idcust=<?php echo $data['id_cust']; ?>"onclick="javascript: return confirm('Delete [<?php echo $data['cust_name']; ?>] from system ?')">Delete</a>
                                </td>
                              </tr>    
                              <?php $no++; } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> 
            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title"><h3>Site</h3></div>
                  <div class="x_title">
                    <form  class="form-inline" role="form" action="tambahsite.php" method="post">
                      <input class="form-control" type ="text" name ="site" placeholder="Site Project"/>    
                      <select class="form-control" name="customer">
                        <option value="">Customer</option>
                        <?php 
                          $perintah = mysqli_query($koneksi3, "SELECT * from customer_data");
                          while ($data = mysqli_fetch_array($perintah)) { ?>
                            <option value="<?php echo $data['id_cust'];?>"><?php echo $data['cust_name'];?></option>
                        <?php } ?>
                      </select> 
                      <input class="form-control" type ="Number" name ="idsap" placeholder="ID SAP"/>   
                      <button type="submit" value="submit" class="btn btn-success"> Submit</button>
                    </form>
                  </div>
                  <div class="pre-scrollable">
                    <table class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Site Project</th>
                              <th>Customer</th>
                              <th>ID SAP</th>
                              <th></th>
                            </tr>
                      </thead>
                      <tbody>
                              <?php 
                              $perintah = mysqli_query($koneksi3, "SELECT * from customer a,customer_data b where a.nama_customer=b.id_cust");
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['site'];?></td>
                                <td><?php echo $data['cust_name'];?></td>
                                <td><?php echo $data['idsap'];?></td>
                                <td>
                                <a href="hapussite.php?idsite=<?php echo $data['id_customer']; ?>"onclick="javascript: return confirm('Delete [<?php echo $data['site']; ?>] from system ?')">Delete</a>
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
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title"><h3>Inventory min stock</h3></div>                  
                  <div class="pre-scrollable" style="height: 240px;">
                    <table class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>Material</th>
                          <th>Qty</th>
                          <th>Min</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                              <?php 
                              $perintah = mysqli_query($koneksi3, "SELECT * from mat_inventory");
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['desc'];?></td>
                                <td><?php echo round($data['inv_qty']);?></td>
                                <td><?php echo $data['min'];?></td>                                
                                <td><a href="#" class="edit" data-idmat="<?php echo  $data['id_inv']; ?>">Edit </a></td>
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
        <?php } ?>
      </div>
    </div>
    <!-- modal edit data tire inventory -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
          </div>
        </div>
      </div>
    </div>
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
    <!-- JS pop up modal -->
    <script>
     
        $(function(){
              $(document).on('click','.edit',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditmin.php',
                      {idmat:$(this).attr('data-idmat')},
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
