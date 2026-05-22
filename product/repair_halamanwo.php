<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "header.php"; // call sectionhead.php as library
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "template_menu.php";
        ?>
        <?php if($name!=""){ ?>
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="x_title">
                        <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-6">
                                    <div class="x_panel">
                                        <div class="x_content">
                                            <div class="x_title">
                                                <h3>Work order list </h3>  
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary"><?php echo $tahun; ?></button>
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="caret"></span>
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                      <li><a href="halamanwo.php?year=<?php echo $tahunini;?>"><?php echo $tahunini; ?></a>
                                                      </li>
                                                      <li><a href="halamanwo.php?year=<?php echo $tahunini-1;?>"><?php echo $tahunini-1; ?></a>
                                                      </li>
                                                      <li><a href="halamanwo.php?year=<?php echo $tahunini-2;?>"><?php echo $tahunini-2; ?></a>
                                                      </li>
                                                      <li><a href="halamanwo.php?year=<?php echo $tahunini-3;?>"><?php echo $tahunini-3; ?></a>
                                                      </li>
                                                    </ul>
                                                </div>
                                            </div>                  
                                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                                <thead style="background:#f5f5f5;">
                                                    <tr>
                                                      <th>No</th>
                                                      <th>Work_order</th>
                                                      <th>Size</th>
                                                      <th>SN</th>
                                                      <th>Injury</th>
                                                      <th>Job</th>
                                                      <th>Type</th>
                                                      <th>Customer</th>
                                                      <th>Site</th>
                                                      <th>Rcv_date</th>
                                                      <th>Insp_date</th>
                                                      <th>Wo_date</th>
                                                      <th>Finish_dte</th>
                                                      <th>Invoice</th>
                                                      <th>Invoice Date</th>
                                                      <th>Repair_Loc</th>
                                                      <th>Create_by</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                    </tr>
                                                 </thead>
                                                <tbody>
                            <?php 
                            
                            $perintah = mysqli_query($koneksi3, "SELECT wo,job,date
                                                                FROM job
                                                                WHERE  job='painting' ");
                            $finish = array();
                            while ($data = mysqli_fetch_array($perintah)) {
                                $finish[$data['wo']]=$data['date'];
                            }
                            
                            $loc = $_GET['loc'] ?? '';
                            if ($loc === '') {
                                $perintah = mysqli_query($koneksi3, "
                                    SELECT *
                                    FROM work_order a
                                    WHERE a.received_date LIKE '$tahun%'
                                ");
                            } else {
                                $perintah = mysqli_query($koneksi3, "
                                    SELECT *
                                    FROM work_order a
                                    WHERE a.received_date LIKE '$tahun%'
                                    AND a.store_loc = '$loc'
                                ");
                            }
                            $no=1;
                        
                            while ($data = mysqli_fetch_array($perintah)) { 
                              $bast=$data['bast'];
                              $status=$data['status'];
                              $jobtype=$data['job_type'];
                              $tiretype=$data['type'];
                              
                              // $repair_type = $data['repair_type'];
                              if($status=='w/ work_order'){?>
                                <tr>
                                    <?php if($level==1){?>
                                    <form method="POST" action="repair_updatewo.php"> 
                                    <?php } ?>
                                        <td><?php echo $no++; ?></td>
                                        <td><input type="text" name="wo" value="<?php echo $data['wo']; ?>" required></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><?php echo $data['job_type']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td> <input type="date" name="date" value="<?php echo $data['wo_date']; ?>" max="<?php echo date('Y-m-d'); ?>" required></td>
                                        <td><?php echo $finish[$data['id_wo']]; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        <td>
                                            <input type="hidden" name="name" value="<?php echo $name; ?>">
                                            <input type="hidden" name="idwo" value="<?php echo $data['id_wo']; ?>">
                                            <button type="submit">Save</button>
                                        </td>
                                    </form>                  
                                </tr>
                                  <?php
                              }
                              elseif ($status=='Complete'){?>  
                                <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <?php echo $data['wo'];?>
                                        </td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><?php echo $data['job_type']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']]; ?></td>
                                        <td><?php echo $data['invoice']; ?></td>
                                        <td><?php echo $data['invoice_date']; ?></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#editModal<?php echo $data['id_wo']; ?>"
                                                title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>   
                                            <a href="repair_jobcard.php?id=<?php echo $data['id_wo']; ?>" class="btn btn-sm btn-primary">Detail</a>
                                        </td>               
                                </tr>
                                <?php
                              }
                              elseif ($status=='Progress'){?>  
                                <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <?php echo $data['wo']; ?>
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#editModal<?php echo $data['id_wo']; ?>"
                                                title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </button>   
                                        </td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><?php echo $data['job_type']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']]; ?></td>
                                        <td><?php echo $data['invoice']; ?></td>
                                        <td><?php echo $data['invoice_date']; ?></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        <td>
                                        </td>               
                                </tr>
                                <?php
                              }
                              else{?>  
                                <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $data['wo']; ?></td>
                                        <td><?php echo $data['size']; ?></td>
                                        <td><?php echo $data['tire_sn']; ?></td>
                                        <td><?php echo $data['injury']; ?></td>
                                        <td><?php echo $data['job_type']; ?></td>
                                        <td><?php echo $tiretype; ?></td>
                                        <td><?php echo $data['customer']; ?></td>
                                        <td><?php echo $data['site']; ?></td>
                                        <td><?php echo $data['received_date']; ?></td>
                                        <td><?php echo $data['inspect_date']; ?></td>
                                        <td><?php echo $data['wo_date']; ?></td>
                                        <td><?php echo $finish[$data['id_wo']]; ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $data['store_loc']; ?></td>
                                        <td><?php echo $data['createby']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        <td>
                                        </td>               
                                </tr>
                                <?php 
                                }
                              $no++; ?>
                            <div class="modal fade" id="editModal<?php echo $data['id_wo']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $data['id_wo']; ?>" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                            
                                  <form action="repair_updatewo.php" method="POST">
                                    <div class="modal-body">
                                      <input type="hidden" name="idwo" value="<?php echo $data['id_wo']; ?>">
                                      <input type="hidden" name="status" value="<?php echo $data['status']; ?>">
                            
                                      <div class="form-group">
                                        <label for="wo">Work Order</label>
                                        <input type="text" class="form-control" name="wo" value="<?php echo $data['wo']; ?>" required>
                                      </div>
                            
                                      <div class="form-group">
                                        <label for="wo_date">Date</label>
                                        <input type="date" class="form-control" name="date" value="<?php echo $data['wo_date']; ?>" required>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="wo_date">Invoice</label>
                                        <input type="text" class="form-control" name="inv" value="<?php echo $data['invoice']; ?>">
                                      </div>  
                                      
                                      <div class="form-group">
                                        <label for="wo_date">Invoice Date</label>
                                        <input type="date" class="form-control" name="invdate" value="<?php echo $data['invoice_date']; ?>">
                                      </div>
                                      
                                    </div>
                            
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-success">Simpan</button>
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                  </form>
                            
                                </div>
                              </div>
                            </div>
                              <?php
                            } ?>
                        </tbody>
                                                
                                            </table>  
                                        </div>
                                    </div>
                                </div> 
                            </div>
          </div>
        </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <!-- modal edit data tire inventory -->
        <?php } ?>
    <!-- footer content -->
    <footer>
      <div class="pull-right">
        Repair Jobcard
      </div>
      <div class="clearfix">
      </div>
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
              responsive: true,
              order: [[ 0, "desc" ]]
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

        $datatable.dataTable({
          'order': [[ 0, "desc" ]],
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
  </body>
</html>