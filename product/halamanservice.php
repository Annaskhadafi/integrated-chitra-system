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
        <!-- page content -->        
        <div class="right_col" role="main">
          <div class="">
            <?php if($level==1){?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="x_title">
            <?php } 
            else {}?>
            <div class="clearfix"></div>
            <div class="row">
            <div class="col-md-12 col-sm-6 col-xs-6">
              <div class="x_panel">
                <div class="x_content">
                  <div class="x_title">
                    <h3>Work Order Service </h3>  
                  </div>                  
                  <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead style="background:#f5f5f5;">
                        <tr>
                          <th>No</th>
                          <th>No_Quot</th>
                          <th>Quot_Date</th>
                          <th>Po</th>
                          <th>Po_Date</th>
                          <th>Work_order</th>
                          <th>Wo_Date</th>
                          <th>Costumer</th>
                          <th>Job_Desk</th>
                          <th>Price</th>
                          <th>BAST</th>
                          <th>BAST Date</th>
                          <th>Invoice</th>
                          <th>Invoice Date</th>
                          <th>Create_by</th>
                          <th>Status</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $perintah = mysqli_query($koneksi4, "SELECT * FROM work_orderr JOIN costumer JOIN costumer_data ON work_orderr.costumer = costumer.id_costumer AND costumer.nama_costumer = costumer_data.id_cost");
                        $no=1;
                        while ($data = mysqli_fetch_array($perintah)) { 
                          $bast=$data['bast'];
                          $status=$data['status'];
                          // $repair_type = $data['repair_type'];
                          if($status==1 && $level!=1){}                          
                          else{?>
                            <tr>                            
                                <td><?php $no=$data['no'];echo $no;?> </td>
                                <td><?php echo $data['no_quot'];?> </td>
                                <td><?php echo $data['quot_date'];?> </td>
                                <td><?php echo $data['po'];?> </td>
                                <td><?php echo $data['po_date'];?> </td>
                                <td><?php echo $data['work_order'];?> </td>
                                <td><?php echo $data['wo_date'];?> </td>
                                <td><?php echo $data['cost_name'];?></td>
                                <td><?php echo $data['job_desk'];?> </td>
                                <td><?php echo $data['price'];?></td>
                                <td><?php echo $data['bast'];?></td>
                                <td><?php echo $data['bast_date'];?></td>
                                <td><?php echo $data['invoice'];?></td>
                                <td><?php echo $data['invoice_date'];?></td>
                                <td><?php echo $data['create_by']; ?></td>
                              <!-- nanti kondisi if po,bast,invoice beserta button taro di bawah sini yaaaa-->
                                <td>
                                  <?php 
                                      if ($status==1){$stat="On Progress";}
                                      else{$stat="Finish";}
                                      echo $stat;
                                  ?> 
                                </td>  
                                <td><?php 
                                  if($level==1){?>
                                      <button href="#" class="editdatawo btn btn-info btn-xs" data-no="<?php echo $data['no'];?>">Edit</button>
                                      <?php
                                  }?>
                                </td>
                            </tr>    
                            <?php 
                            }
                          $no++;
                        } ?>
                    </tbody>
                    </table>            
                </div>
              </div>
            </div> 
          </div>
          </div>
        </div>
        <!-- /page content -->
<!-- modal edit data tire inventory -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
          </div>
        </div>
      </div>
</div>
    
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
    <!-- JS pop up modal -->
    <script>
        // $(function(){
        //     $(document).on('click','.editdatawo',function(e){
        //         e.preventDefault();
        //         $("#myModal").modal('show');
        //         $.post('modalupdatehalamanwoservice.php',
        //             {
                      
        //               no:$(this).attr('data-no')
        //             },
        //             function(html){
        //                 $(".modal-body").html(html);
        //             }   
        //         );
        //     });
        // });
        $(function(){
            $(document).on('click','.reject',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modalrejectwo.php',
                    {
                      idwo:$(this).attr('data-idwo')
                    },
                    function(html){
                        $(".modal-body").html(html);
                    }   
                );
            });
        });
        $(function(){
            $(document).on('click','.hiddensn',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modalstatushidden.php',
                    {
                      idwo:$(this).attr('data-idwo')
                    },
                    function(html){
                        $(".modal-body").html(html);
                      }   
                );
            });
        });
        $(function(){
            $(document).on('click','.show',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modalstatusshow.php',
                    {
                      idwo:$(this).attr('data-idwo')
                    },
                    function(html){
                        $(".modal-body").html(html);
                      }   
                );
            });
        });
        $(function(){
            $(document).on('click','.bast',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modalupdatebastposervice.php',
                    {
                      no:$(this).attr('data-no')
                    },
                    function(html){
                        $(".modal-body").html(html);
                    }   
                );
            });
        });
        $(function(){
            $(document).on('click','.editdatawo',function(e){
                e.preventDefault();
                $("#myModal").modal('show');
                $.post('modalupdatehalamanwoservice.php',
                    {
                      no:$(this).attr('data-no')
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