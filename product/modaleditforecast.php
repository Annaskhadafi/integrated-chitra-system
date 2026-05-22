<?php 
$idforecast= $_POST['idforecast'];
// echo $idforecast;
?>
<h2>Edit Forecast</h2>
<form  class="form-inline" role="form" action="updateforecast.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($koneksi6, "SELECT * from forecast where id_forecast=$idforecast");
  $datamodal = mysqli_fetch_array($perintahmodal) ?>
    <input class="form-control" value="<?php echo  $idforecast; ?>" type="hidden" name="id_forecast"/>
    <input class="form-control" value="<?php echo date("Y-m-d");?>" type="hidden" name="editdate"/>
    <input class="form-control" type="text" value="<?php echo  $datamodal['size']; ?>" name="size" />
    <input class="form-control" type="text" value="<?php echo  $datamodal['quantity']; ?>" name="qty" />
    <input class="form-control" type="month" value="<?php echo substr($datamodal['submit_date'],0,7); ?>" min="<?php echo date("Y-m");?>" name="submitdate" />
    <input class="form-control" type="text" value="<?php echo  $datamodal['project']; ?>" name="project"/>
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>