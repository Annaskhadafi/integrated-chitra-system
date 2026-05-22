<?php 
//tangkap nilai idsupplier, inisialisasi $idsupplier
$idsupplier= $_POST['idsupplier'];
?>
<!-- form edit data tire_supplier -->
<form  class="form-inline" role="form" action="updatesupplier.php" method="post">
        <?php include "koneksi.php";
        $perintahmodal=mysqli_query($sambung, "select * from supplier where id_supplier = $idsupplier");
        $datamodal = mysqli_fetch_array($perintahmodal) ?>
        <input class="form-control" value= "<?php echo  $datamodal['id_supplier']; ?>" type="hidden" name="id_supplier"/>
          <input class="form-control" type="text" value="<?php echo  $datamodal['supplier']; ?>" name="supplier" />
          <button type="submit" value="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
</form>