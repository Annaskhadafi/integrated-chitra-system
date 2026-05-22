<?php 
//tangkap nilai idinv, inisialisasi $idinv
$idmat= $_POST['idmat'];
?>
<h3>Minimal Qty Stock</h3><br>
<form  class="form-inline" role="form" action="updateinputmaterial.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($sambung, "SELECT * from mat_inventory where id_inv=$idmat");
  $datamodal = mysqli_fetch_array($perintahmodal) ?>
    <input class="form-control" value= "<?php echo  $datamodal['id_inv'];?>" type="hidden" name="idinv"/>
    <label><?php echo  $datamodal['desc'];?> : </label>
    <input class="form-control" type="number" value="<?php echo  $datamodal['min']; ?>" name="min" />
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>