<?php
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(4, 7));
?>
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
                <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>VHS stock summary</h2>
                                <div class="clearfix"></div>
                            </div>
                            <!--cek stok actual-->
                            
                            <div class="x_content"><?php 
                                            $actual=array();
                                            $last=array();
                                            $picinput=array();
                                            $perintah = mysqli_query($koneksi6,"SELECT * FROM actual a,chitraparatama_ics.user b WHERE a.id_storeloc=$idstoreloc and a.pic=b.id_user ORDER BY id_actual;");
                                            while ($data = mysqli_fetch_array($perintah)){
                                                $actual[$data['id_part_number']][$data['id_storeloc']]=$data['qty_actual'];
                                                $last[$data['id_part_number']][$data['id_storeloc']]=$data['last_update'];
                                                $picinput[$data['id_part_number']][$data['id_storeloc']]=$data['name'];
                                            }
                            ?>
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                    <table id="datatable-butts" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Site</th>
                                                <th>PN_CP</th>
                                                <th>MM_CK</th>
                                                <th>Size</th>
                                                <th>Brand</th>
                                                <th>Pattern</th>
                                                <th>Supply</th>
                                                <th>Stock on hand(SAP)</th>
                                                <th>GR/GI</th>
                                                <th>Actual Stock</th>
                                                <th>Last Update</th>
                                                <th>PIC</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $perintah = mysqli_query($koneksi6,"SELECT 
                                                                                    location,
                                                                                    part_number,
                                                                                    mm_ck,
                                                                                    a.id_part_number,
                                                                                    size,
                                                                                    brand,
                                                                                    pattern,
                                                                                    COUNT(a.id_stock) AS qty,
                                                                                    SUM(CASE WHEN a.gi IS NOT NULL AND a.gi <> '' THEN 1 ELSE 0 END) AS gi,
                                                                                    COUNT(a.id_stock) - SUM(CASE WHEN a.gi IS NOT NULL AND a.gi <> '' THEN 1 ELSE 0 END) AS onhand
                                                                                FROM stock a
                                                                                JOIN storeloc b ON a.id_storeloc = b.id_storeloc and b.id_storeloc=$idstoreloc
                                                                                JOIN part_number c ON a.id_part_number = c.id_part_number
                                                                                GROUP BY a.id_storeloc, a.id_part_number;");
                                            while ($data = mysqli_fetch_array($perintah)){
                                            ?>
                                                <tr>
                                                    <form method="POST" action="vhs_tambahactual.php"> 
                                                    <td><?php echo $data['location'];?></td>
                                                    <td><?php echo $data['part_number'];?></td>
                                                    <td><?php echo $data['mm_ck'];?></td>
                                                    <td><?php echo $data['size'];?></td>
                                                    <td><?php echo $data['brand'];?></td>
                                                    <td><?php echo $data['pattern'];?></td>
                                                    <td><?php echo $data['qty'];?></td>
                                                    <td><?php echo $data['onhand'];?></td>
                                                    <td><?php echo $data['gi'];?></td>
                                                    <td><input type="number" name="actual" value="<?php echo $actual[$data['id_part_number']][$idstoreloc];?>" required></td>
                                                    <td><?php echo $last[$data['id_part_number']][$idstoreloc];?></td>
                                                    <td><?php echo $picinput[$data['id_part_number']][$idstoreloc];?></td>
                                                    <td>
                                                        <input type="hidden" name="storeloc" value="<?php echo $idstoreloc; ?>">
                                                        <input type="hidden" name="material" value="<?php echo $data['id_part_number'];?>">
                                                        <input type="hidden" name="pic" value="<?php echo $idlogin; ?>">
                                                        <button type="submit">Save</button>
                                                    </td>
                                                    </form>
                                                </tr>
                                            <?    
                                            }
                                            ?>    
                                        </tbody>
                                    </table> 
                                </div>
                            </div>
                        </div>
                </div>
                <div class="row" style="margin-top:0px">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Actual Update</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead style="background:#f5f5f5;">	
                                        <tr>
                                          <th>No</th>
                                          <th>Size</th>
                                          <th>Brand</th>
                                          <th>Pattern</th>
                                          <th>Part number</th>
                                          <th>MM_CK</th>
                                          <th>Location</th>
                                          <th>PIC</th>
                                          <th>Qty</th>
                                          <th>Last Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $perintah = mysqli_query($koneksi6,"SELECT id_actual,size,brand,pattern,part_number,mm_ck,location,name,qty_actual,last_update 
                                                                                FROM actual a,chitraparatama_ics.user b,part_number c,storeloc d 
                                                                                WHERE a.id_storeloc=$idstoreloc AND a.id_part_number=c.id_part_number AND a.pic=b.id_user AND a.id_storeloc=d.id_storeloc 
                                                                                ORDER BY id_actual DESC limit 200;");
                                            while ($data = mysqli_fetch_array($perintah)) {?>
                                                <tr>
                                                  <td><?php echo $data['id_actual'];?></td>
                                                  <td><?php echo $data['size'];?></td>
                                                  <td><?php echo $data['brand'];?></td>
                                                  <td><?php echo $data['pattern'];?></td>
                                                  <td><?php echo $data['part_number'];?></td>
                                                  <td><?php echo $data['mm_ck'];?></td>
                                                  <td><?php echo $data['location'];?></td>
                                                  <td><?php echo $data['name'];?></td>
                                                  <td><?php echo $data['qty_actual'];?></td>
                                                  <td><?php echo $data['last_update'];?></td>
                                                </tr>
                                                <?php 
                                            }
                                        ?>
                                    </tbody>
                                </table>
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
                  responsive: false,
                  order: [[0, 'desc']]
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
              'order': [[ 0, 'desc' ]],
              'columnDefs': [
                { orderable: true, targets: [0] }
              ]
            });
            
                
            TableManageButtons.init();
          });
        </script>
    <script>
      $(function(){
        $(document).on('click','.editstocksales',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modaleditstockvhssales.php',
          {idforecast:$(this).attr('data-idforecast')
          },
          function(html){
            $(".modal-body").html(html);
              }   
          );
        });
      });
      $(function(){
        $(document).on('click','.editstockscm',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modaleditstockvhsscm.php',
          {idforecast:$(this).attr('data-idforecast'),job:$(this).attr('data-job'),project:$(this).attr('data-project')
          },
          function(html){
            $(".modal-body").html(html);
              }   
          );
        });
      });
      $(function(){
        $(document).on('click','.receivedstock',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modalreceivedstockvhs.php',
          {idstock:$(this).attr('data-idstock')
          },
          function(html){
            $(".modal-body").html(html);
              }   
          );
        });
      });
      $(function(){
        $(document).on('click','.editstockte',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modaleditstockvhste.php',
          {idstock:$(this).attr('data-idstock')
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
    <script>
    function cancelWO(idstock,wo) {
    if (confirm("Cancel WO: " + wo + "  (Stock ID: " + idstock + ") ?")){
            // Redirect ke halaman PHP untuk proses cancel
            window.location.href = "vhs_cancel_wo.php?id_stock=" + encodeURIComponent(idstock);
        }
    }
    </script>
  </body>
</html>