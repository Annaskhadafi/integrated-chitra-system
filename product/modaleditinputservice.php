<?php 
//tangkap nilai idpattern, inisialisasi $idpattern
$idusage= $_POST['idusage'];
?>
<form  class="form-inline" role="form" action="updateinputmaterial.php" method="post">
  <?php include "koneksi.php";
  $perintahmodal=mysqli_query($sambung, "SELECT * from mat_usage a,mat_inventory b where a.inv=b.id_inv and a.id_usage=$idusage");
  $datamodal = mysqli_fetch_array($perintahmodal) ?>
    <input class="form-control" value= "<?php echo  $datamodal['id_usage'];?>" type="hidden" name="idusage"/>
    <input class="form-control" value= "<?php echo  $datamodal['id_inv'];?>" type="hidden" name="idinv"/>
    <input class="form-control" type="hidden" value="<?php echo  $datamodal['qty']; ?>" name="qtysebelum" />
    <label><?php echo  $datamodal['desc'];?> : </label><br>
    <input class="form-control" type="number" value="<?php echo  $datamodal['qty']; ?>" name="qty" />
    <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>