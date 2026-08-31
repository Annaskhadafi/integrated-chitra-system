<?php
include_once "koneksi.php";
include_once "auth_check.php";
require_access($koneksi, array(), array(1));
?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <?php include "template_menu.php";?>          
          <!--tampilkan alert jika data tidak semua diinput-->
          <!-- page content -->
          <div class="right_col" role="main">
            
          <?php if($name!=""){ ?>
            <div class="clearfix"></div>
            <div class="row"><div class="col-md-12 col-sm-6 col-xs-6">               
                <div class="row">
                      <div class="col-md-12 col-sm-6 col-xs-6">
                        <div class="x_panel">
                          <div class="x_content">
                            <div class="x_title">
                              <h3>List Tire Problem From Customers</h3>  
                            </div>
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                              <thead style="background:#f5f5f5;">
                                <tr>
                                  <th>No</th>
                                  <th>Customer</th>
                                  <th>Site</th>
                                  <th>Tire Size</th>
                                  <th>Brand</th>
                                  <th>Tire_Desc</th>
                                  <th>Compound</th>
                                  <th>SN_Tire</th>
                                  <th>OTD</th>
                                  <th>RTD</th>
                                  <th>Worn</th>
                                  <th>Injury</th>
                                  <th>Area</th>
                                  <th>Prob_Cause</th>
                                  <th>Act_Plan</th>
                                  <th>Price</th>
                                  <th>Lifetime</th>
                                  <th>Target</th>
                                  <th>Est_Loss</th>
                                  <th>Submit Date</th>
                                  <th>Date_Accept/Reject</th>
                                  <th>Date_Closed</th>
                                  <th>Aging</th>
                                  <th>Aksi</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $modalsn = array();
                                $q2 = mysqli_query ($koneksi5,("SELECT *,b.customer,
                                                                IF(a.act_plan='Done',DATEDIFF(a.date_closed,a.date_in),
                                                                    IF(a.act_plan='Reject',DATEDIFF(a.date_accept,a.date_in),DATEDIFF(CURDATE(),a.date_in))
                                                                ) as aging 
                                    FROM chitraparatama_warranty.tab_warranty a,chitraparatama_fleetlist.customer_master b
                                    where a.costumer=b.id_customer_master"));                                
                                $urut = 1;
                                while($r2 = mysqli_fetch_array($q2)){
                                  $no = $r2['no'];
                                  $tire_size = $r2['tire_size'];
                                  $brand = $r2['brand'];
                                  $tire_desc = $r2['tire_desc'];
                                  $compound_tire = $r2['compound_tire'];
                                  $sn_tire = $r2['sn_tire'];
                                  $lifetime = $r2['lifetime'];
                                  $area = $r2['area'];
                                  $otd = $r2['otd'];
                                  $rtd = $r2['rtd'];
                                  $worn = $r2['worn'];
                                  $customer = $r2['customer'];
                                  $site = $r2['site'];
                                  $injury = $r2['injury'];
                                  $prob_cause = $r2['prob_cause'];
                                  $date_accept = $r2['date_accept'];
                                  $act_plan = $r2['act_plan'];
                                  $date_closed = $r2['date_closed'];
                                  $price = $r2['price'];
                                  $target = $r2['target'];
                                  $est_loss = $r2['est_loss'];
                                  $date_submit = $r2['date_in'];
                                  $aging = $r2['aging'];
                                  $modalsn[]=$no;
                                  ?> 
                                  <tr>
                                      <td><?php echo $no; ?></td>
                                      <td><?php echo $customer; ?></td>
                                      <td><?php echo $site; ?></td>
                                      <td><?php echo $tire_size; ?></td>
                                      <td><?php echo $brand; ?></td>
                                      <td><?php echo $tire_desc; ?></td>
                                      <td><?php echo $compound_tire; ?></td>
                                      <td><?php echo $sn_tire; ?></td>
                                      <td><?php echo $otd; ?></td>
                                      <td><?php echo $rtd; ?></td>
                                      <td><?php echo number_format($worn,2).'%';?></td>
                                      <td><?php echo $injury ?></td>
                                      <td><?php echo $area ?></td>
                                      <td><?php echo $prob_cause ?></td>
                                      <td><?php echo $act_plan ?></td>
                                      <td><?php echo $price ?></td>
                                      <td><?php echo $lifetime; ?></td>
                                      <td><?php echo $target ?></td>
                                      <td><?php echo $est_loss ?></td>
                                      <td><?php echo $date_submit; ?></td>
                                      <td><?php echo $date_accept ?></td>
                                      <td><?php echo $date_closed; ?></td>
                                      <td><?php echo $aging; ?></td>
                                      <td>
                                        <a href="#" class="editinvent" data-idwarranty="<?php echo $no;?>"data-item="warranty"><button type="button" class="btn btn-summary">Edit</button></a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete<?php echo $no;?>">Delete</button>
                                      </td>
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
          <?php } ?>
          
          <?php foreach($modalsn as $modalsna){
                                $q2 = mysqli_query ($koneksi5,("SELECT * from tab_warranty where no=$modalsna"));
                                $r2 = mysqli_fetch_array($q2);
                                $actp = $r2['act_plan'];
                                ?>
                                <!-- Modal Delete-->
                                <div class="modal fade" id="modalDelete<?php echo $modalsna; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <p> Are you sure to delete it? </p>
                                        <div class="modal-footer">
                                          <form  class="form" role="form" action="delete.php" method="post">
                                            <input type="text" hidden name="sn_tire" value="<?php echo $r2['sn_tire']; ?>" readonly>
                                            <button type="submit" class="btn btn-secondary">Yes</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                 
                                <?php
                              }?>
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                              <h4 class="modal-title" id="myModalLabel">Edit Warranty</h4>
                                          </div>
                                          <div class="modal-body">
                                          </div>
                                      </div>
                                  </div>
                              </div>
        </div>
      </div>  

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
              responsive: true,
              order: [ 0,'desc'],
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

        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });  
    </script>
    <!-- Mengarahkan ke halaman modal (modalEditMaster-->
    <script>
      $(function(){
        $(document).on('click','.editinvent',function(e){
          e.preventDefault();
          $("#myModal").modal('show');
          $.post('modalEditDataMaster.php',
          {idwarranty:$(this).attr('data-idwarranty'),
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