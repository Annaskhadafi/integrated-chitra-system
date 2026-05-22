<?php 
$idstock= $_POST['idstock'];
// echo $idstock;
?>
<?php 
    include "koneksi.php";
    $perintahmodal=mysqli_query($koneksi6, "SELECT * from stock where id_stock=$idstock"); 
    $datamodal = mysqli_fetch_array($perintahmodal);
    $do=$datamodal['do'];
    $id_forecast=$datamodal['id_forecast'];
    $delivery_date=$datamodal['delivery_date'];
?>

<h2><b>Received delivery order :</b> <?php echo $do;?></h2>
<form action="updatereceivestockvhs.php" method="POST" data-parsley-validate class="form-horizontal form-label-left">
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <!--<input class="form-control" type="date" value="<?php echo $loop;?>" name="loop"/>-->
            <input class="form-control" type="hidden" value="<?php echo $do;?>" name="do"/>
            <input class="form-control" type="hidden" value=<?php echo $id_forecast;?> name="idforecast"/>
            <label>Received date : </label><input class="form-control" type="date" value=<?php echo $delivery_date;?> min="<?php echo $delivery_date;?>"name="deliverydate"/>
        </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit" class="btn btn-primary">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </div>
</form>
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jquery.inputmask -->
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <!-- /jquery.inputmask -->