<?php session_start();?> <!-- tangkap nilai 'username' dan 'password' dari proseslogin -->
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
                <div class="x_content">
                    <?php 
                        if($level==1) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <form class="form-inline" role="form" action="tambahDataMaster.php" method="post">
                                <input class="form-control" type = "hidden" name = "item" value="dealer">
                                <input class="form-control" type = "text" name = "name" placeholder="Dealer Name" required>
                                <br>
                                <br>
                                <button type="submit" value="submit" class="btn btn-success"><span>Submit</span></button>
                              </form>
                            </div>
                            <?php 
                        } ?>
                    <div class="col-md-12 col-sm-12 col-xs-12"> 
                        <div class="x_title">
                          <h2>Service Company</h2>
                          <div class="clearfix"></div>
                        </div>
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>#</th>
                              <th>Service Company</th>
                              <th>Website</th>
                              <th>Instagram</th>
                              <th>LinkedIn</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi2,"SELECT * from unit_dealer");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $data['id_unit_dealer'];?></td>
                              <td><?php echo $data['unit_dealer'];?></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td style="width:70px">
                                    <?php if($level==1) {?>
                                        <a href="#" class="editinvent" data-id="<?php echo $data['id_unit_dealer']; ?>"data-item="dealer">Edit</a>
                                    <?php } ?>
                                </td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                        <!-- footer content -->
                      </div>
                </div>
              </div>
              <div class="x_panel">
                <div class="x_content">
                    <?php 
                        if($level==1) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <form class="form-inline" role="form" action="tambahDataMaster.php" method="post">
                                <input class="form-control" type = "hidden" name = "item" value="dealer&machine">
                              	<select name="dealer" class="form-control" required>
                                	<option value="" selected disabled>Service company</option>
                                    <?php
                                        $perintah = mysqli_query($koneksi2,"SELECT * FROM unit_dealer");
                                        while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value="<?php echo $data['id_unit_dealer'];?>"><?php echo $data['unit_dealer'];?></option>
                                            <?php    
                                        }
                                    ?>
                              	</select>
                              	<select name="unit" class="form-control" required>
                                	<option value="" selected disabled>Machine model</option>
                                    <?php
                                        $perintah = mysqli_query($koneksi2,"SELECT unit_manufacture FROM unit_master GROUP BY unit_manufacture ORDER BY unit_manufacture");
                                        while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value="<?php echo $data['unit_manufacture'];?>"><?php echo $data['unit_manufacture'];?></option>
                                            <?php    
                                        }
                                    ?>
                              	</select>
                                <br>
                                <br>
                                <button type="submit" value="submit" class="btn btn-success"><span>Submit</span></button>
                              </form>
                            </div>
                            <?php 
                        } ?>
                </div>
                <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12"> 
                        <div class="x_title">
                          <h2>Service Company & Machine</h2>
                          <div class="clearfix"></div>
                        </div>
                        <table id="datatable-buttons3" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>#</th>
                              <th>Service Company</th>
                              <th>Machine 1</th>
                              <th>Machine 2</th>
                              <th>Machine 3</th>
                              <th>Machine 4</th>
                              <th>Machine 5</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                                $dealer=array();
                                $unit=array();
                                $perintah = mysqli_query($koneksi2,"SELECT unit_dealer,GROUP_CONCAT(unit) AS unit FROM unit_dealer_machine GROUP BY unit_dealer");
                                while ($data = mysqli_fetch_array($perintah)) {
                                    $unitdealer=$data['unit_dealer'];
                                    $dealer[$unitdealer]=$data['unit'];
                                }
                                // print_r($dealer);
                                
                                $perintah = mysqli_query($koneksi2,"SELECT * from unit_dealer");
                                $no=1;
                                while ($data = mysqli_fetch_array($perintah)) { 
                                $iddealer=$data['id_unit_dealer'];
                            ?>
                            <tr>
                              <td><?php echo $iddealer; ?></td>
                              <td><?php echo $data['unit_dealer'];?></td>
                              <?php 
                                $kumpulanunit=isset($dealer[$iddealer]) ? $dealer[$iddealer] : '';
                                $pecah = explode(",", $kumpulanunit);  
                              ?>
                              <td><?php echo isset($pecah[0]) ? $pecah[0] : ''; ?></td>
                              <td><?php echo isset($pecah[1]) ? $pecah[1] : ''; ?></td>
                              <td><?php echo isset($pecah[2]) ? $pecah[2] : ''; ?></td>
                              <td><?php echo isset($pecah[3]) ? $pecah[3] : ''; ?></td>
                              <td><?php echo isset($pecah[4]) ? $pecah[4] : ''; ?></td>
                              <td style="width:70px">
                                    <?php if($level==1) {?>
                                        <a href="#" class="editinvent" data-id="<?php echo $iddealer;?>"data-item="dealermachine">Edit</a>
                                    <?php } ?>
                                </td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                        <!-- footer content -->
                      </div>
                </div>
              </div>
              <div class="x_panel">
                <div class="x_content">
                        <?php 
                        if($level==1) { ?>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <form class="form-inline" role="form" action="tambahDataMaster.php" method="post">
                                <input class="form-control" type = "hidden" name = "item" value="dealercontact">
                                <select class="form-control" name="company" required>
                                 <option value="">Company</option>     
                                  <?php 
                                    $perintah=mysqli_query($koneksi2,"SELECT unit_dealer,id_unit_dealer FROM unit_dealer ORDER BY unit_dealer");
                                    while ($data = mysqli_fetch_array($perintah)) {?>    
                                      <option value=<?php echo  $data['id_unit_dealer']; ?>><?php echo $data['unit_dealer'];?></option>     
                                  <?php }?>         
                                </select>
                                <input class="form-control" type = "text" name = "alamat" placeholder="Address" required>
                                <input class="form-control" type = "text" name = "nama" placeholder="Name" required>
                                <input class="form-control" type = "text" name = "jabatan" placeholder="Position" required>
                                <input class="form-control" type = "text" name = "contact" data-inputmask="'mask' : '999999999999'"placeholder="Contact number" required>
                                <input class="form-control" type = "text" name = "email" placeholder="Email" required>
                                <br>
                                <br>
                                <button type="submit" value="submit" class="btn btn-success"><span>Submit</span></button>
                              </form>
                            </div> 
                            <?php 
                        } 
                        ?>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_title">
                          <h2>Service Company Contact</h2>
                          <div class="clearfix"></div>
                        </div> 
                        <table id="datatable-buttons2" class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>#</th>
                              <th>Company</th>
                              <th>Address</th>
                              <th>Name</th>
                              <th>Position</th>
                              <th>Contact</th>
                              <th>Email</th>
                              <th></th>	
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            $perintah = mysqli_query($koneksi2,"SELECT * from unit_dealer_contact a,unit_dealer b where a.unit_dealer=b.id_unit_dealer");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                              <td><?php echo $data['id_unit_dealer_contact'];?></td>
                              <td><?php echo $data['unit_dealer'];?></td>
                              <td><?php echo $data['address'];?></td>
                              <td><?php echo $data['pic'];?></td>
                              <td><?php echo $data['jabatan'];?></td>
                              <td><?php echo $data['dealer_contact'];?></td>
                              <td><?php echo $data['dealer_email'];?></td>
                              <td style="width:70px">
                                    <?php if($level==1) {?>
                                        <a href="#" class="editinvent" data-id="<?php echo $data['id_unit_dealer_contact']; ?>"data-item="dealercontact">Edit</a>
                                    <?php } ?>
                                </td>
                            </tr><?php $no++; } ?>
                          </tbody>
                        </table>
                        <!-- footer content -->
                      </div>
                </div>
              </div>
            </div>
          </div>          
        <?php }
          else {}
        ?>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Edit service company master</h4>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <!-- jquery.inputmask -->
    <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
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
            var handleDataTableButtons2 = function() {
              if ($("#datatable-buttons2").length) {
                $("#datatable-buttons2").DataTable({
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
                  responsive: false
                });
              }
            };
            var handleDataTableButtons3 = function() {
              if ($("#datatable-buttons3").length) {
                $("#datatable-buttons3").DataTable({
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
                  responsive: false
                });
              }
            };

            TableManageButtons = function() {
              "use strict";
              return {
                init: function() {
                  handleDataTableButtons();
                  handleDataTableButtons2();
                  handleDataTableButtons3();
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
          {id:$(this).attr('data-id'),
           item:$(this).attr('data-item')},
          function(html){
            $(".modal-body").html(html);
              }   
          );
        });
      });
    </script>
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });
    </script>
  </body>
</html>