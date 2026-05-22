<?php session_start();?> <!-- tangkap nilai 'username' dan 'password' dari proseslogin -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chitra Tire System</title>
    <link rel="shortcut icon" href="images/cp_logo2.png"/>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
    <style>
    .pre-scrollable {height: 700px;}
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
      <?php 
          include "koneksi.php";
          include "template_menu_adm.php";
        ?>
         <!-- Isi content -->       
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row">
                        
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h3>Rim/wheel Data Master</h3>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah brand tire -->
                      <form  class="form-inline" role="form" action="tambahrimmaster.php" method="post">
                        <input class="form-control" type = "text" name = "manufac" placeholder="Rim Manufacturer Name" required="required"/>
                        <select class="form-control" name="type" required="required">
                          <option value="">--Rim Type--</option> 
                          <option value="5 Pieces">5 Pieces</option> 
                          <option value="3 Pieces">3 Pieces</option>
                        <input class="form-control" type = "number" name = "size" placeholder="Rim Size" required="required"/> Inch <br>  
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>
                      <!-- end form tambah brand tire -->
                      <!-- tampilkan seluruh brand tire -->
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Rim/wheel Manufacturer</th>
                              <th>Type</th>
                              <th>Size (Inch)</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php 
                              include "koneksi.php";
                              $perintah = mysqli_query($sambung, "SELECT * from rim");
                              $no=1;
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['rim_manufac'];?></td>
                                <td><?php echo $data['rim_type'];?></td>
                                <td><?php echo $data['rim_size'];?></td>
                                <td>
                                <a href="#" class="editrim" data-idrim="<?php echo  $data['id_rim']; ?>">Edit </a>
                                </td>
                              </tr>    
                              <?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- end tampilkan seluruh brand tire -->
                    </div>
                  </div>
            </div>
          </div>
        </div>
      <!-- /Isi content -->  
      </div>
    </div>
  <!-- modal pop up edit -->
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
          </div>
        </div>
      </div>
    </div>    
  <!-- end modal pop up edit -->
  <!-- footer content -->
  <footer>
      <div class="pull-right">
        Chitra Tire System by Chitra Paratama @2017
      </div>
      <div class="clearfix"></div>
  </footer>
  <!-- /footer content -->

    <!-- kelebihan /div -->
      </div>
    </div>
    <!-- kelebihan /div -->

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
      <!-- Custom Theme Scripts -->
      <script src="../build/js/custom.min.js"></script>
    <!-- end jQuery -->

    <!-- DataTables -->
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
    <!-- /DataTables -->
    <!-- modal pop up Manufacture tire -->
      <script>
          $(function(){
              $(document).on('click','.editrim',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditrim.php',
                      {idrim:$(this).attr('data-idrim')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- /modal pop up remark tire -->
    <!-- toogle pop up -->
      <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();   
        });
      </script>
    <!-- /toogle pop up -->
  </body>
</html>