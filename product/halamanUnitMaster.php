<?php
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(1, 4, 8));
?> <!-- tangkap nilai 'username' dan 'password' dari proseslogin -->
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
                  include_once "koneksi.php";
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
                    <h2>Unit Data Master</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php if($level==1) {?>
                      <form  class="form-inline" role="form" action="tambahDataMaster.php" method="post">
                        <input class="form-control" type = "hidden" name = "item" value="unit">
                        <input class="form-control" type = "text" name = "manufacture" placeholder="Manufacture">
                        <input style="width:160px;" class="form-control" type = "text" name = "model" placeholder="Model">
                        <select class="form-control" name="size">
                         <option value="">Size</option>     
                          <?php 
                            $perintah=mysqli_query($koneksi2,"SELECT size from tire_master group by size order by size");
                            while ($data = mysqli_fetch_array($perintah)) {?>    
                              <option value=<?php echo  $data['size']; ?>><?php echo  $data['size'];?></option>     
                          <?php }?>         
                        </select>
                        <input class="form-control" type = "number" name = "quantity" placeholder="Tire quantity">
                        <select class="form-control" name="category">
                         <option value="">Category</option>
                         <option value="RDT">RDT</option>
                         <option value="ADT">ADT</option>
                         <option value="Suppeq">Suppeq</option>
                         <option value="T&B">T&B</option>     
                         <option value="Drilling">Drilling</option>          
                        </select>
                        <br>
                        <br>
                        <button type="submit" value="submit" class="btn btn-success"><span>Submit</button>
                      </form> 
                      <?php } ?>
                    </div>
                </div>
                <div class="x_content">
                      <div class="col-md-12 col-sm-12 col-xs-12"> 
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Manufacture</th>
                              <th>Model</th>
                              <th>Category</th>
                              <th>Tire size</th>
                              <th>Tire qty</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi2,"SELECT * from unit_master");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $data['unit_manufacture'];?></td>
                              <td><?php echo $data['model'];?></td>
                              <td><?php echo $data['category'];?></td>
                              <td><?php echo $data['tire_size'];?></td>
                              <td><?php echo $data['tire_quantity'];?></td>
                              <td style="width:70px">
                                    <?php if($level==1) {?>
                                        <a href="#" class="editinvent" data-idunit="<?php echo $data['id_unit_master']; ?>"data-item="unit">Edit</a>
                                    <?php } ?>
                                </td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Edit unit master</h4>
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