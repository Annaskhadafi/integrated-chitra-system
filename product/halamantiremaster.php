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
            <h3>Tire Data Master</h3>            
            <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Tire Manufacturer</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah brand tire -->
                      <form  class="form-inline" role="form" action="tambahmanufac.php" method="post">
                        <input class="form-control" type = "text" name = "manufac" placeholder="Tire Manufacturer Name"/>
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>
                      <!-- end form tambah brand tire -->
                      <!-- tampilkan seluruh brand tire -->
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Tire Manufacturer</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                              <?php 
                              include "koneksi.php";
                              $perintah = mysqli_query($koneksi2, "select *from tire_manufac");
                              $no=1;
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['manufac'];?></td>
                                <td>
                                <a href="#" class="editmanufac" data-idmanufac="<?php echo  $data['id_manufac']; ?>">Edit </a>|
                                <a href="hapusmanufac.php?idmanufac=<?php echo $data['id_manufac']; ?>"onclick="javascript: return confirm('Delete [<?php echo $data['manufac']; ?>] from tire manufacturer ?')">Delete</a>
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
            <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="x_panel tile">
                    <div class="x_title">
                      <h2>Tire Pattern</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah pattern tire -->
                      <form  class="form-inline" role="form" action="tambahpattern.php" method="post">
                        <input class="form-control" type = "text" name = "pattern" placeholder="Tire Pattern"/>
                         <select class="form-control" name="id_manufac">  
                         <option value="">Manufacturer</option>     
                          <?php include "koneksi.php";
                          $perintah=mysqli_query($koneksi2, "SELECT * FROM tire_manufac order by manufac asc");    
                          $no =1;    
                          while ($data = mysqli_fetch_array($perintah)) {?>    
                         <option value=<?php echo  $data['id_manufac']; ?>><?php echo  $data['manufac']; ?></option>     
                          <?php $no++; }?>         
                          </select>  
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>                      
                      <!-- end form tambah pattern tire -->
                      <!-- tampilkan seluruh pattern tire -->
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Pattern</th>
                              <th>Manufacturer</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            include "koneksi.php";
                            $perintah = mysqli_query($koneksi2, "
                            select * 
                            from tire_pattern a,tire_manufac b 
                            where a.manufac=b.id_manufac order by b.manufac asc");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                            <td><?php echo $data['pattern'];?></td>
                            <td><?php echo $data['manufac'];?></td>
                            <td><a href="#" class="editpattern" data-idpattern="<?php echo  $data['id_pattern']; ?>">Edit </a>|
                            <a href="hapuspattern.php?idpattern=<?php echo $data['id_pattern']; ?>" 
                              onclick="javascript: return confirm('Delete pattern from [<?php echo $data['pattern']; ?>] from [<?php echo $data['manufac']; ?>]  ?')">Delete</a>
                            </td>
                            </tr>    
                            <?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- end tampilkan seluruh pattern tire -->
                    </div>
                  </div>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12">
                  <div class="x_panel tile">
                    <div class="x_title">
                      <h2>Tire Size</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah size tire -->
                      <form  class="form-inline" role="form" action="tambahsize.php" method="post">
                        <input class="form-control" type = "text" name ="size" placeholder="Tire Size"/>
                        <select class="form-control" name="pattern">
                          <option value="">Pattern</option>
                          <?php 
                          $perintah=mysqli_query($koneksi2, "SELECT * FROM tire_pattern order by pattern asc");
                          $no =1;
                          while ($data = mysqli_fetch_array($perintah)) {?>
                          <option value=<?php echo  $data['id_pattern']; ?>><?php echo $data['pattern']; ?></option>
                          <?php
                          $no++;
                          }
                          ?> 
                        </select>
                        <input class="form-control" type = "text" name = "otd" style="width:100px;" placeholder="OTD"/>
                        <input class="form-control" type = "text" name = "psi" style="width:100px;" placeholder="PSI"/>  
                        <input class="form-control" type = "text" name = "target" style="width:100px;" placeholder="Target Lt"/> 
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>
                      <!-- end form tambah size tire --> 
                      <!-- tampilkan seluruh size tire -->
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Size</th>
                              <th>Pattern</th>
                              <th>OTD</th>
                              <th>Recc Pressure</th>
                              <th>Target Lt</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            include "koneksi.php";
                            $perintah = mysqli_query($koneksi2, "SELECT * FROM tire_size a,tire_pattern b
                            where a.pattern=b.id_pattern
                            ORDER BY b.pattern ASC");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                            <tr>
                            <td><?php echo $data['size'];?></td>
                            <td><?php echo $data['pattern'];?></td>
                            <td><?php echo $data['otd'];?></td>                    
                            <td><?php echo $data['recc_pressure'];?></td>         
                            <td><?php echo $data['target'];?></td>
                            <td><a href="#" class="editsize" data-idsize="<?php echo  $data['id_size']; ?>">Edit </a>|
                            <a href="hapussize.php?idsize=<?php echo $data['id_size']; ?>" 
                              onclick="javascript: return confirm('Delete tire size [<?php echo $data['size']; ?>] with pattern [<?php echo $data['pattern'];?>] ?')">Delete</a>
                            </td>
                            </tr>    
                            <?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- end tampilkan seluruh size tire -->
                    </div>
                  </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-6">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Tire Compound</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah compound tire -->
                      <form  class="form-inline" role="form" action="tambahcompound.php" method="post">
                        <input class="form-control" type = "text" name = "compound" placeholder="Tire Compound"/>
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>
                      <!-- end form tambah compound tire -->
                      <!-- tampilkan seluruh compound tire -->
                      </br>
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Tire Compound</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            include "koneksi.php";
                            $perintah = mysqli_query($koneksi2, "
                            select * 
                            from tire_compound");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                                <tr>
                                  <td><?php echo $data['compound'];?></td>
                                  <td style="width:100px">
                                    <a href="#" class="editcompound" data-idcompound="<?php echo  $data['id_compound']; ?>">Edit </a>|
                                    <a href="hapuscompound.php?idcompound=<?php echo $data['id_compound']; ?>" onclick="javascript: return confirm('Delete compound [<?php echo $data['compound']; ?>] from list ?')">
                                      Delete
                                    </a>
                                  </td>
                                </tr>    
                              <?php $no++; 
                            } ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- end tampilkan seluruh compound tire -->
                    </div>
                  </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Tire Supplier</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <!-- form tambah supplier tire -->
                      <form  class="form-inline" role="form" action="tambahsupplier.php" method="post">
                        <input class="form-control" type = "text" name = "supplier" placeholder="Tire Supplier Name"/>
                        <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                      </form>
                      <!-- end form tambah supplier tire --> 
                      <!-- tampilkan seluruh supplier tire --> 
                      </br>
                      <div class="pre-scrollable">
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Tire Supplier</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            include "koneksi.php";
                            $perintah = mysqli_query($koneksi2, "
                            select * 
                            from supplier");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                                <tr>
                                <td><?php echo $data['supplier'];?></td>
                                <td style="width:100px"><a href="#" class="editsupplier" data-idsupplier="<?php echo  $data['id_supplier']; ?>">Edit </a>|
                                <a href="hapussupplier.php?idsupplier=<?php echo $data['id_supplier']; ?>" 
                                  onclick="javascript: return confirm('Delete supplier [<?php echo $data['supplier']; ?>] from list ?')">Delete</a>
                                </td>
                                </tr>    
                              <?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                      <!-- end tampilkan seluruh supplier tire --> 
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

    <!-- modal pop up size tire -->
      <script>
          $(function(){
              $(document).on('click','.editsize',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditsize.php',
                      {idsize:$(this).attr('data-idsize')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- end modal pop up size tire -->
    <!-- modal pop up pattern tire -->
      </script>
          <script>
          $(function(){
              $(document).on('click','.editpattern',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditpattern.php',
                      {idpattern:$(this).attr('data-idpattern')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>    
    <!-- /modal pop up pattern tire -->
    <!-- modal pop up Manufacture tire -->
      <script>
          $(function(){
              $(document).on('click','.editmanufac',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditmanufac.php',
                      {idmanufac:$(this).attr('data-idmanufac')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- /modal pop up manufacture tire -->
    <!-- modal pop up Supplier tire -->
      <script>
          $(function(){
              $(document).on('click','.editsupplier',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditsupplier.php',
                      {idsupplier:$(this).attr('data-idsupplier')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- /modal pop up Supplier tire -->
    <!-- modal pop up Compound tire -->
      <script>
          $(function(){
              $(document).on('click','.editcompound',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditcompound.php',
                      {idcompound:$(this).attr('data-idcompound')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- /modal pop up Compound tire -->
    <!-- modal pop up status tire -->
      <script>
          $(function(){
              $(document).on('click','.editstatus',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditstatus.php',
                      {idstatus:$(this).attr('data-idstatus')},
                      function(html){
                          $(".modal-body").html(html);
                      }   
                  );
              });
          });
      </script>
    <!-- /modal pop up status tire -->
    <!-- modal pop up remark tire -->
      <script>
          $(function(){
              $(document).on('click','.editremark',function(e){
                  e.preventDefault();
                  $("#myModal").modal('show');
                  $.post('modaleditremark.php',
                      {idremark:$(this).attr('data-idremark')},
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