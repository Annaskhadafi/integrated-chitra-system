<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include "sectionhead.php"; // call sectionhead.php as library
    //$idunit = $_GET['idunit'];
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php 
          include "koneksi.php";
          include "template_menu_adm.php";
        ?>
         <!-- page content -->        
        <div class="right_col" role="main">
          <div class="clearfix"></div>
          <div class="row">  
            <h3>Site & Unit Data Master</h3>            
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Site</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content"> 
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                              <th>Site Name</th>
                              <th>Target</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                            include "koneksi.php";
                            $perintah = mysqli_query($sambung, "SELECT * 
                            from site");
                            $no=1;
                            while ($data = mysqli_fetch_array($perintah)) { ?>
                              <tr>
                                <td><?php echo $data['site'];?></td>
                                <td><?php echo $data['target'];?></td>
                                <td>
                                  <a href="#" class="editsite" data-idsite="<?php echo  $data['id_site']; ?>">Edit </a>|
                                  <a href="hapussite.php?idsite=<?php echo $data['id_site']; ?>" onclick="javascript: return confirm('Delete site [<?php echo $data['site']; ?>] from list ?')">Delete</a>
                                </td>
                              </tr>    
                              <?php $no++; } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel tile">
                    <div class="x_title">
                      <h2>Unit</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">             
                        <form  class="form-inline-sm" role="form" action="tambahunit.php" method="post">
                          <div class="row">                             
                            <div class="col-md-2 col-sm-2 col-xs-6">
                              <input class="form-control" type = "text" name = "unit" placeholder="Model"/>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-6">
                              <input class="form-control" type = "number" name = "tire" placeholder="Tire qty"/> 
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-6">
                              <SELECT class="form-control" name="size"> 
                                <option value="">Size</option>     
                                <?php $perintah=mysqli_query($sambung, "SELECT * FROM tire_size GROUP BY size");$no =1;    
                                while ($data = mysqli_fetch_array($perintah)) {?>    
                                <option value=<?php echo  $data['size']; ?>><?php echo  $data['size']; ?></option>     
                                <?php $no++; }?>         
                              </SELECT> 
                            </div>
                            <div class="col-md-2 col-sm-1 col-xs-6"> 
                              <input class="form-control" type = "number" name = "axl2" placeholder="Axl 2 tire"/> 
                            </div>
                            <div class="col-md-2 col-sm-1 col-xs-6">
                              <input class="form-control" type = "number" name = "axl4" placeholder="Axl 4 tire"/>
                            </div> 
                            <div class="col-md-2 col-sm-1 col-xs-6">
                              <input class="form-control" type = "number" name = "axl8" placeholder="Axl 8 tire"/> 
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-6">
                            <button type="submit" value="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> Submit</button>
                            </div>                        
                          </div>
                        </form>
                      </div>
                        <table class="table table-striped table-bordered">
                          <thead style="background:#f5f5f5;">
                            <tr>
                            <th>Unit Model</th>
                            <th>Tire Qty</th>                            
                            <th>Tire Size</th>                      
                            <th>Axl2tire</th>                      
                            <th>Axl4tire</th>                      
                            <th>Axl8tire</th>
                            <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php 
                              include "koneksi.php";
                              $perintah = mysqli_query($sambung, "SELECT * 
                                from unit");
                              $no=1;
                              while ($data = mysqli_fetch_array($perintah)) { ?>
                                <tr>
                                  <td><?php echo $data['unit'];?></td>                                          
                                  <td><?php echo $data['tire'];?></td>                                                                                    
                                  <td><?php echo $data['size'];?></td>
                                  <td><?php echo $data['axl2tire'];?></td>
                                  <td><?php echo $data['axl4tire'];?></td>
                                  <td><?php echo $data['axl8tire'];?></td>
                                  <td><a href="#" class="editunit" data-idunit="<?php echo  $data['id_unit']; ?>">Edit </a>|
                                    <a href="hapusunit.php?idunit=<?php echo $data['id_unit']; ?>" 
                                      onclick="javascript: return confirm('Delete unit model [<?php echo $data['unit']; ?>] from list ?')">Delete</a></td>
                                </tr>    
                                <?php $no++; 
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
        <!-- /page content -->
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

      <script>window.jQuery || document.write('<script src=""../vendors/js/jquery.min.js"><\/script>')</script>
      <script src="../vendors/js/bootstrap.min.js"></script>
      <script src="../vendors/js/docs.min.js"></script>
    <script>
    $(document).ready(function(){
      // Add scrollspy to <body>
      $('body').scrollspy({target: ".navbar", offset: 50});   

      // Add smooth scrolling on all links inside the navbar
      $("#myNavbar a").on('click', function(event) {

        // Prevent default anchor click behavior
        event.preventDefault();

        // Store hash
        var hash = this.hash;

        // Using jQuery's animate() method to add smooth page pre-scrollable
        // The optional number (800) specifies the number of milliseconds it takes to pre-scrollable to the specified area
        $('html, body').animate({
          scrollTop: $(hash).offset().top
        }, 800, function(){
       
          // Add hash (#) to URL when done scrolling (default click behavior)
          window.location.hash = hash;
        });
      });
    });
    </script>
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
        <script>
            $(function(){
                $(document).on('click','.editsite',function(e){
                    e.preventDefault();
                    $("#myModal").modal('show');
                    $.post('modaleditsite.php',
                        {idsite:$(this).attr('data-idsite')},
                        function(html){
                            $(".modal-body").html(html);
                        }   
                    );
                });
            });
        </script>
        <script>
            $(function(){
                $(document).on('click','.editunit',function(e){
                    e.preventDefault();
                    $("#myModal").modal('show');
                    $.post('modaleditunit.php',
                        {idunit:$(this).attr('data-idunit')},
                        function(html){
                            $(".modal-body").html(html);
                        }   
                    );
                });
            });
        </script>
        <script>
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
  <footer>
    <div class="pull-right">
       Chitra Tire System by Chitra Paratama @2017
    </div>
    <div class="clearfix"></div>
  </footer>
</html>