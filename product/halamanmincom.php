<?php session_start();?>
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
                <br>
                <?php include "template_menu.php";?>
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
            <div class="right_col" role="main">
                    <div class="clearfix"></div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Mining Company Information</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <?php 
                                //hanya admin yang bisa input
                                if($level==1){ ?>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <form class="form-inline" role="form" action="tambahmincom.php" method="post">
                                        <input class="form-control" type ="text" name="mincom" placeholder="Mining Company Name" required>
                                        <select class="form-control" name="material" required> 
                                            <option value="" selected disabled>Material</option>  
                                            <option value="Coal">Coal</option> 
                                            <option value="Mineral">Mineral</option> 
                                            <option value="Gold">Gold</option>       
                                        </select>
                                        <input class="form-control" type="number" name="target" max="9999999999" placeholder="Target Production">
                                        <br>
                                        <br>
                                        <button type="submit" value="submit" class="btn btn-success"><span>Submit</span></button>
                                    </form> 
                                </div>
                                    <?php 
                                } 
                                ?>
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                    <?php 
                                    if($name!=""){
                                        $perintah = mysqli_query($koneksi2,"SELECT * 
                                                                            FROM mining_company a
                                                                            JOIN site_master b ON a.id_mining = b.mining_company
                                                                            where b.location is NOT NULL ");
                                        while ($data = mysqli_fetch_array($perintah)) {
                                        $location[$data['id_mining']]=$data['location'];   
                                        }
                                    ?>      
                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                          <thead style="background:#f5f5f5;">
                                            <tr>
                                              <th>Mining Company</th>
                                              <th>Location</th>
                                              <th>Material</th>
                                              <th>Target Production</th>
                                              <th>Last Update</th>
                                              <th></th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php 
                                            $perintah = mysqli_query($koneksi2,"SELECT * from mining_company");
                                            $no=1;
                                            while ($data = mysqli_fetch_array($perintah)) { 
                                            $id=$data['id_mining'];
                                            ?>
                                                <tr>
                                                  <td><?php echo $data['mining_company'];?></td>
                                                  <td><?php echo isset($location[$id]) ? $location[$id] : ''; ?></td>
                                                  <td><?php echo $data['material'];?></td>
                                                  <td><?php echo $data['target'];?></td>
                                                  <td><?php echo $data['tgl_update'];?></td>
                                                  <td><button><a href="#" class="detailmincom" data-idmining="<?php echo $data['id_mining']; ?>"data-item="site">See Detail</a></button></td>
                                                </tr>
                                                <?php $no++; 
                                            }?>
                                          </tbody>
                                        </table>
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- footer content -->  
                                        <?php 
                                    }
                                      else {}
                                    ?>
                                </div>
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
        $(document).on('click','.detailmincom',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modaldetailmincom.php',
          {idmining:$(this).attr('data-idmining')
          //,item:$(this).attr('data-item')
          },
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