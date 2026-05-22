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
          include "template_menu.php";
        ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row" style="margin-top:50px">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel tile">
                <div class="x_title">
                  <h2>User Data Master</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-11 col-sm-11 col-xs-11">
                        <!-- form penambahan user ICS -->
                        <form  class="form-inline" role="form" action="tambahData.php" method="post">
                            <input class="form-control" type = "hidden" name ="item" value="user">
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" placeholder="Name" required />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="username" placeholder="Username" required />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Password" required />
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="section" required>
                                    <option value="">Section</option>
                                    <?php 
                                    $perintah = mysqli_query($koneksi, "SELECT * FROM section WHERE section !='' ORDER BY section ");
                                    while ($data = mysqli_fetch_array($perintah)) {
                                        echo "<option value='{$data['id_section']}'>{$data['section']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="level" required>
                                    <option value="1">Admin</option>
                                    <option value="2">Staff</option>
                                    <option value="3">Managerial</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <span class="glyphicon glyphicon-plus-sign"></span> Submit
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="x_content">
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>id</th>
                          <th>Name</th>
                          <th>Username</th>
                          <th>Password</th>
                          <th>Department</th>
                          <th>Section</th>
                          <th>Level</th>
                          <th>Last login</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                <?php 
                //select seluruh data user dari tabel user
                $perintah = mysqli_query($koneksi, "SELECT * 
                FROM user a, section b, department c 
                WHERE a.level !=910 and a.section=b.id_section and b.department=c.id_dept 
                ORDER BY a.name");
                $no=1;
                while ($data = mysqli_fetch_array($perintah)) { ?>
                    <tr>
                    <td><?php echo $data['id_user'];?></td>
                    <td><?php echo $data['name'];?></td>
                    <td><?php echo $data['username'];?></td>
                    <td><?php echo $data['password'];?></td>
                    <td><?php echo $data['department'];?></td>
                    <td><?php echo $data['section'];?></td>
                    <td><?php 
                        if($data['level']==1){
                          $dept="Admin";
                        } elseif($data['level']==2){
                          $dept="Staff";
                        } else {
                          $dept="Managerial";
                        } echo $dept; ?>
                    </td>
                    <td><?php echo $data['last_login'];?></td>
                    <!-- tombol edit user -->
                    <td><a href="#" class="edituser" data-iduser="<?php echo  $data['id_user']; ?>">Edit </a>|
                    <a href="hapususer.php?iduser=<?php echo $data['id_user']; ?>" 
                      onclick="javascript: return confirm('Delete user with username [<?php echo $data['username']; ?>]  ?')">Delete</a>
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
    <!-- /page content -->
    <!-- modal edit user -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit</h4>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" value="submit" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        
                    </div>
                </div>
            </div>
    </div>    
        <!-- footer content -->
    <footer>
      <div class="pull-right">
        Integrated Chitra System
      </div>
      <div class="clearfix"></div>
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
    <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
    <script src="../vendors/js/bootstrap.min.js"></script>
    <script src="../vendors/js/docs.min.js"></script>
    <script>
      //function edit user
        $(function(){
            $(document).on('click','.edituser',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modaledituser.php',
                    {iduser:$(this).attr('data-iduser')},
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