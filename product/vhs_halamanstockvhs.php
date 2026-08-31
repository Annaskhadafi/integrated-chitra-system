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
                <?php if ($level==1 && $idsection==7){ ?>
                    <!--menu stock vhs SCM only-->
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Add VHS stock</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <!--tambah stock vhs-->
                                    <form  class="form-inline" role="form" action="vhs_tambahstockvhs.php" method="post">
                                        <input class="form-control" type = "hidden" name ="pic" value=<?php echo  $idlogin; ?>>
                                        <input class="form-control" type = "text" name ="do" placeholder="Delivery order" required>
                                      <select class="form-control" name="pn" required> 
                                        <option value="">Item</option>
                                        <?php 
                                          $perintah=mysqli_query($koneksi6, "SELECT * from part_number order by size,brand,pattern");
                                          while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value=<?php echo  $data['id_part_number']; ?>><?php echo $data['size']." ".$data['brand']." ".$data['pattern']."(".$data['part_number'].")"; ?></option>
                                            <?php 
                                          }   
                                        ?> 
                                      </select>
                                      <input class="form-control" type="date" name="date" required>
                                      <input class="form-control" type = "number" name ="qty" placeholder="Quantity" min="1" max="400" required>
                                      <select class="form-control" name="storeloc" required> 
                                        <option value="">Store_loc</option>
                                        <?php 
                                          $perintah=mysqli_query($koneksi6, "SELECT * from storeloc ORDER BY location");
                                          while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value=<?php echo  $data['id_storeloc']; ?>><?php echo $data['location']." (".$data['storeloc'].")"; ?></option>
                                            <?php 
                                          }   
                                        ?> 
                                      </select>
                                      <br>
                                      <br>
                                      <button type="submit" value="submit" class="btn btn-success"> Submit </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Update WO & GR/GI</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <!--update WO GR/GI-->
                                    <form  class="form-inline" role="form" action="vhs_halamanupdatestock.php" method="post">
                                      <select class="form-control" name="storeloc" required> 
                                        <option value="">Store_loc</option>
                                        <?php 
                                          $perintah=mysqli_query($koneksi6, "SELECT a.id_storeloc, a.location,a.storeloc
                                                                            FROM storeloc a
                                                                            JOIN stock b ON a.id_storeloc = b.id_storeloc
                                                                            WHERE b.gi IS NULL
                                                                            GROUP BY a.id_storeloc
                                                                            ORDER BY a.storeloc
                                                                            ; ");
                                          while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value=<?php echo  $data['id_storeloc']; ?>><?php echo $data['storeloc']." (".$data['location'].")"; ?></option>
                                            <?php 
                                          }   
                                        ?> 
                                      </select>
                                      <select class="form-control" name="pn" required> 
                                        <option value="">Item</option>
                                        <?php 
                                          $perintah=mysqli_query($koneksi6, "SELECT * from part_number order by size,brand,pattern");
                                          while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value=<?php echo  $data['id_part_number']; ?>><?php echo $data['size']." ".$data['brand']." ".$data['pattern']."(".$data['part_number'].")"; ?></option>
                                            <?php 
                                          }   
                                        ?> 
                                      </select>
                                      <input class="form-control" type ="number" name ="qty" placeholder="Quantity" min="1" max="400" required>
                                      <input class="form-control" type="date" name="date" required>
                                      <input class="form-control" type="hidden" name="picgi" value=<?php echo  $idlogin; ?>>
                                      <br>
                                      <br>
                                      <button type="submit" value="submit" class="btn btn-success"> Add WO & GR/GI </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Invoice MRKO</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <!--update INVOICE-->
                                    <form  class="form-inline" role="form" action="vhs_updatestockinvoice.php" method="post">
                                      <select class="form-control" name="mrko" required> 
                                        <option value="">MRKO / Location</option>
                                        <?php 
                                          $perintah=mysqli_query($koneksi6, "SELECT mrko,a.id_storeloc,location
                                                                            FROM stock a 
                                                                            join storeloc b on a.id_storeloc=b.id_storeloc
                                                                            where mrko is not null and invoice is null 
                                                                            GROUP BY mrko, a.id_storeloc, b.location
                                                                            ORDER BY location,mrko;
                                                                            ; ");
                                          while ($data = mysqli_fetch_array($perintah)) {?>
                                            <option value="<?php echo $data['mrko'].'|'.$data['id_storeloc']; ?>">
                                                <?php echo $data['mrko'].' / '.$data['location']; ?>
                                            </option>
                                            <?php 
                                          }   
                                        ?> 
                                      </select>
                                      <input class="form-control" type ="text" name ="invoice" placeholder="Invoice number" required>
                                      <input class="form-control" type="date" name="date" required>
                                      <input class="form-control" type="hidden" name="picgi" value=<?php echo  $idlogin; ?>
                                      <br>
                                      <br>
                                      <br>
                                      <button type="submit" value="submit" class="btn btn-success"> Invoice MRKO </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:0px">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Transfer stock</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="col-md-12 col-sm-12 col-xs-12"> 
                                <!--Transfer stock-->
                                    <form class="form-inline" role="form" action="vhs_transfer.php" method="post">
                                        <?php 
                                          // Query hanya sekali
                                          $perintah = mysqli_query($koneksi6, "SELECT a.id_storeloc, a.location, a.storeloc FROM storeloc a ORDER BY a.location");
                                        
                                          // Simpan hasil ke dalam array
                                          $storeloc_list = [];
                                          while ($row = mysqli_fetch_assoc($perintah)) {
                                              $storeloc_list[] = $row;
                                          }
                                        ?>
                                        
                                        <b>From : </b>
                                        <select class="form-control" name="from" required> 
                                          <option value="">Store_loc</option>
                                          <?php foreach ($storeloc_list as $data) { ?>
                                            <option value="<?php echo $data['id_storeloc']; ?>">
                                              <?php echo $data['storeloc']." (".$data['location'].")"; ?>
                                            </option>
                                          <?php } ?>
                                        </select>
                                        
                                        <b>To : </b>
                                        <select class="form-control" name="to" required> 
                                          <option value="">Store_loc</option>
                                          <?php foreach ($storeloc_list as $data) { ?>
                                            <option value="<?php echo $data['id_storeloc']; ?>">
                                              <?php echo $data['storeloc']." (".$data['location'].")"; ?>
                                            </option>
                                          <?php } ?>
                                        </select>
                                        <select class="form-control" name="pn" required> 
                                          <option value="">Item</option>
                                          <?php 
                                            $perintah=mysqli_query($koneksi6, "SELECT * from part_number");
                                            while ($data = mysqli_fetch_array($perintah)) {?>
                                              <option value="<?php echo  $data['id_part_number']; ?>"><?php echo $data['size']." ".$data['brand']." ".$data['pattern']."(".$data['part_number'].")"; ?></option>
                                              <?php 
                                            }   
                                          ?> 
                                        </select>
                                      <input class="form-control" type = "number" name ="qty" placeholder="Quantity" min="1" max="400" required>
                                      <input class="form-control" type = "text" name ="do" placeholder="Delivery Order" required>
                                      <input class="form-control" type="date" name="date" required>
                                      <br>
                                      <br>
                                      <button type="submit" value="submit" class="btn btn-success"> Transfer </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row" style="margin-top:0px">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>VHS stock</h2>
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
                                              <th>Do</th>
                                              <th>Do date</th>
                                              <th>Status</th>
                                              <th>Storeloc</th>
                                              <th>Location</th>
                                              <th>WO number</th>
                                              <th>GR/GI</th>
                                              <th>GR/GI Date</th>
                                              <th>MRKO</th>
                                              <th>Invoice</th>
                                              <th>PIC trnsfer</th>
                                              <th>PIC GI</th>
                                              <th></th>
                                            </tr>
                                        </thead>
                                        <?php if ($level==1 && $idsection==7){ ?>
                                        <tbody>
                                            <?php 
                                                $perintah = mysqli_query($koneksi6,"SELECT a.*,d.name,b.size,b.brand,b.pattern,b.part_number,b.mm_ck,c.storeloc,c.location
                                                                                    FROM chitraparatama_vhs_stock.stock a
                                                                                    LEFT JOIN chitraparatama_vhs_stock.part_number b ON a.id_part_number = b.id_part_number
                                                                                    LEFT JOIN chitraparatama_vhs_stock.storeloc c ON a.id_storeloc = c.id_storeloc
                                                                                    LEFT JOIN chitraparatama_ics.user d ON a.pic = d.id_user");
                                                while ($data = mysqli_fetch_array($perintah)) {?>
                                                    <tr>
                                                      <td><?php echo $data['id_stock'];?></td>
                                                      <td><?php echo $data['size'];?></td>
                                                      <td><?php echo $data['brand'];?></td>
                                                      <td><?php echo $data['pattern'];?></td>
                                                      <td><?php echo $data['part_number'];?></td>
                                                      <td><?php echo $data['mm_ck'];?></td>
                                                      <td><?php echo $data['do'];?></td>
                                                      <td><?php echo $data['delivery_date'];?></td>
                                                      <td><?php echo $data['status'];?></td>
                                                      <td><?php echo $data['storeloc'];?></td>
                                                      <td><?php echo $data['location'];?></td>
                                                      <td><?php echo $data['wo'];?></td>
                                                      <td><?php echo $data['gi'];?></td>
                                                      <td><?php echo $data['gi_date'];?></td>
                                                      <td><?php echo $data['mrko'];?></td>
                                                      <td><?php echo $data['invoice'];?></td>
                                                      <td><?php echo $data['name'];?></td>
                                                      <td></td>
                                                      <td><?php if($data['gi']>1) {?><button type="button" class="btn btn-warning btn-cancel" 
                                                                                    data-idstock="<?php echo $data['id_stock']; ?>" 
                                                                                    data-wo="<?php echo $data['wo']; ?>">
                                                                              Cancel
                                                                            </button> <?php } ?>
                                                      </td>
                                                    </tr>
                                                    <?php 
                                                }
                                            ?>
                                        </tbody>
                                        <?php } 
                                        else {
                                        ?>
                                        <tbody>
                                            <?php 
                                                $perintah = mysqli_query($koneksi6,"SELECT a.*,d.name,b.size,b.brand,b.pattern,b.part_number,b.mm_ck,c.storeloc,c.location
                                                                                    FROM chitraparatama_vhs_stock.stock a
                                                                                    LEFT JOIN chitraparatama_vhs_stock.part_number b ON a.id_part_number = b.id_part_number
                                                                                    LEFT JOIN chitraparatama_vhs_stock.storeloc c ON a.id_storeloc = c.id_storeloc
                                                                                    LEFT JOIN chitraparatama_ics.user d ON a.pic = d.id_user
                                                                                    where a.id_storeloc=$idstoreloc");
                                                while ($data = mysqli_fetch_array($perintah)) {?>
                                                    <tr>
                                                      <td><?php echo $data['id_stock'];?></td>
                                                      <td><?php echo $data['size'];?></td>
                                                      <td><?php echo $data['brand'];?></td>
                                                      <td><?php echo $data['pattern'];?></td>
                                                      <td><?php echo $data['part_number'];?></td>
                                                      <td><?php echo $data['mm_ck'];?></td>
                                                      <td><?php echo $data['do'];?></td>
                                                      <td><?php echo $data['delivery_date'];?></td>
                                                      <td><?php echo $data['status'];?></td>
                                                      <td><?php echo $data['storeloc'];?></td>
                                                      <td><?php echo $data['location'];?></td>
                                                      <td><?php echo $data['wo'];?></td>
                                                      <td><?php echo $data['gi'];?></td>
                                                      <td><?php echo $data['gi_date'];?></td>
                                                      <td><?php echo $data['name'];?></td>
                                                      <td></td>
                                                      <td>
                                                      </td>
                                                    </tr>
                                                    <?php 
                                                }
                                            ?>
                                        </tbody>
                                        <?php } ?>
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
<script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

<!-- Chart.js -->
<script src="../vendors/Chart.js/dist/Chart.min.js"></script>

<!-- bootstrap-progressbar -->
<script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>

<!-- ECharts -->
<script src="../vendors/echarts/dist/echarts.min.js"></script>
<script src="../vendors/echarts/map/js/world.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
    
    <script>
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();   
    });
    </script>
    <script>
    $(document).on('click', '.btn-cancel', function() {
      var idstock = $(this).data('idstock');
      var wo = $(this).data('wo');
      if (confirm("Cancel WO: " + wo + " (Stock ID: " + idstock + ")?")) {
        window.location.href = "vhs_cancel_wo.php?id_stock=" + encodeURIComponent(idstock);
      }
    });
    </script>
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

  </body>
</html>