<?php session_start();?> <!-- tangkap nilai 'username' dan 'password' dari proseslogin -->
<!DOCTYPE html>
<html lang="en">
<?php include'header.php';?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
            <a href="halamanDataMaster.php" class="site_title"></a>
            <div class="navbar nav_title" style="border: 0;"></div>
            <div class="clearfix"></div>
            <div class="profile">
              <div class="profile_info">
                <?php 
                  include "koneksi.php";
                  $username=$_SESSION['username'];                
                  $password=$_SESSION['password'];  
                  $perintah = mysqli_query($koneksi,"SELECT * from user a,department b where a.username='$username' and a.password='$password' and a.department=b.id_dept");
                  $user = mysqli_fetch_array($perintah);
                  $dept =$user['department'];
                  $name =$user['name'];
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
        <?php 
        if($name!=""){?>      
            <div class="right_col" role="main">
                <div class="clearfix"></div>
                <div class="row" style="margin-top:0px">
                  <div class="x_panel">
                    <div class="x_title">
                        <h2>Mining Contractor Site Project Master</h2>
                        <div class="clearfix"></div>
                </div>
                <?php if($level=='1'&&$idsection=='1') { ?>
                    <div class="x_content">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form  class="form-inline" role="form" action="tambahDataMaster.php" method="post">
                                <input class="form-control" type = "hidden" name ="item" value="site">
                                <select class="form-control" name="idcustomer" required>  
                                 <option value="">Customer</option>     
                                  <?php 
                                    $perintah=mysqli_query($koneksi2,"SELECT id_customer_master,customer from customer_master order by customer");
                                    while ($data = mysqli_fetch_array($perintah)) {?>    
                                      <option value=<?php echo  $data['id_customer_master']; ?>><?php echo  $data['customer'];?></option>     
                                  <?php }?>         
                                </select>
                                <select class="form-control" name="idmincom" required>  
                                 <option value="">Mining company</option>     
                                  <?php 
                                    $perintah=mysqli_query($koneksi2,"SELECT id_mining,mining_company from mining_company order by mining_company");
                                    while ($data = mysqli_fetch_array($perintah)) {?>    
                                      <option value=<?php echo  $data['id_mining']; ?>><?php echo  $data['mining_company'];?></option>     
                                  <?php }?>         
                                </select>
                                <input class="form-control" type = "text" name = "site" placeholder="Site/Project" required>
                                <input class="form-control" type = "text" name = "location" placeholder="Location" required>
                                <input class="form-control" type = "text" name = "kabupaten" placeholder="kabupaten" required>
                                <input class="form-control" type = "text" name = "kecamatan" placeholder="kecamatan" required>
                                <input class="form-control" type = "number" name = "target" placeholder="Target produksi" required>
                                <br>
                                <br>
                                <button type="submit" value="submit" class="btn btn-success"><span></span>Submit</button>
                            </form> 
                        </div>
                    </div>
                <?php } ?>
                    <div class="x_content">
                          <div class="col-md-12 col-sm-12 col-xs-12"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                              <thead style="background:#f5f5f5;">
                                <tr>
                                  <th>Id</th>
                                  <th>Customer Name</th>
                                  <th>Mining Company</th>
                                  <th>Site/Project</th>
                                  <th>Location</th>
                                  <th>Kabupaten</th>
                                  <th>Kecamatan</th>
                                  <th>Target OB</th>
                                  <th>Target Coal</th>
                                  <th>Status</th>
                                  <th>Last Update</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $perintah = mysqli_query($koneksi2,"SELECT *,a.target as targetsite
                                                                    FROM site_master a
                                                                    LEFT JOIN customer_master b ON a.id_customer=b.id_customer_master
                                                                    LEFT JOIN mining_company c ON a.mining_company=c.id_mining");
                                $no=1;
                                while ($data = mysqli_fetch_array($perintah)) { ?>
                                <tr>
                                  <td><?php echo $data['id_site_master'];?></td>
                                  <td><?php echo $data['customer'];?></td>
                                  <td><?php echo $data['mining_company'];?></td>
                                  <td><?php echo $data['site'];?></td>
                                  <td><?php echo $data['location'];?></td>
                                  <td><?php echo $data['kabupaten'];?></td>
                                  <td><?php echo $data['kecamatan'];?></td>
                                  <td><?php echo number_format($data['targetsite'], 0, ',', '.'); ?></td>
                                  <td><?php echo number_format($data['target2'], 0, ',', '.'); ?></td>
                                  <td><?php echo $data['status'];?></td>
                                  <td><?php echo $data['year_update'];?></td>
                                  <td style="width:70px">
                                    <?php if($level==1&&$idsection=='1') { ?>
                                      <a href="#" class="editinvent" data-idsite="<?php echo $data['id_site_master']; ?>"data-item="site">Edit</a>
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
                                            <h4 class="modal-title" id="myModalLabel">Edit customer data</h4>
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
            <?php 
        }
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
                  responsive: false
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
          {idsite:$(this).attr('data-idsite'),
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